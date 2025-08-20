<?php
include 'db.php';
include 'partials/header.php';

// Get category id from URL
$categoryId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// If invalid, redirect or show error
if ($categoryId <= 0) {
    echo "<p>Invalid category.</p>";
    exit;
}

// Fetch category name
$catStmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$catStmt->bind_param("i", $categoryId);
$catStmt->execute();
$catResult = $catStmt->get_result();
$category = $catResult->fetch_assoc();

if (!$category) {
    echo "<p>Category not found.</p>";
    exit;
}

// Fetch ebooks only in this category
$stmt = $conn->prepare("
    SELECT e.id, e.title, e.regular_price, e.offer_price, e.thumbnail, e.pdf, c.name AS category
    FROM ebooks e
    LEFT JOIN categories c ON e.category_id = c.id
    WHERE e.category_id = ?
    ORDER BY e.id DESC
");
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="category-section">
    <h1>Ebooks - <?= htmlspecialchars($category['name']); ?></h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Description</th>
                <th>Original Price</th>
                <th>Offered Price</th>
                <!-- <th>Category</th> -->
                <th>Preview</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']); ?></td>
                    <td>₹<?= htmlspecialchars($row['regular_price']); ?></td>
                    <td>₹<?= htmlspecialchars($row['offer_price']); ?></td>
                    <!-- <td><?= htmlspecialchars($row['category']); ?></td> -->
                    <td>
                        <?php if ($row['thumbnail']): ?>
                            <img src="<?= htmlspecialchars($row['thumbnail']); ?>" width="80">
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['pdf']): ?>
                            <a href="<?= htmlspecialchars($row['pdf']); ?>" target="_blank"> Buy Now</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<?php include 'partials/footer.php'; ?>