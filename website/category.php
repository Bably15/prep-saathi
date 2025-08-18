<?php
include '../Admin/db.php'; // adjust path if needed

// Get category ID from URL
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch category name
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$stmt->bind_result($category_name);
$stmt->fetch();
$stmt->close();

// Fetch all PDFs in this category
$stmt = $conn->prepare("SELECT id, title, regular_price, offer_price, thumbnail, pdf FROM ebooks WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo htmlspecialchars($category_name); ?> PDFs</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1><?php echo htmlspecialchars($category_name); ?> PDFs</h1>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
<?php while($row = $result->fetch_assoc()): ?>
    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:15px; text-align:center;">
        <img src="<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width:100%; height:auto; border-radius:6px;">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><del>₹<?php echo $row['regular_price']; ?></del> <strong>₹<?php echo $row['offer_price']; ?></strong></p>
        <a href="<?php echo htmlspecialchars($row['pdf']); ?>" target="_blank" style="display:inline-block; background:#28a745; color:#fff; padding:8px 12px; border-radius:4px; text-decoration:none;">View PDF</a>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
