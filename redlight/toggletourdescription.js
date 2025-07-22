document.addEventListener("DOMContentLoaded", function() {
    function toggleContent() {
        // Identify the elements within the Tour Description section
        var tourdescription = document.getElementById("descriptionContent");
        var button = document.querySelector(".show-more-description");

        // Check if the content is currently expanded or collapsed
        if (tourdescription.classList.contains("expanded")) {
            // If expanded, collapse the content
            tourdescription.classList.remove("expanded");
            button.textContent = "Show more";
        } else {
            // If collapsed, expand the content
            tourdescription.classList.add("expanded");
            button.textContent = "Show less";
        }
    }

    // Attach event listener to the button
    var button = document.querySelector(".show-more-description");
    if (button) {
        button.addEventListener("click", toggleContent);
    } else {
        console.error("Button not found");
    }
});
