document.addEventListener("DOMContentLoaded", function() {
    function toggleContent() {
        var guruInfo = document.getElementById("guruInfo");
        if (!guruInfo) {
            console.error("Element with id 'guruInfo' not found");
            return; // Exit if element not found
        }
        var button = guruInfo.querySelector(".show-more");

        if (guruInfo.classList.contains("expanded")) {
            guruInfo.classList.remove("expanded");
            button.textContent = "Show more";
        } else {
            guruInfo.classList.add("expanded");
            button.textContent = "Show less";
        }
    }

    // Attach event listener to the button
    var button = document.querySelector("#guruInfo .show-more"); // Ensure it targets the right element within guruInfo
    if (button) {
        button.addEventListener("click", toggleContent);
    } else {
        console.error("Button not found");
    }
});
