<?php
include('db.php');

// Handle the form submission and image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    // Image upload handling
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_path = $target_file;
    }

    // Insert ad details into the database
    $sql = "INSERT INTO ads (user_id, title, description, price, image_path, category) 
            VALUES (1, '$title', '$description', '$price', '$image_path', '$category')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Ad posted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post an Ad</title>
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
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input[type="file"] {
            padding: 10px;
            font-size: 14px;
            color: #555;
        }
        .image-preview {
            margin-top: 20px;
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            display: none;
            transition: all 0.3s ease;
        }
        .submit-btn {
            background-color: #ff6a00;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #ff5722;
            transition: background-color 0.3s ease;
        }
        .form-group input[type="text"], .form-group textarea {
            transition: all 0.3s ease;
        }
        .form-group input[type="text"]:focus, .form-group textarea:focus {
            border-color: #ff6a00;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Post Your Ad</h1>
</div>

<div class="container">
    <form action="post_ad.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Ad Title</label>
            <input type="text" name="title" id="title" required>
        </div>
        
        <div class="form-group">
            <label for="description">Ad Description</label>
            <textarea name="description" id="description" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (₹)</label>
            <input type="number" name="price" id="price" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" required>
                <option value="Electronics">Electronics</option>
                <option value="Vehicles">Vehicles</option>
                <option value="Furniture">Furniture</option>
                <option value="Fashion">Fashion</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="form-group">
            <img id="image-preview" class="image-preview" src="#" alt="Image Preview">
        </div>

        <div class="form-group">
            <button type="submit" class="submit-btn">Post Ad</button>
        </div>
    </form>
</div>

<script>
    // Image Preview function
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            const preview = document.getElementById('image-preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>
