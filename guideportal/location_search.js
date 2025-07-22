 let itineraryLocations = [];  // Store the itinerary locations
    let autocomplete;
    const suggestionsContainer = document.getElementById("locationSuggestions");

    // Initialize Google Places Autocomplete
    function initAutocomplete() {
        const input = document.getElementById("itineraryLocation");

        // Initialize autocomplete for place names only (not establishment or regions)
        autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'],
            fields: ['formatted_address', 'name'],
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (!place || !place.geometry) {
                console.log("Place details not available.");
                return;
            }
            // Add the selected place name to the itinerary list
            addLocationToItinerary(place.name || place.formatted_address);
        });
    }

    // Function to add a location to the itinerary list
    function addLocationToItinerary(location) {
        if (!itineraryLocations.includes(location)) {
            itineraryLocations.push(location);
            document.getElementById("itineraryLocations").value = JSON.stringify(itineraryLocations);

            // Create list item for the location
            const list = document.getElementById("itineraryList");
            const listItem = document.createElement("li");
            listItem.textContent = location;

            // Add remove button to the list item
            const removeButton = document.createElement("button");
            removeButton.textContent = "Remove";
            removeButton.style.marginLeft = "10px";
            removeButton.onclick = () => removeLocationFromItinerary(location, listItem);
            listItem.appendChild(removeButton);

            // Add the list item to the itinerary list
            list.appendChild(listItem);
        }

        // Clear input field and hide suggestions after adding
        document.getElementById("itineraryLocation").value = "";
        suggestionsContainer.style.display = "none";
    }

    // Function to remove a location from itinerary list
    function removeLocationFromItinerary(location, listItem) {
        itineraryLocations = itineraryLocations.filter(loc => loc !== location);
        document.getElementById("itineraryLocations").value = JSON.stringify(itineraryLocations);
        listItem.remove();
    }

    // Function to show search suggestions based on input
    function searchLocation() {
        const input = document.getElementById("itineraryLocation");
        const query = input.value;

        // Only show suggestions if query length > 2 characters
        if (query.length > 2) {
            const service = new google.maps.places.AutocompleteService();
            service.getPlacePredictions({ input: query, types: ['geocode'] }, function (predictions, status) {
                suggestionsContainer.innerHTML = "";  // Clear previous suggestions
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    predictions.forEach(function (prediction) {
                        const suggestion = document.createElement("div");
                        suggestion.textContent = prediction.description;
                        suggestion.style.padding = "5px";
                        suggestion.style.cursor = "pointer";
                        suggestion.onclick = () => {
                            addLocationToItinerary(prediction.description);
                        };
                        suggestionsContainer.appendChild(suggestion);
                    });
                    suggestionsContainer.style.display = "block";  // Show suggestions
                } else {
                    suggestionsContainer.style.display = "none";  // Hide if no suggestions
                }
            });
        } else {
            suggestionsContainer.style.display = "none";  // Hide if query length is too short
        }
    }

    // Initialize the Google Maps autocomplete when the script loads
    initAutocomplete();