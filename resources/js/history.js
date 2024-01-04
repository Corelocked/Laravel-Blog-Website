// let last_id = 0;

// window.show = function (id) {
//     const url = "/dashboard/posts/history/" + id;
//
//     $.ajax({
//         type: "GET",
//         url: url,
//         processData: false,
//         contentType: false,
//         success: function (data) {
//             const category = $(".post__preview .post_container .info .category");
//             $(".preview_title").html(data.title);
//             category.html(data.category.name);
//             category.css('background', data.category.backgroundColor + 'CC');
//             category.css('color', data.category.textColor);
//             const postInfo = $(".post__preview .post_container .top .info");
//             const readTime = postInfo.find(".reading-info .reading-time");
//             const readInfo = postInfo.find(".reading-info");
//             if (data.read_time === null) {
//                 if (readInfo.length) { readInfo.empty() }
//             }
//             if (readTime.length) {
//                 readTime.html(data.read_time + " min");
//             } else {
//                 const readTimeTextElement = $('<p>').addClass('reading-text').text('Czas czytania: ');
//                 const watchIcon = $('<i>').addClass('fa-solid fa-clock');
//                 const readTimeElement = $('<p>').addClass('reading-time').text(data.read_time +' min');
//                 $(".post__preview .post_container .top .info .reading-info").append(readTimeTextElement, watchIcon, readTimeElement);
//             }
//             let date = new Date(data.created_at);
//             let formattedDate = date.toLocaleDateString("pl-PL", {
//                 day: "2-digit",
//                 month: "2-digit",
//                 year: "numeric",
//             });
//             let author = $(".date").html().slice(10);
//             $(".date").html(formattedDate + author);
//             let body =
//                 '<div class="actions"><a><i class="fa-solid fa-arrow-left"></i> Powrót do strony głównej</a><a>Następny post <i class="fa-solid fa-arrow-right"></i></a></div><div class="exit_preview" onClick="exitPreview();">Do góry <i class="fa-solid fa-arrow-up"></i></div>';
//             $(".post_body").html(data.body + body);
//             var output = document.getElementById("output");
//             output.src = data.image_path;
//
//             if (last_id !== id) {
//                 $(".h_" + id).addClass("active");
//                 $(".h_" + last_id).removeClass("active");
//             }
//             last_id = id;
//
//             if (window.innerWidth < 650) {
//                 const preview = document.querySelector(".post__preview");
//                 let pos = preview.offsetTop;
//                 let offset = 90;
//
//                 if (window.innerWidth <= 425) {
//                     offset = 62;
//                 }
//
//                 window.scrollTo({
//                     top: pos - offset,
//                     behavior: "smooth",
//                 });
//             }
//         },
//     });
// };

window.revert = function (postId, historyId) {
    Swal.fire({
        title: "Czy jesteś pewien?",
        html: "Czy na pewno chcesz przywrócić?<p style='font-size: 15px; font-weight: 400; margin-top: 5px;'>Informacja:<br>Po przywróceniu post zostanie zaktualizowany, będzie można dodatkowo edytować.</p>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Tak, przywróć!",
        cancelButtonText: "Anuluj",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                "/dashboard/posts/history/" +
                postId +
                "/" +
                historyId +
                "/revert";
        }
    });
};

window.extend_history = function () {
    const history_list = document.getElementById('history_list');
    const extend_history = document.querySelector('.extend-history');

    if (history_list.style.height === '0px') {
        history_list.style.height = 'auto';
        history_list.style.visibility = 'visible';
        extend_history.innerHTML = "Ukryj kompaktową historię";
    } else {
        history_list.style.height = '0px';
        history_list.style.visibility = 'hidden';
        extend_history.innerHTML = "Pokaż kompaktową historię";
    }

}

function convertDateTime(date) {
    var tzoffset = new Date().getTimezoneOffset() * 60000;

    date = new Date(Date.parse(date) - tzoffset)
        .toISOString()
        .slice(0, -1)
        .replace("T", " ");
    date = date.slice(0, -7);

    return date;
}
