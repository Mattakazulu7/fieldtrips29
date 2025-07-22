document.addEventListener("DOMContentLoaded", function () {
    const showMoreButton = document.querySelector(".show-more-steps");
    const itineraryList = document.querySelector(".itinerary-list-wrapper ol");
    let stepsHidden = true;

    showMoreButton.addEventListener("click", function () {
        if (stepsHidden) {
            // Show the additional steps
            itineraryList.classList.add("expanded");
            showMoreButton.textContent = "Show less steps";
        } else {
            // Hide the additional steps
            itineraryList.classList.remove("expanded");
            showMoreButton.textContent = "Show 17 more steps";
        }
        stepsHidden = !stepsHidden;
    });
});
