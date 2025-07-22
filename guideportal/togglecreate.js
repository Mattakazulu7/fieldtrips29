// toggleviews.js
document.addEventListener("DOMContentLoaded", function() {
    const overlay = document.getElementById("overlay");
    const createTourButton = document.querySelector(".carousel-item:nth-child(1)"); // Adjust selector based on your button

    createTourButton.addEventListener("click", function() {
        overlay.style.display = overlay.style.display === "block" ? "none" : "block";
        document.body.style.overflow = overlay.style.display === "block" ? "hidden" : "auto"; // Prevent scrolling
    });

    // Add other event listeners for toggling views if needed
});
