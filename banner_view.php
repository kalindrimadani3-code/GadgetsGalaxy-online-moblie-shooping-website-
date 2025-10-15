<?php
include 'db.php';
$message = "";

// Delete banner
if (isset($_POST['delete']) && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    $getImageQuery = "SELECT image FROM banners WHERE image = $delete_id";
    $res = mysqli_query($con, $getImageQuery);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $imageName = $row['image'];
        $filePath = __DIR__ . '/banners/' . $imageName;

        if (file_exists($filePath)) unlink($filePath);

        $deleteQuery = "DELETE FROM banners WHERE image = $delete_id";
        if (mysqli_query($con, $deleteQuery)) {
            $message = "<p class='success'>✅ Banner deleted successfully.</p>";
        } else {
            $message = "<p class='error'>❌ Failed to delete from database.</p>";
        }
    } else {
        $message = "<p class='error'>❌ Banner not found.</p>";
    }
}

$result = mysqli_query($con, "SELECT * FROM banners ORDER BY image DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Banners</title>
    <link rel="stylesheet" href="banner_view.css">
</head>
<body>
    <div class="container">
        <h2>All Banners</h2>
        <?= $message ?>
        <table>
            <tr>
                
                <th>Filename</th>
                <th>Preview</th>
                <th>Action</th>
            </tr>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                      
                        <td><?= htmlspecialchars($row['image']) ?></td>
                        <td><img src="banners/<?= htmlspecialchars($row['image']) ?>" alt="banner"></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Delete this banner?');">
                                <input type="hidden" name="delete_id" value="<?= $row['image'] ?>">
                                <input type="submit" name="delete" value="Delete" class="delete-btn">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No banners found.</td></tr>
            <?php endif; ?>
        </table>
        <p><a href="uploads_banner.php" > Go Back to Upload</a></p>

    </div>
</body>
</html>
