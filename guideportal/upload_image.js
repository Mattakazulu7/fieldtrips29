document.getElementById('tourImage').addEventListener('change', function(event) {
    const formData = new FormData();
    formData.append('tourImage', event.target.files[0]);

    fetch('creatour/upload_image.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Set the file path to the hidden input for `create_tour.php`
            document.getElementById('productPicturePath').value = data.filePath;
            alert('Image uploaded successfully!');
        } else {
            alert('Image upload failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Image upload failed.');
    });
});
