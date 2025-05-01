var q1 = document.getElementById("q1");
var q2 = document.getElementById("q2");
var q3 = document.getElementById("q3");
var q4 = document.getElementById("q4");
var q5 = document.getElementById("q5");
var next1 = document.getElementById('next1')
var back1 = document.getElementById('back1')
var next2 = document.getElementById('next2')
var back2 = document.getElementById('back2')
var next3 = document.getElementById('next3')
var back3 = document.getElementById('back3')
var next4 = document.getElementById('next4')
var back4 = document.getElementById('back4')
document.addEventListener('DOMContentLoaded', function () {
    let query = window.matchMedia("(max-width: 767px)");
    if (query.matches) {
        next1.onclick = function () {
            $('.wrapper').animate({ scrollTop: 0 }, 800);
            q1.style.left = "-850px";
            q2.style.left = "0px";
        }
        back1.onclick = function () {
            $('.wrapper').animate({ scrollTop: 0 }, 800);
            q1.style.left = "0px";
            q2.style.left = "850px";
        }
        back2.onclick = function () {
            $('.wrapper').animate({ scrollTop: 0 }, 800);
            alert("as");
            q1.style.left = "0px";
            q2.style.left = "850px";
        }
        next2.onclick = function () {
            $('.wrapper').animate({ scrollTop: 0 }, 800);
            q2.style.left = "-850px";
            q3.style.left = "0px";
        }
        back3.onclick = function () {
            q3.style.left = "0px";
            q4.style.left = "850px";
        }
        next3.onclick = function () {
            q3.style.left = "-850px";
            q4.style.left = "0px";
        }
        back4.onclick = function () {
            q4.style.left = "0px";
            q5.style.left = "850px";
        }
        next4.onclick = function () {
            q4.style.left = "-850px";
            q5.style.left = "0px";
        }
    } else {
            if(next1){
                next1.onclick = function () {
                    $('.wrapper').animate({ scrollTop: 0 }, 800);
                    q1.style.left = "-100%";
                    q2.style.left = "0px";
                }
            }
            if(back1){
                back1.onclick = function () {
                    $('.wrapper').animate({ scrollTop: 0 }, 800);
                    q1.style.left = "0px";
                    q2.style.left = "100%";
                }
            }
        if(back2){
                back2.onclick = function () {
                    $('.wrapper').animate({ scrollTop: 0 }, 800);
                    q2.style.left = "0px";
                    q3.style.left = "100%";
                }
            }
        if(next2){
                next2.onclick = function () {
                    $('.wrapper').animate({ scrollTop: 0 }, 800);
                    q2.style.left = "-100%";
                    q3.style.left = "0px";
                }
            }
        if(back3){
                back3.onclick = function () {
                    $('.wrapper').animate({ scrollTop: 0 }, 800);
                    q3.style.left = "0px";
                    q4.style.left = "100%";
                }
            }
        if(next3){
                next3.onclick = function () {
                    $('.wrapper').animate({ scrollTop: 0 }, 800);
                    q3.style.left = "-100%";
                    q4.style.left = "0px";
                }
            }
        if(back4){
            back4.onclick = function () {
                $('.wrapper').animate({ scrollTop: 0 }, 800);
                q4.style.left = "0px";
                q5.style.left = "100%";
            }
        }
        if(next4){
            next4.onclick = function () {
                $('.wrapper').animate({ scrollTop: 0 }, 800);
                q4.style.left = "-100%";
                q5.style.left = "0px";
            }
        }
}
});

function uncheck() {
    var rad = document.getElementById('rd')
    rad.removeAttribute('checked')
}
