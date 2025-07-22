document.getElementById('reviewForm').addEventListener('submit', function (event) {
    const name = document.getElementById('name').value.trim();
    const review = document.getElementById('review_text').value.trim();

    if (name === '' || review === '') {
        event.preventDefault();
        alert('Please fill in all required fields.');
    }
});
