 
        // Function to toggle views
        function showView(viewId) {
            // Hide all views
            const views = document.querySelectorAll('.view');
            views.forEach(view => {
                view.style.display = 'none';
            });

            // Show the selected view
            const selectedView = document.getElementById(viewId);
            if (selectedView) {
                selectedView.style.display = 'block';
            }
        }

        // Event listeners for footer items
        document.querySelectorAll('.footer-item').forEach(item => {
            item.addEventListener('click', function() {
                 
                const viewId = this.querySelector('span').textContent.toLowerCase(); // Get the view ID from the span text
                console.log(`Footer item clicked: ${viewId}`); // Log the clicked view ID
           
                showView(viewId); // Call the showView function
            });
        });
    