document.addEventListener("DOMContentLoaded", function() {
 

    // Delete button functionality
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id'); // Get the product ID from the button's data attribute

            // Confirm deletion
            if (confirm(`Are you sure you want to delete product ID: ${productId}?`)) {
                // Send AJAX request to delete the product
                fetch(`delete_product.php?id=${productId}`, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the product tile from the DOM
                            this.closest('.product-tile').remove();
                            alert(data.message); // Show success message
                        } else {
                            alert(data.message); // Show error message
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the product.');
                    });
            }
        });
    });
});
