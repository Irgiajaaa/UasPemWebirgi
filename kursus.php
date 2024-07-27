<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $nama_kursus = $_POST['nama_kursus'];
        
        $sql = "INSERT INTO kursus (nama_kursus) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nama_kursus);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $id_kursus = $_POST['id_kursus'];
        $nama_kursus = $_POST['nama_kursus'];
        
        $sql = "UPDATE kursus SET nama_kursus = ? WHERE id_kursus = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nama_kursus, $id_kursus);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id_kursus = $_POST['id_kursus'];
        
        $sql = "DELETE FROM kursus WHERE id_kursus = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_kursus);
        $stmt->execute();
        
        // Debugging log
        if ($stmt->affected_rows > 0) {
            echo "Delete Berhasil";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Kursus</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Pendaftaran Kursus</h2>
        <div class="links">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="materi_kursus.php" class="link-button">Akses Materi Kursus</a>
				<a href="kursus.php" class="link-button">Daftar Kursus</a>
                <a href="penilaian.php" class="link-button">Penilaian</a>
                <a href="infoperkode.php" class="link-button">Informasi PerID Peserta</a>
                <a href="infopertgl.php" class="link-button">Informasi Pertanggal</a>
                <a href="infoperiode.php" class="link-button">Informasi Periode</a>
                <a href="infoperkodetgl.php" class="link-button">Informasi Perkode Tanggal</a>
				<a href="infopeserta.php" class="link-button">Informasi Peserta</a>
            <?php elseif ($_SESSION['role'] == 'peserta'): ?>
                <a href="peserta.php" class="link-button">Daftar Peserta</a>
                <a href="tugas.php" class="link-button">Kerjakan Tugas</a>
				<a href="infomateri.php" class="link-button">Informasi Materi Kursus</a>
                <a href="melihatnilai.php" class="link-button">Lihat Penilaian</a>
            <?php endif; ?>
        </div>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
    <form method="post">
        <fieldset>
            <input type="hidden" name="id_kursus" id="id_kursus">
            <label for="nama_kursus">Nama Kursus:</label>
            <input type="text" name="nama_kursus" id="nama_kursus" required>
            <button type="submit" name="create">Create</button>
            <button type="submit" name="update">Update</button>
        </fieldset>
    </form>
    <fieldset>
        <h3 align="center">Daftar Kursus</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Kursus</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM kursus");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id_kursus']}</td>
                    <td>{$row['nama_kursus']}</td>
                    <td>
                        <button class='edit' onclick=\"edit({$row['id_kursus']}, '{$row['nama_kursus']}')\">Edit</button>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='id_kursus' value='{$row['id_kursus']}'>
                            <button class='delete' type='submit' name='delete'>Delete</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </table>

        <script>
            function edit(id_kursus, nama_kursus) {
                document.getElementById('id_kursus').value = id_kursus;
                document.getElementById('nama_kursus').value = nama_kursus;
            }
        </script>
    </fieldset>
</body>
</html>
