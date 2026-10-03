// sha256sum hashes for Professional, Standard, and Basic accounts
// document.getElementById("professional").onclick = function(){
//   window.location.href ="./registr.php?reg=19c73a5cdf346d967e544a2838600bcc16a9fd39a52a4ba39f7351fcc6a65d4e"; 
// }

document.getElementById("standard").onclick = function(){
  window.location.href ="./registr.php?reg=ef6691545d2c5523efed00424407cb261aeb0037d165ca5792f7f8bac3381362"; 
}

document.getElementById("basic").onclick = function(){
  window.location.href ="./registr.php?reg=0e35f6e9742e074dfd62e874de3c242c6d9b64c21bf9afbaf3d11f05579b4495"; 
}

// Index.html Slideshow
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}

function showDivs(n) {
  var i;
  var x = document.getElementById("mySlides");
  var dots = document.getElementById("demodots");
    if (n > x.length) {slideIndex = 1}    
    if (n < 1) {slideIndex = x.length} ;
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" w3-white", "");
    }
    x[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " w3-white";
}