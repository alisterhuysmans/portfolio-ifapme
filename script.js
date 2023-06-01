document.addEventListener("DOMContentLoaded", function () {
    var carousel = document.querySelector(".carousel");
    var slider = carousel.querySelector(".slider");
    var slides = slider.querySelectorAll(".slide");
    var slideWidth = slides[0].offsetWidth;
    var slideCount = slides.length;
    var currentIndex = 0;

    // Set initial position of slider
    slider.style.transform = "translateX(0)";

    // Add event listener for next button
    document.querySelector(".next").addEventListener("click", function () {
        if (currentIndex < slideCount - 3) {
            currentIndex++;
            slider.style.transform =
                "translateX(-" + slideWidth * currentIndex + "px)";
        }
    });

    // Add event listener for previous button
    document.querySelector(".prev").addEventListener("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
            slider.style.transform =
                "translateX(-" + slideWidth * currentIndex + "px)";
        }
    });
});
