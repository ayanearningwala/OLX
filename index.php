<?php
include('db.php');

// Initialize search query
$searchQuery = "";

// Check if search is submitted
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// Fetch ads from the database based on search query
$sql = "SELECT * FROM ads WHERE title LIKE '%$searchQuery%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLX Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #ff6a00;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .cart-icon {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
        }
        .cart-icon span {
            background-color: red;
            padding: 5px 10px;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 14px;
            color: white;
        }
        .search-container {
            text-align: center;
            margin-top: 20px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 50%;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-container button {
            padding: 10px 20px;
            background-color: #ff6a00;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 10px;
            font-size: 16px;
        }
        .search-container button:hover {
            background-color: #ff5722;
        }
        .ads-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .ad-card {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .ad-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .ad-card h3 {
            margin: 10px 0;
        }
        .ad-card p {
            color: #555;
            font-size: 14px;
            margin: 10px 0;
        }
        .ad-card p strong {
            color: #ff6a00;
        }
        .ad-card button {
            background-color: #ff6a00;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .ad-card button:hover {
            background-color: #ff5722;
        }

        /* Cart Overlay */
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
            flex-direction: column;
        }
        .cart-content {
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            max-width: 400px;
            text-align: center;
        }
        .cart-content h3 {
            margin-bottom: 15px;
        }
        .cart-content ul {
            list-style-type: none;
            padding: 0;
        }
        .cart-content li {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .cart-content li button {
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .cart-content button {
            background-color: #ff6a00;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .cart-content button:hover {
            background-color: #ff5722;
        }

        /* Button Colors */
        .add-to-cart {
            background-color: green;
        }
        .cancel-cart {
            background-color: red;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>OLX Clone</h1>
    <!-- Cart Icon with Dynamic Count -->
    <span class="cart-icon" onclick="toggleCart()">🛒<span id="cart-count">0</span></span>
</div>

<!-- Search Form -->
<div class="search-container">
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search for items..." value="<?php echo $searchQuery; ?>">
        <button type="submit">Search</button>
    </form>
</div>

<!-- Cart Overlay -->
<div id="cart-overlay" class="cart-overlay">
    <div class="cart-content">
        <h3>Items in Cart</h3>
        <ul id="cart-items">
            <!-- Cart items will be dynamically added here -->
        </ul>
        <button onclick="closeCart()">Close</button>
    </div>
</div>

<div class="container">
    <div class="ads-container">
        <?php
        if ($result->num_rows > 0) {
            // Output ads based on the search query
            while($ad = $result->fetch_assoc()) {
                echo '
                <div class="ad-card">
                    <img src="'.$ad['image_path'].'" alt="Ad Image">
                    <h3>'.$ad['title'].'</h3>
                    <p>'.$ad['description'].'</p>
                    <p><strong>Price: ₹'.$ad['price'].'</strong></p>
                    <p><em>Category: '.$ad['category'].'</em></p>
                    <button class="add-to-cart" onclick="addToCart('.$ad['id'].', \''.$ad['title'].'\', '.$ad['price'].')">Add to Cart</button>
                </div>';
            }
        } else {
            echo "<p>No ads found.</p>";
        }
        ?>
    </div>
</div>

<script>
    // Store cart items in an array
    let cart = [];

    // Add item to the cart
    function addToCart(adId, title, price) {
        cart.push({ id: adId, title: title, price: price });
        updateCartCount();
        alert(title + " has been added to your cart!");
    }

    // Update the cart count in the emoji
    function updateCartCount() {
        const cartCount = document.getElementById('cart-count');
        cartCount.textContent = cart.length;  // Update the count
    }

    // Toggle the cart overlay visibility
    function toggleCart() {
        const cartOverlay = document.getElementById('cart-overlay');
        const cartItemsList = document.getElementById('cart-items');
        
        // Clear the cart list before adding new items
        cartItemsList.innerHTML = '';

        // Display the cart items
        cart.forEach(item => {
            const li = document.createElement('li');
            li.innerHTML = `
                ${item.title} - ₹${item.price} 
                <button class="add-to-cart" onclick="confirmAdd(${item.id})">Add</button>
                <button class="cancel-cart" onclick="removeFromCart(${item.id})">Cancel</button>
            `;
            cartItemsList.appendChild(li);
        });

        // Toggle the overlay display
        cartOverlay.style.display = cartOverlay.style.display === 'flex' ? 'none' : 'flex';
    }

    // Remove item from the cart
    function removeFromCart(itemId) {
        cart = cart.filter(item => item.id !== itemId);
        updateCartCount();
        toggleCart();
    }

    // Close the cart overlay
    function closeCart() {
        document.getElementById('cart-overlay').style.display = 'none';
    }

    // Confirm add item to cart (can add additional logic if needed)
    function confirmAdd(itemId) {
        alert("Item added to cart: " + itemId);
        closeCart();
    }
</script>

</body>
</html>
