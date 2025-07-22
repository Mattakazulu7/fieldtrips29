// List of top 100 cities (for demo purposes, we'll use a small set of cities)
const cities = [
      
    "New York", "Los Angeles", "Chicago", "Houston", "Phoenix",
    "Philadelphia", "San Antonio", "San Diego", "Dallas", "San Jose",
    "Austin", "Jacksonville", "Fort Worth", "Columbus", "San Francisco",
    "Charlotte", "Indianapolis", "Seattle", "Denver", "Washington",
    "Boston", "El Paso", "Detroit", "Nashville", "Portland", 
    "Memphis", "Oklahoma City", "Las Vegas", "Louisville", "Baltimore", 
    "Milwaukee", "Albuquerque", "Tucson", "Fresno", "Sacramento", 
    "Kansas City", "Long Beach", "Mesa", "Atlanta", "Colorado Springs", 
    "Raleigh", "Omaha", "Miami", "Cleveland", "Tulsa", 
    "Minneapolis", "Wichita", "New Orleans", "Arlington", "Bakersfield", 
    "Tampa", "Aurora", "Honolulu", "Anaheim", "Santa Ana", 
    "Corpus Christi", "Riverside", "Lexington", "St. Louis", "Stockton", 
    "Pittsburgh", "Cincinnati", "Anchorage", "Henderson", "Greensboro",
    "Plano", "Newark", "Lincoln", "Toledo", "Orlando", 
    "Chula Vista", "Jersey City", "Chandler", "Fort Wayne", "Buffalo", 
    "Durham", "St. Petersburg", "Laredo", "Madison", "Lubbock", 
    "Chesapeake", "Scottsdale", "Reno", "Glendale", "Norfolk", 
    "Gilbert", "Winston-Salem", "North Las Vegas", "Irving", "Chattanooga", 
    "Chesapeake", "Baton Rouge", "Spokane", "Des Moines", "Tacoma", 
    "San Bernardino", "Modesto", "Fontana", "Moreno Valley", "Santa Clarita", 
    "Fayetteville", "Birmingham", "Columbia", "Grand Rapids", "Aurora",
    "Amman", "Baghdad", "Barquisimeto", "Beirut", "Belgrade",
    "Berlin", "Birmingham", "Brisbane", "Bucharest", "Budapest", 
    "Buenos Aires", "Cairo", "Calcutta", "Caracas", "Chennai", 
    "Copenhagen", "Dakar", "Dallas", "Dhaka", "Dubai", 
    "Durban", "Giza", "Helsinki", "Hong Kong", "Istanbul", 
    "Jakarta", "Kiev", "Kolkata", "Lagos", "Lima", 
    "Lisbon", "London", "Los Angeles", "Madrid", "Manila", 
    "Mexico City", "Moscow", "Mumbai", "Nairobi", "New Delhi", 
    "New York", "Oslo", "Paris", "Prague", "Quito", 
    "Rio de Janeiro", "Rome", "San Francisco", "Santiago", "Sao Paulo", 
    "Seoul", "Shanghai", "Singapore", "Sydney", "Tehran", 
    "Tokyo", "Toronto", "Vancouver", "Warsaw", "Washington D.C.", 
    "Yerevan", "Zagreb", "Abu Dhabi", "Accra", "Addis Ababa", 
    "Alicante", "Almaty", "Albuquerque", "Alexandria", "Algiers", 
    "Amritsar", "Ankara", "Antwerp", "Arlington", "Astana",
    "Athens", "Atlanta", "Auckland", "Austin", "Baghdad",
    "Bangkok", "Barcelona", "Beijing", "Belgrade", "Belo Horizonte", 
    "Berlin", "Bogotá", "Boston", "Brasília", "Brisbane", 
    "Bucharest", "Buenos Aires", "Cairo", "Calcutta", "Cape Town", 
    "Caracas", "Casablanca", "Chengdu", "Chennai", "Chicago", 
    "Colombo", "Copenhagen", "Dhaka", "Dubai", "Durban", 
    "Düsseldorf", "Essen", "Geneva", "Guangzhou", "Hanoi", 
    "Helsinki", "Ho Chi Minh City", "Hong Kong", "Houston", "Istanbul", 
    "Jakarta", "Kiev", "Kinshasa", "Kolkata", "Lagos", 
    "Lima", "Lisbon", "London", "Lyon", "Madrid", 
    "Malaga", "Manila", "Melbourne", "Mexico City", "Miami", 
    "Milan", "Minneapolis", "Montevideo", "Montreal", "Mumbai", 
    "New Delhi", "New York", "Oslo", "Paris", "Phnom Penh", 
    "Port-au-Prince", "Prague", "Quito", "Rio de Janeiro", "Rome", 
    "San Francisco", "São Paulo", "Seoul", "Shanghai", "Singapore", 
    "Sydney", "Taipei", "Tehran", "Tokyo", "Toronto", 
    "Vancouver", "Warsaw", "Washington D.C.", "Zagreb", "Abu Dhabi", 
    "Accra", "Addis Ababa", "Almaty", "Amritsar", "Ankara", 
    "Antwerp", "Arlington", "Athens", "Atlanta", "Auckland", 
    "Baghdad", "Bangalore", "Bangkok", "Barcelona", "Beijing", 
    "Belgrade", "Berlin", "Bogotá", "Boston", "Brasília", 
    "Brisbane", "Brussels", "Buenos Aires", "Cairo", "Calcutta", 
    "Cape Town", "Caracas", "Casablanca", "Chengdu", "Chennai", 
    "Chicago", "Colombo", "Copenhagen", "Dallas", "Dhaka", 
    "Dubai", "Durban", "Düsseldorf", "Geneva", "Guangzhou", 
    "Hanoi", "Helsinki", "Ho Chi Minh City", "Hong Kong", "Houston", 
    "Istanbul", "Jakarta", "Kiev", "Kinshasa", "Kolkata", 
    "Lagos", "Lima", "Lisbon", "London", "Lyon", 
    "Madrid", "Malaga", "Manila", "Melbourne", "Mexico City", 
    "Miami", "Milan", "Minneapolis", "Montevideo", "Montreal", 
    "Mumbai", "New Delhi", "New York", "Oslo", "Paris", 
    "Phnom Penh", "Port-au-Prince", "Prague", "Quito", "Rio de Janeiro", 
    "Rome", "San Francisco", "São Paulo", "Seoul", "Shanghai", 
    "Singapore", "Sydney", "Taipei", "Tehran", "Tokyo", 
    "Toronto", "Vancouver", "Warsaw", "Washington D.C.", "Zagreb", 
    "Accra", "Almaty", "Amritsar", "Athens", "Auckland",
    "Amsterdam", "Alexandria", "Abu Dhabi", "Auckland", "Ankara",
    "Asunción", "Athens", "Atlanta", "Athens", "Auckland",
    "Bangkok", "Barcelon", "Berlin", "Berlin", "Brisbane", 
    "Copenhagen", "Cape Town", "Dubai", "Düsseldorf", "Delhi"
];

    // Add more cities here...


function autocompleteCity() {
    const input = document.getElementById('city');
    const suggestionsContainer = document.getElementById('citySuggestions');
    const query = input.value.toLowerCase();
    
    // Clear previous suggestions
    suggestionsContainer.innerHTML = '';
    
    if (query.length > 0) {
        const filteredCities = cities.filter(city => city.toLowerCase().includes(query));
        
        // Display suggestions
        if (filteredCities.length > 0) {
            suggestionsContainer.style.display = 'block';
            
            filteredCities.forEach(city => {
                const suggestionItem = document.createElement('div');
                suggestionItem.classList.add('suggestion');
                suggestionItem.textContent = city;
                suggestionItem.onclick = function() {
                    input.value = city; // Set city name when clicked
                    suggestionsContainer.style.display = 'none'; // Hide suggestions
                };
                suggestionsContainer.appendChild(suggestionItem);
            });
        } else {
            suggestionsContainer.style.display = 'none'; // Hide suggestions if no match
        }
    } else {
        suggestionsContainer.style.display = 'none'; // Hide suggestions when input is empty
    }
}
