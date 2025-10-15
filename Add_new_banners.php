<?php
require("db.php");


$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $targetDir = "banners/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($_FILES["banner_image"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $targetFile)) {
            $query = "INSERT INTO banners (image) VALUES ('$targetFile')";
            mysqli_query($con, $query);

            // ✅ REDIRECT after successful upload
            header("Location: uploads_banner.php");
            exit();
        } else {
            $msg = "<p class='error'>❌ Failed to upload image.</p>";
        }
    }
}

// Fetch banners for preview
$banners = mysqli_query($con, "SELECT * FROM banners ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Upload Banners</title>
  <link rel="stylesheet" href="Add_new_banners.css">
</head>
<body>
  <div class="admin-container">
    <h2>Upload New Banner</h2>
    <?php echo $msg; ?>
    <form method="POST" enctype="multipart/form-data">
      <label>Select Banner Image:</label>
      <input type="file" name="banner_image" required>

      <button type="submit">Upload Banner</button>
    </form>

    <div class="banner-preview">
      <?php while ($row = mysqli_fetch_assoc($banners)) { ?>
        <img src="<?php echo $row['image']; ?>" alt="Banner">
      <?php } ?>
    </div>
  </div>
</body>
</html>
