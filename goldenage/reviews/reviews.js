async function fetchReviews() {
    try {
        const response = await fetch('fetch_reviews.php');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        const reviewsContainer = document.getElementById('reviews');
        reviewsContainer.innerHTML = '';

        if (data.length) {
            data.forEach(review => {
                const reviewElement = document.createElement('div');
                reviewElement.className = 'review';

                // Generate stars based on the rating
                const stars = Array.from({ length: 5 }, (_, index) => 
                    `<span class="star ${index < review.rating ? 'filled' : ''}"><i class="fas fa-star"></i></span>`
                ).join('');

                reviewElement.innerHTML = `
                    <h2>${review.name} (${review.rating}/5)</h2>
                    <div class="stars">${stars}</div>
                    <p>${review.review_text}</p>
                    <small>Travel Type: ${review.travel_type}, Date: ${review.travel_date}</small>
                `;
                reviewsContainer.appendChild(reviewElement);
            });
        } else {
            reviewsContainer.innerHTML = '<p>No reviews found.</p>';
        }
    } catch (error) {
        console.error('Error fetching reviews:', error);
    }
}

document.addEventListener('DOMContentLoaded', fetchReviews);
