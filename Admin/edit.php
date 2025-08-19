<?php
include 'auth_check.php';
include 'db.php';

$categories = [];
$res = $conn->query("SELECT id, name FROM categories ORDER BY id");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $categories[$row['id']] = $row['name'];
    }
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM ebooks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ebook = $result->fetch_assoc();

if (!$ebook) {
    die("PDF not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $regular_price = $_POST['regular_price'];
    $offer_price = $_POST['offer_price'];
    $category_id = (int)$_POST['category_id'];

    $thumbPath = $ebook['thumbnail'];
    $pdfPath = $ebook['pdf'];

    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbName = time() . "_" . basename($_FILES['thumbnail']['name']);
        $thumbPath = "uploads/" . $thumbName;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbPath);
    }

    if (!empty($_FILES['pdf']['name'])) {
        $pdfName = time() . "_" . basename($_FILES['pdf']['name']);
        $pdfPath = "uploads/" . $pdfName;
        move_uploaded_file($_FILES['pdf']['tmp_name'], $pdfPath);
    }

    $stmt = $conn->prepare("UPDATE ebooks SET title=?, regular_price=?, offer_price=?, category_id=?, thumbnail=?, pdf=? WHERE id=?");
    $stmt->bind_param("sddissi", $title, $regular_price, $offer_price, $category_id, $thumbPath, $pdfPath, $id);

    if ($stmt->execute()) {
        header("Location: index.php?updated=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<?php include 'partials/header.php'; ?>
<div class="container">

    <div class="content">
        <div class="edit-container card">
            <h1>Edit PDF Details</h1>
            <form action="" method="POST" enctype="multipart/form-data" class="form-ui">

                <div class="form-group">
                    <label>Title</label>
                    <input class="readonly-input" type="text" name="title" value="<?= htmlspecialchars($ebook['title']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Regular Price</label>
                    <input type="number" step="0.01" name="regular_price" value="<?= htmlspecialchars($ebook['regular_price']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Offer Price</label>
                    <input type="number" step="0.01" name="offer_price" value="<?= htmlspecialchars($ebook['offer_price']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($categories as $key => $value): ?>
                            <option value="<?= $key ?>" <?= ($ebook['category_id'] == $key) ? "selected" : "" ?>>
                                <?= htmlspecialchars($value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Thumbnail (optional)</label>
                    <input type="file" name="thumbnail" accept="image/*">
                    <?php if (!empty($ebook['thumbnail'])): ?>
                        <div class="preview">
                            <img src="<?= $ebook['thumbnail']; ?>" alt="thumbnail">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>PDF File (optional)</label>
                    <input type="file" name="pdf" accept="application/pdf">
                    <?php if (!empty($ebook['pdf'])): ?>
                        <div class="preview">
                            <a href="<?= $ebook['pdf']; ?>" target="_blank">📄 View Current PDF</a>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-primary">Update PDF</button>
            </form>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>

<style>
    .edit-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .edit-container h1 {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
        color: #333;
    }

    .form-ui .form-group {
        margin-bottom: 18px;
    }

    .form-ui label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
        color: #444;
    }

    .form-ui input,
    .form-ui select {
        width: 100%;
        padding: 10px 12px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        transition: border 0.2s;
    }

    .form-ui input:focus,
    .form-ui select:focus {
        border-color: #007bff;
    }

    .preview {
        margin-top: 10px;
    }

    .preview img {
        max-width: 160px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .preview a {
        display: inline-block;
        margin-top: 5px;
        color: #007bff;
        font-weight: 500;
        text-decoration: none;
    }

    .preview a:hover {
        text-decoration: underline;
    }

    .btn-primary {
        background: #007bff;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #0056b3;
    }
</style>