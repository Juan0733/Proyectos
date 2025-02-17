document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.slider');
    const slides = slider.querySelectorAll('.slide');
    const images = slider.querySelectorAll('img');
    let currentIndex = 0;

    function nextSlide() {
        images[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % slides.length;
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        images[currentIndex].classList.add('active');
    }

    images[currentIndex].classList.add('active');

    setInterval(nextSlide, 5000);
});