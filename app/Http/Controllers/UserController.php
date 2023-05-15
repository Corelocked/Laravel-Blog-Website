<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);

        return view('user.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('user.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $input['image_path'] = '/images/user.png';

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $input['send_mail'] = $request->send_mail == 'on' ? true : false;

        if ($input['send_mail']) {
            $data['subject'] = 'Zostało założone konto na Blogu!';
            $data['user'] = $request->firstname.' '.$request->lastname;
            $data['rola'] = $request->roles;
            $data['login'] = $request->email;
            $data['password'] = $request->password;
            $data['toEmail'] = $data['login'];

            return redirect()->route('mail.send', [
                'data' => $data,
            ]);
        }

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = User::find($id);
        // return view('user.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if (! empty($user->roles[0])) {
            if ($user->roles[0]->name == 'Admin' && ! Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }

        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name')->all();

        if (! $userRole) {
            $userRole = $roles['Admin'];
        }

        return view('user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->profile_update) {
            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
            ]);

            if ($id != Auth::id()) {
                abort(403);
            }
        } else {
            $path = parse_url($request->headers->get('referer'), PHP_URL_PATH);
            $user_id = explode('/', $path)[3];

            if ($user_id != $id) {
                abort(403);
            }

            if (! Auth::User()->can('user-edit')) {
                abort(403);
            }

            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'roles' => 'required',
            ]);
        }

        $input = $request->all();

        if (! empty($input['password'])) {
            if ($request->profile_update) {
                $this->validate($request, [
                    'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
                    'password_confirmation' => 'min:8',
                ]);

                $input['password'] = Hash::make($input['password']);
            } else {
                $input['password'] = Hash::make($input['password']);
            }
        } else {
            $input = Arr::except($input, ['password']);
        }

        if (! empty($input['image'])) {
            $input['image_path'] = $this->storeImage($request);
        }

        $data = [];

        $user = User::find($id);

        if (! empty($user->roles[0])) {
            if ($user->roles[0]->name == 'Admin' && ! Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }

        $oldName = $user->firstname.' '.$user->lastname;

        if (! $request->profile_update) {
            if (! empty($user->roles[0])) {
                $oldRole = $user->roles[0]->name;
            }
        }

        $user->update($input);

        if ($request->roles) {
            DB::table('model_has_roles')->where('model_id', $id)->delete();

            $user->assignRole($request->input('roles'));
        }

        $changes = Arr::except($user->getChanges(), 'updated_at');

        if (! $request->profile_update) {
            if (! empty($user->roles[0])) {
                if ($oldRole != $user->roles[0]->name) {
                    $changes['rola'] = $user->roles[0]->name;
                    $data['rola'] = $user->roles[0]->name;
                }
            }
        }

        if (isset($changes['firstname'])) {
            $data['user'] = $changes['firstname'];
            $data['new_name'] = $data['user'];
        }
        if (isset($changes['lastname'])) {
            if (isset($changes['firstname'])) {
                $data['user'] = $changes['firstname'].' '.$changes['lastname'];
                $data['new_name'] = $data['user'];
            } else {
                $data['user'] = $request->firstname.' '.$changes['lastname'];
                $data['new_name'] = $data['user'];
            }
        } else {
            if (isset($changes['firstname'])) {
                $data['user'] = $changes['firstname'].' '.$request->lastname;
                $data['new_name'] = $data['user'];
            } else {
                $data['user'] = $request->firstname.' '.$request->lastname;
            }
        }

        if (isset($changes['email'])) {
            $data['login'] = $changes['email'];
            $data['toEmail'] = $changes['email'];
        } else {
            $data['toEmail'] = $request->email;
        }
        if (isset($changes['password'])) {
            $data['password'] = $request->password;
        }

        $input['send_mail'] = $request->send_mail == 'on' ? true : false;

        if ($changes) {
            $data['subject'] = 'Zostały wprowadzone zmiany na koncie.';
            $data['user'] = $oldName;
            if ($input['send_mail']) {
                return redirect()->route('mail.send', [
                    'data' => $data,
                ]);
            }
            if ($data['toEmail'] == 'admin@db.pl' || ! isset($data['toEmail'])) {
                return redirect()->back();
            }
        } else {
            if ($request->profile_update) {
                return redirect()->back();
            }
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::id() == $id) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if ($user->hasRole('Admin')) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('users.index');
    }

    private function storeImage($request)
    {
        $newImageName = uniqid().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images'), $newImageName);

        return '/images/'.$newImageName;
    }
}
