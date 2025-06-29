<x-main-layout>
    <div class="article">
        <div class="contact_form">
            <div class="leave_message">Leave a message!</div>
            <div class="body_form">
                <img src="{{ asset('images/open.png') }}" alt="">
                <form method="POST">
                    <label>Name</label>
                    <input type="text" name="name">
                    <label>Email</label>
                    <input type="email" name="email">
                    <label>Message</label>
                    <textarea name="body"></textarea>
                    <input type="submit" value="Send">
                </form>
            </div>
        </div>
    </div>
</x-main-layout>