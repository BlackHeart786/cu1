// Data structure for product cards (used for demo rendering)
const latestPhonesData = [
    { title: "Apple iPhone 16", variant: "128 GB", oldPrice: "Rs.350,00.00", newPrice: "Rs.329,00.00", image: "images/Apple-iPhone-17.jpg" },
    { title: "Apple iPhone 16 Pro", variant: "128 GB", oldPrice: "Rs.356,00.00", newPrice: "Rs.329,00.00", image: "images/Apple-iPhone-17.jpg" },
    { title: "Samsung Galaxy S24 Ultra", variant: "256 GB", oldPrice: "Rs.350,00.00", newPrice: "Rs.329,00.00", image: "images/Apple-iPhone-17.jpg" },
    { title: "Google Pixel 9 Pro XL", variant: "256 GB", oldPrice: "Rs.350,00.00", newPrice: "Rs.329,00.00", image: "images/Apple-iPhone-17.jpg" },
];

const accessoriesData = [
    { title: "Apple Pencil Pro 2024", variant: "", newPrice: "Rs.54,900.00", image: "images/accessory-pencil.jpg" },
    { title: "Samsung Starter Pack", variant: "Galaxy Z Fold 5", newPrice: "Rs.34,900.00", image: "images/accessory-samsung.jpg" },
    { title: "Spigen iPhone 14 Pro Max Case", variant: "Ultra Hybrid", newPrice: "Rs.17,900.00", image: "images/accessory-case.jpg" },
    { title: "Baseus GaN5 20W", variant: "Type-C Fast Charger", newPrice: "Rs.5,000.00", image: "images/accessory-charger.jpg" },
];

/**
 * Function to generate and append product cards to a container
 */
function renderProducts(containerId, products) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Use .map() to convert the array of product objects into an array of HTML strings
    container.innerHTML = products.map(product => `
        <div class="product-card">
            <img src="${product.image}" alt="${product.title}" class="product-image">
            <p class="product-title">${product.title}</p>
            ${product.variant ? `<p class="product-variant">${product.variant}</p>` : ''}
            <div class="price-box">
                ${product.oldPrice ? `<span class="original-price">${product.oldPrice}</span>` : ''}
                <span class="discounted-price">${product.newPrice}</span>
            </div>
        </div>
    `).join(''); // .join('') concatenates the array of strings into one single HTML string
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. Render Dummy Products (Repeated)
    
    // Repeat latestPhonesData 4 times for a full-page look
    const repeatedPhones = latestPhonesData.concat(latestPhonesData, latestPhonesData, latestPhonesData);
    renderProducts('latest-phones-grid', repeatedPhones); 

    // Repeat accessoriesData 2 times
    const repeatedAccessories = accessoriesData.concat(accessoriesData);
    renderProducts('accessories-grid', repeatedAccessories);

    // 2. Mobile Menu Toggle
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }
});