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
        e.style.height = 'auto';
        $('.button_collapse').removeClass('fa-caret-down').addClass('fa-caret-up');
    }
}

$('.filtr_collapse').click(function (){
    toggleHeight();
});

function radioCheck(number){
    for(let i = 1; i <= 4; i++){
        if(number === i){
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
});

window.changeView = function(name, mode) {
    let elements = null;

    if (mode === 'post') {
        elements = document.querySelectorAll(".posts-list .post");
        localStorage.setItem('postView', name);
    } else {
        elements = document.querySelectorAll(".comments-list .comment");
        localStorage.setItem('commentView', name);
    }

    if (name === 'tile') {
        document.querySelector('.view_button.list').classList.remove('active');
        document.querySelector('.view_button.tiles').classList.add('active');
        elements.forEach((el) => {
            el.classList.add('tile');
        });
    } else {
        document.querySelector('.view_button.tiles').classList.remove('active');
        document.querySelector('.view_button.list').classList.add('active');
        elements.forEach((el) => {
            el.classList.remove('tile');
        });
    }
}

let categoryArray = [];
if (document.querySelector('#categories')) {
    categoryArray = document.querySelector('#categories').value.split(',').map(Number);
    categoryArray = categoryArray.filter(number => number !== 0);
}

window.selectCategory = function (event, id) {
    const inputCategories = document.querySelector('#categories');
    let category = document.querySelector(".checkbox[data-category-id='" + id + "'] .check i")

    if (categoryArray.includes(id)) {
        category.classList.replace('fa-solid', 'fa-regular');
        category.classList.replace('fa-square-check', 'fa-square');
        categoryArray.splice(categoryArray.indexOf(id), 1);
        inputCategories.value = categoryArray;
    } else {
        category.classList.replace('fa-regular', 'fa-solid');
        category.classList.replace('fa-square', 'fa-square-check');
        categoryArray.push(id);
        inputCategories.value = categoryArray;
    }
}

let visibleCategories = false
window.categoriesToggle = function () {
    const categories_hidden = document.querySelectorAll(".checkbox.hidden");
    const toggleButton = document.querySelector(".categories_extend");

    if(visibleCategories) {
        categories_hidden.forEach((category) => {
            category.classList.remove("show");
        })
        visibleCategories = false;
        toggleButton.innerHTML = '<i class="fa-solid fa-chevron-down"></i> Pokaż więcej';
    } else {
        categories_hidden.forEach((category) => {
            category.classList.add("show");
        })
        visibleCategories = true;
        toggleButton.innerHTML = '<i class="fa-solid fa-chevron-up"></i> Ukryj';
    }
}

let userArray = [];
if (document.querySelector('#users')) {
    userArray = document.querySelector('#users').value.split(',').map(Number);
    userArray = userArray.filter(number => number !== 0);
}

window.selectUser = function (event, id) {
    let user = document.querySelector(".checkbox[data-user-id='" + id + "'] .check i")
    const inputUsers = document.querySelector('#users');
    if (userArray.includes(id)) {
        user.classList.replace('fa-solid', 'fa-regular');
        user.classList.replace('fa-square-check', 'fa-square');
        userArray.splice(userArray.indexOf(id), 1);
        inputUsers.value = userArray;
    } else {
        user.classList.replace('fa-regular', 'fa-solid');
        user.classList.replace('fa-square', 'fa-square-check');
        userArray.push(id);
        inputUsers.value = userArray;
    }
}

let searchForHighlighted = false;
let searchForNotHighlighted = false;
if (document.querySelector('#highlight')) {
    const inputHighlight = document.querySelector('#highlight');
    const cleanedValue = inputHighlight.value;
    let arrayValue = cleanedValue.split(',');
    let numericArray = arrayValue.map(Number);

    searchForHighlighted = numericArray[0] === 1;
    searchForNotHighlighted = numericArray[1] === 1;
}
window.selectHighlight = function (value) {
    let highlightValue = document.querySelector(".checkbox[data-highlight='" + value + "'] .check i");
    const inputHighlight = document.querySelector('#highlight');
    let select;

    if (value === "yes") {
        searchForHighlighted = !searchForHighlighted;
        select = searchForHighlighted;
    } else {
        searchForNotHighlighted = !searchForNotHighlighted;
        select = searchForNotHighlighted;
    }

    inputHighlight.value = [searchForHighlighted ? 1 : 0, searchForNotHighlighted ? 1 : 0];

    if (select) {
        highlightValue.classList.replace('fa-regular', 'fa-solid');
        highlightValue.classList.replace('fa-square', 'fa-square-check');
    } else {
        highlightValue.classList.replace('fa-solid', 'fa-regular');
        highlightValue.classList.replace('fa-square-check', 'fa-square');
    }

}
