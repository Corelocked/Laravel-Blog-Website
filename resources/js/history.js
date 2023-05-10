let last_id = 0;

const show_actual = document.querySelector('.show_actual');
show_actual.addEventListener('click', (event) => {
    let url = window.location.pathname;
    let segments = url.split('/');
    let postId = segments[3]; 

    const get_post_url = '/dashboard/posts/' + postId + '/show';

    $.ajax({
        type: "GET",
        url: get_post_url,
        processData: false,
        contentType: false,
        success: function (data) {
            $('.preview_title').html(data.title);
            let date = new Date(data.created_at);
            let formattedDate = date.toLocaleDateString('pl-PL', { day: '2-digit', month: '2-digit', year: 'numeric' });
            let author = $('.date').html().slice(10);
            $('.date').html(formattedDate + author);
            let body = '<div class="actions"><a href=""><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a><a href="">Następny post <i class="fa-solid fa-arrow-right"></i></a></div><div class="exit_preview" onClick="exitPreview();">Do góry <i class="fa-solid fa-arrow-up"></i></div>';
            $('.post_body').html(data.body + body);
            var output = document.getElementById('output');
            output.src = data.image_path;

            if(last_id != 0){
                $('.h_0').addClass('active');
                $('.h_' + last_id).removeClass('active');
            }
            last_id = 0;

            if (window.innerWidth < 650) {
                const preview = document.querySelector('.post__preview');
                let pos = preview.offsetTop;
                let offset = 90;

                if(window.innerWidth <= 425){
                    offset = 62;
                }

                window.scrollTo({
                    top: pos - offset,
                    behavior: 'smooth'
                });
            }
        }        
    });

});

window.show = function(id){
    const url = '/dashboard/posts/history/' + id;
    
    $.ajax({
        type: "GET",
        url: url,
        processData: false,
        contentType: false,
        success: function (data) {
            $('.preview_title').html(data.title);
            let date = new Date(data.created_at);
            let formattedDate = date.toLocaleDateString('pl-PL', { day: '2-digit', month: '2-digit', year: 'numeric'});
            let author = $('.date').html().slice(10);
            $('.date').html(formattedDate + author);
            let body = '<div class="actions"><a href=""><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a><a href="">Następny post <i class="fa-solid fa-arrow-right"></i></a></div><div class="exit_preview" onClick="exitPreview();">Do góry <i class="fa-solid fa-arrow-up"></i></div>';
            $('.post_body').html(data.body + body);
            var output = document.getElementById('output');
            output.src = data.image_path;

            if(last_id != id){
                $('.h_' + id).addClass('active');
                $('.h_' + last_id).removeClass('active');
            }
            last_id = id;

            if (window.innerWidth < 650) {
                const preview = document.querySelector('.post__preview');
                let pos = preview.offsetTop;
                let offset = 90;

                if(window.innerWidth <= 425){
                    offset = 62;
                }

                window.scrollTo({
                    top: pos - offset,
                    behavior: 'smooth'
                });
            }
        }        
    });
}

window.revert = function(postId, historyId){
    Swal.fire({
        title: 'Czy jesteś pewien?',
        html: "Czy na pewno chcesz przywrócić?<p style='font-size: 15px; font-weight: 400; margin-top: 5px;'>Informacja:<br>Po przywróceniu post zostanie zaktualizowany, będzie można dodatkowo edytować.</p>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak, przywróć!',
        cancelButtonText: 'Anuluj',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/dashboard/posts/history/" + postId + "/" + historyId + "/revert";
            }
        })
}
window.exitPreview = function(){
    window.scrollTo({top: 0, behavior: 'smooth'});
}
function convertDateTime(date){
    var tzoffset = (new Date()).getTimezoneOffset() * 60000;

    date = (new Date(Date.parse(date) - tzoffset)).toISOString().slice(0, -1).replace('T', ' ');
    date = date.slice(0, -7)

    return date;
}