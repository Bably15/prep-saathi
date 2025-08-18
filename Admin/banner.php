<!-- <?php include 'auth_check.php'; ?> -->
<?php include 'db.php'; ?>
<?php include 'partials/header.php'; ?>
<!-- <?php include 'partials/sidebar.php'; ?> -->

<div class="main-body">
    <h1>Manage Banners</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div id="successMsg" style="
      background: #d1fae5;
      color: #065f46;
      padding: 10px 15px;
      border: 1px solid #10b981;
      border-radius: 6px;
      margin-bottom: 15px;
      font-weight: bold;
      text-align: center;">
            ✅ Banner uploaded successfully!
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById("successMsg");
                if (msg) msg.style.display = "none";
            }, 3000);
        </script>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Banner Preview</th>
                <th>Banner Name</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <!-- Upload Row -->
            <tr>
                <form action="upload_banner.php" method="POST" enctype="multipart/form-data">
                    <td>New</td>
                    <td>
                        <label class="upload-box">
                            <input type="file" name="banner_image" accept="image/*" required onchange="previewBanner(event)">
                            <span>📷 Upload Banner</span>
                        </label>
                        <div id="bannerPreview" class="preview-box"></div>
                    </td>
                    <td><input type="text" name="banner_name" required placeholder="Banner Name"></td>
                    <td><button type="submit">Add Banner</button></td>
                </form>
            </tr>

            <!-- Existing Banners -->
            <?php
            $stmt = $conn->query("SELECT id, name, image FROM banners ORDER BY id DESC");
            while ($row = $stmt->fetch_assoc()):
            ?>
                <tr>
                    <td><?= (int)$row['id']; ?></td>
                    <td>
                        <?php if ($row['image']): ?>
                            <img src="<?= htmlspecialchars($row['image']); ?>" alt="Banner Preview">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><a href="edit_banner.php?id=<?= (int)$row['id']; ?>">✏️ Edit</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function previewBanner(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById("bannerPreview").innerHTML =
                    `<img src="${e.target.result}" style="max-width:120px; max-height:80px; border:1px solid #ccc; border-radius:4px;">`;
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById("bannerPreview").innerHTML = "";
        }
    }
</script>

<?php include 'partials/footer.php'; ?>