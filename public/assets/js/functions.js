function navBar() {
    var x = document.getElementById("navMenu");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    }
    else {
        x.className = x.className.replace(" w3-show", "");
    }
}

$(document).ready(function () {
    $("#showmore").click(function () {
        $("#more").toggleClass("w3-hide");
        $("#arrowup").toggleClass("w3-hide");
        $("#showmore").hide();
    });
});

function currentDiv(n) {
    showDivs(slideIndex = n);
  }
  
  function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    if (n > x.length) {slideIndex = 1}
    if (n < 1) {slideIndex = x.length}
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" w3-opacity-off", "");
    }
    x[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " w3-opacity-off";
  }

  function scrollToTrick(){
      window.scrollTo(0, document.getElementById('trick').offsetTop - 60);
  }
