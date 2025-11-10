<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah Karyawan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Tambah Karyawan</h2>

  <?php if ($pesan): ?>
    <div class="alert"><?php echo htmlspecialchars($pesan); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Nama*:</label>
    <input type="text" name="nama" required>

    <label>Jabatan*:</label>
    <input type="text" name="jabatan" required>

    <label>Email*:</label>
    <input type="email" name="email" required>

    <label>Gaji:</label>
    <input type="number" step="0.01" name="gaji">

    <label>Foto:</label>
    <input type="file" name="foto" accept="image/*">

    <button type="submit" name="submit">💾 Simpan Data</button>
  </form>

  <p style="text-align:center;"><a href="index.php">⬅ Kembali ke Daftar</a></p>
</body>
</html>
