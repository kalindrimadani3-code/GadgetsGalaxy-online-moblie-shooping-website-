<?php
include 'db.php';
$message = "";

// Upload banner
if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === 0) {
    $imageName = time() . "_" . basename($_FILES['banner_image']['name']);
    $uploadFolder = __DIR__ . '/banners/';
    $uploadPath = $uploadFolder . $imageName;

    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0755, true);
    }

    if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $uploadPath)) {
        $query = "INSERT INTO banners (image) VALUES ('$imageName')";
        if (mysqli_query($con, $query)) {
            $message = "<p class='success'>✅ Banner uploaded successfully!</p>";
        } else {
            unlink($uploadPath);
            $message = "<p class='error'>❌ Database error: " . mysqli_error($con) . "</p>";
        }
    } else {
        $message = "<p class='error'>❌ Failed to upload image.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Banner</title>
    <link rel="stylesheet" href="uploads_banner.css">
</head>
<body>
    <div class="container">
        <h2>Upload Banner</h2>
        <?= $message ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Select Banner Image:</label><br>
            <input type="file" name="banner_image" accept="image/*" required><br><br>
            <button type="submit">Upload</button>
        </form>
        <p><a href="banner_view.php"> View All Banners</a></p>
       <p><a href="dashboard.php"> Go Back to dashboard</a></p>
    </div>
</body>
</html>
