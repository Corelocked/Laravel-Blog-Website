$(document).ready(function(){
    $(".js-select2").select2({
        tags: true
    });
});
$(".js-select2").change(function() {
    $('#user').val($(".js-select2").val());
});

let maxHeight = $('.filtr_body').css('height');

toggleHeight();

function toggleHeight() {
    let e = document.querySelector('.filtr_body');
    
    if(e.style.height != '0px') {
        e.style.height = '0px';
        $('.button_collapse').removeClass('fa-caret-up').addClass('fa-caret-down');
    } else {
        e.style.height = maxHeight;
        $('.button_collapse').removeClass('fa-caret-down').addClass('fa-caret-up');
    }
}

$('.filtr_collapse').click(function (){
    toggleHeight();
});

function radioCheck(number){
    for(let i = 1; i <= 4; i++){
        if(number == i){
            $(".rec_" + i).addClass("active");
            $(".rec_" + i + " .dot").html('<i class="fa-solid fa-square-xmark"></i>');
        }else{
            $(".rec_" + i).removeClass("active");
            $(".rec_" + i + " .dot").html('<i class="fa-regular fa-square"></i>');
        }
    }
    switch(number){
        case 1:
            $('#limit').val("20");
            break;
        case 2:
            $('#limit').val("50");
            break;
        case 3:
            $('#limit').val('100');
            break;
        case 4:
            $('#limit').val('0');
            break;
    }
}

function filterCheck(number){
    if(number == 1){
        $(".f_2").removeClass("active");
        $(".f_2 .dot").html('<i class="fa-solid fa-circle-dot"></i>');
        $('#order').val('desc');
    }else{
        $(".f_1").removeClass("active");
        $(".f_1 .dot").html('<i class="fa-solid fa-circle-dot"></i>');
        $('#order').val('asc');
    }

    $(".f_" + number).addClass("active");
    $(".f_" + number + " .dot").html('<i class="fa-solid fa-circle-check"></i>');
}

if($('#order').val() == 'desc'){
    filterCheck(1);
}else{
    filterCheck(2);
}

$(".f_1").click(function(){
    filterCheck(1);
});
$(".f_2").click(function(){
    filterCheck(2);
});

switch(parseInt($('#limit').val())){
    case 0:
        radioCheck(4);
        break;
    case 20:
        radioCheck(1);
        break;
    case 50:
        radioCheck(2);
        break;
    case 100:
        radioCheck(3);
        break;
}

$(".rec_1").click(function(){
    radioCheck(1);
});
$(".rec_2").click(function(){
    radioCheck(2);
});
$(".rec_3").click(function(){
    radioCheck(3);
});
$(".rec_4").click(function(){
    radioCheck(4);
});

$('.show_results').click(function(){
    $('#filter_form').submit();
})