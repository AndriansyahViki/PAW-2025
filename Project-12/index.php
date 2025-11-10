<?php
include 'db.php';


$limit = 5; ### jumlah data per halaman ###
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page - 1) * $limit : 0;

 ### Filter Pencarian ###
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

 ### Query Data ###
$where = '';
if ($search != '') {
  $search_escaped = mysqli_real_escape_string($conn, $search);
  $where = "WHERE nama LIKE '%$search_escaped%' OR jabatan LIKE '%$search_escaped%' OR email LIKE '%$search_escaped%'";
}

$sql = "SELECT * FROM karyawan $where LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

 ### menghitung Total Data  ###
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM karyawan $where");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Daftar Karyawan</title>
</head>
<body>

  <a href="add.php" class="btn-add">➕ Tambah Karyawan</a>
<link rel="stylesheet"  href="style.css">
  <div class="search-box">

    <form method="get" action="">
      <input type="text" name="search" placeholder="Cari nama / jabatan / email..." value="<?php echo htmlspecialchars($search); ?>">
      <!-- <button type="submit">Cari</button> -->
    </form>
  </div>

      <h2>Daftar Karyawan</h2>
  <table>
    <tr>
      <th>No</th>
      <th>Foto</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th>Email</th>
      <th>Gaji</th>
      <th>Aksi</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
      $no = $start + 1;
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td><img src='uploads/" . htmlspecialchars($row['foto']) . "' width='60'></td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['gaji']) . "</td>";
        echo "<td>
                <a href='edit.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a> |
                <a href='delete.php?id=" . $row['id'] . "' class='btn-del' onclick=\"return confirm('Yakin hapus?');\">Hapus</a>
              </td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='7'>Belum ada data</td></tr>";
    }
    ?>
  </table>

  <!-- Pagination -->
  <div class="pagination" style="margin-top:15px;">
    <?php
    if ($totalPage > 1) {
      for ($i = 1; $i <= $totalPage; $i++) {
        $active = ($i == $page) ? 'active' : '';
        $link = "?page=$i";
        if ($search != '') $link .= "&search=" . urlencode($search);
        echo "<a class='$active' href='$link'>$i</a>";
      }
    }
    ?>
  </div>
</body>
</html>
