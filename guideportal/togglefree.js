  // JavaScript function to toggle the price input field based on pricing selection
    function togglePriceInput(isPaid) {
        const priceInput = document.getElementById('price');
        const priceLabel = document.getElementById('priceLabel');
        if (isPaid) {
            priceInput.style.display = 'inline';
            priceLabel.style.display = 'inline';
            priceInput.disabled = false;
        } else {
            priceInput.style.display = 'none';
            priceLabel.style.display = 'none';
            priceInput.disabled = true;
            priceInput.value = ''; // Clear the price if switching back to Free
        }
    }