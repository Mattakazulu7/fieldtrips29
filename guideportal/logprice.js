document.getElementById('createTourForm').addEventListener('submit', function(event) {
    // Prevent the form from submitting immediately
    event.preventDefault();

    // Get the value of the price input
    const price = document.getElementById('price').value;
    const isPaid = document.getElementById('paid').checked;

    // Display a confirmation dialog showing the price
    const message = isPaid ? `The price is set to $${price}. Do you want to proceed?` : "This tour is Free. Do you want to proceed?";
    if (confirm(message)) {
        // If the user clicks "OK", proceed with the form submission
        this.submit();
    }
});