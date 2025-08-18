<?php
include 'auth_check.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['banner_name']);

    if (!empty($_FILES['banner_image']['name'])) {
        $targetDir = "uploads/banners/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['banner_image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO banners (name, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $targetFile);
            $stmt->execute();
            header("Location: banner.php?success=1");
            exit;
        } else {
            echo "Error uploading file.";
        }
    }
}
