function createProductCard(product) {
    const priceDisplay = Number(product.price) === 0
        ? "Free"
        : `$${Number(product.price).toFixed(2)}`;

    const type     = product.selection_type || "Unknown";
    const title    = product.triptitle      || "Untitled Trip";
    const tripPic  = product.product_picture || "/temp/photos/yellowumbrella.jpg";
    const userPic  = product.user_profile_picture || "/default-avatar.jpg";

    // ✅ Fix path if index.php is included
    const basePath = window.location.pathname.replace(/\/index\.php$/, '').replace(/\/$/, '');
    const profileUrl = `${window.location.origin}${basePath}/profile/?id=${product.profile_id}`;

    console.log(`Rendering profile_id=${product.profile_id}`);
    console.log(`Selection (Type): ${type}`);
    console.log(`Trip Title: ${title}`);

    return `
    <a href="${profileUrl}" style="text-decoration: none; color: inherit;">
      <div class="product">
        <div class="profile-picture">
          <img src="${userPic}" alt="User Profile Image">
        </div>
        <img src="${tripPic}" alt="${title}">
        <div class="details">
          <h2>${type}</h2>
          <p>${title}</p>
          <p>${product.product_desc}</p>
          <div class="time">${priceDisplay}</div>
          <div class="time">${product.product_start}</div>
        </div>
      </div>
    </a>`;
}


function loadProducts() {
    fetch('fetch_products.php')
        .then(r => r.json())
        .then(products => {
            console.log("Fetched Products:", products);

            const container = document.getElementById('product-container');
            container.innerHTML = "";

            products.forEach(p => {
                console.log(
                    `Profile ${p.profile_id}:`,
                    `Type="${p.selection_type}",`,
                    `Trip="${p.triptitle}"`
                );
                container.innerHTML += createProductCard(p);
            });
        })
        .catch(err => console.error('Error fetching products:', err));
}

document.addEventListener('DOMContentLoaded', loadProducts);
