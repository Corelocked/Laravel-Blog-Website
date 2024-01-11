document.addEventListener('click', function (e) {
    const modal = document.querySelector('.modal');

    if (e.target.classList.contains('modal')) {
        if (modal.style.display === 'flex') {
            modal.style.display = 'none';
        }
    }
});

document.querySelector('.profile').addEventListener('click', function () {
    document.querySelector('.modal').style.display = 'flex';
});

document.querySelector('.close').addEventListener('click', function () {
    document.querySelector('.modal').style.display = 'none';
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
    if (i < 10) {i = "0" + i}
    return i;
}
