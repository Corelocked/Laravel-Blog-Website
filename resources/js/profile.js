$(document).click(function (e){
    if(e.target.className == "modal"){
        if($('.modal').css('display', 'flex')){
            $('.modal').css('display', 'none');
        }
    }
});
$('.profile').on('click', function(){
    $('.modal').css('display', 'flex');
});
$('.close').on('click', function(){
    $('.modal').css('display', 'none');
});
startTime();
function startTime() {
    const today = new Date();
    let h = today.getHours();
    let m = today.getMinutes();
    let s = today.getSeconds();
    m = checkTime(m);
    h = checkTime(h);
    document.getElementById('hours').innerHTML = h;
    document.getElementById('minutes').innerHTML = m;
    setTimeout(startTime, 1000);
}

function checkTime(i) {
    if (i < 10) {i = "0" + i};
    return i;
}