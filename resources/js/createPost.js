const Toast = Swal.mixin({
    toast: true,
    position: 'bottom',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

setInterval(() => {
    save();
}, 60000);

window.save = function(){
    const title = document.querySelector('input[name=title]').value;
    const excerpt = document.querySelector('textarea[name=excerpt]').value;
    const body = document.querySelector(".ql-editor").innerHTML;
    const image = document.querySelector('input[name=image]').files[0];
    const is_published = document.querySelector('input[name=is_published]').checked;
    const category = parseInt(document.querySelector('input[name=category_id]').value);
    const token = document.querySelector('input[name=_token]').value;

    const id = parseInt(document.querySelector('input[name=id_saved_post]').value);

    if(image || title !== '' || excerpt !== '' || body !== '<p><br></p>'){
        let form = new FormData();
        form.append('title', title);
        form.append('excerpt', excerpt);
        form.append('body', body);
        form.append('image', image);
        form.append('is_published', is_published);
        form.append('category_id', category);
        form.append('_token', token);

        if(id !== 0){
            form.append('_method', 'PATCH');
        }

        fetch("/dashboard/posts-saved" + (id ? '/' + id : ''), {
            method: "POST",
            body: form,
            headers: {
                'enctype': 'multipart/form-data',
                'X-CSRF-TOKEN': token,
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                Toast.fire({
                    icon: 'success',
                    title: 'Zapisano!'
                });
                if(id === 0) {
                    redirectToNewUrl(data);
                }
            })
            .catch(error => {
                Toast.fire({
                    icon: 'error',
                    title: 'Niezapisano!'
                });
                console.error('Fetch Error: ', error);
            });
    }
}

function redirectToNewUrl(data) {
    console.log(data);
    document.querySelector('input[name=id_saved_post]').value = data.id;
    const newUrl = "/dashboard/posts/create?edit=" + data.id;
    history.pushState(null, null, newUrl);
}
