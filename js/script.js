document.addEventListener('DOMContentLoaded', function() {
    var slideIndex = 1;
    showSlide(slideIndex);

    document.querySelector('.hero-image-slider .prev').addEventListener('click', function() {
        showSlide(slideIndex -= 1, 'right');
    });

    document.querySelector('.hero-image-slider .next').addEventListener('click', function() {
        showSlide(slideIndex += 1, 'left');
    });

   function showSlide(n, direction) {
    var slides = document.querySelectorAll('.hero-image-slide');
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (var i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';
        slides[i].classList.remove('slide-left');
        slides[i].classList.remove('slide-right');
    }
    slides[slideIndex-1].style.display = 'block';
    if (direction === 'left') {
        slides[slideIndex-1].classList.add('slide-left');
    } else if (direction === 'right') {
        slides[slideIndex-1].classList.add('slide-right');
    }
}

showSlide(slideIndex);
});