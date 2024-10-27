document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    const closeBtn = document.getElementById('close-btn');

    menuToggle.addEventListener('click', function () {
        navLinks.classList.toggle('active');
    });

    closeBtn.addEventListener('click', function () {
        navLinks.classList.remove('active');
    });
});
function selectPackage(packageName) {
    // Redirect to the appointment form with the selected package as a query parameter
    window.location.href = `submit_form.php?package=${packageName}`;
}

// testimonals
let slideIndex = 0;
const slides = document.querySelectorAll('.testimonial');
const totalSlides = slides.length;

showSlides();

function showSlides() {
    const testimonialSlide = document.querySelector('.testimonial-slide');
    slideIndex++;
    if (slideIndex > totalSlides) {
        slideIndex = 1;
    }
    testimonialSlide.style.transform = `translateX(-${(slideIndex - 1) * 100}%)`;
    setTimeout(showSlides, 3000); // Change slide every 3 seconds
}

// services
document.addEventListener('DOMContentLoaded', function () {
    const serviceBoxes = document.querySelectorAll('.service-box');

    function checkScroll() {
        serviceBoxes.forEach((box, index) => {
            const boxTop = box.getBoundingClientRect().top;
            const triggerPoint = window.innerHeight - 150;

            if (boxTop < triggerPoint) {
                box.classList.add('visible');
            } else {
                box.classList.remove('visible');
            }
        });
    }

    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Trigger on load
});

