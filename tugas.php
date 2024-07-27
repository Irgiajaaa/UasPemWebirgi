<?php
include 'koneksi.php';

// Handle create, update, delete requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $id_materi = $_POST['id_materi'];
        $nama_tugas = $_POST['nama_tugas'];
        $deskripsi_tugas = $_POST['deskripsi_tugas'];
        
        $sql = "INSERT INTO tugas (id_materi, nama_tugas, deskripsi_tugas) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $id_materi, $nama_tugas, $deskripsi_tugas);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $id_tugas = $_POST['id_tugas'];
        $id_materi = $_POST['id_materi'];
        $nama_tugas = $_POST['nama_tugas'];
        $deskripsi_tugas = $_POST['deskripsi_tugas'];
        
        $sql = "UPDATE tugas SET id_materi = ?, nama_tugas = ?, deskripsi_tugas = ? WHERE id_tugas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $id_materi, $nama_tugas, $deskripsi_tugas, $id_tugas);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id_tugas = $_POST['id_tugas'];
        
        $sql = "DELETE FROM tugas WHERE id_tugas = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_tugas);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Tugas</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
   <div class="container">
        <h2>Kerjakan Tugas</h2>
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
        <input type="hidden" name="id_tugas" id="id_tugas">
        Materi 
        <select name="id_materi" id="id_materi" required>
            <?php
            // Fetch data dari tabel materi_kursus untuk dropdown
            $result_materi = $conn->query("SELECT * FROM materi_kursus");
            while ($row_materi = $result_materi->fetch_assoc()) {
                echo "<option value='{$row_materi['id_materi']}'>{$row_materi['judul_materi']}</option>";
            }
            ?>
        </select><br>
        Nama Tugas <input type="text" name="nama_tugas" id="nama_tugas" required><br>
        Deskripsi Tugas <textarea name="deskripsi_tugas" id="deskripsi_tugas" required></textarea><br>
        <button type="submit" name="create">Create</button>
        <button type="submit" name="update">Update</button>
	</fieldset>
    </form>

    <h3 align="center">Daftar Tugas</h3>
    <table>
        <tr>
            <th>ID Tugas</th>
            <th>Judul Materi</th>
            <th>Nama Tugas</th>
            <th>Deskripsi Tugas</th>
            <th>Action</th>
        </tr>
        <?php
        $result_tugas = $conn->query("SELECT * FROM tugas");
        if ($result_tugas) {
            while ($row_tugas = $result_tugas->fetch_assoc()) {
                // Ambil informasi materi dari tabel materi_kursus
                $stmt = $conn->prepare("SELECT * FROM materi_kursus WHERE id_materi = ?");
                $stmt->bind_param("i", $row_tugas['id_materi']);
                $stmt->execute();
                $result_materi_info = $stmt->get_result();
                if ($result_materi_info) {
                    if ($result_materi_info->num_rows > 0) {
                        $row_materi_info = $result_materi_info->fetch_assoc();
                        echo "<tr>
                            <td>{$row_tugas['id_tugas']}</td>
                            <td>{$row_materi_info['judul_materi']}</td>
                            <td>{$row_tugas['nama_tugas']}</td>
                            <td>{$row_tugas['deskripsi_tugas']}</td>
                            <td>
                                <button class='edit' onclick=\"edit({$row_tugas['id_tugas']}, {$row_tugas['id_materi']}, '{$row_tugas['nama_tugas']}', '{$row_tugas['deskripsi_tugas']}')\">Edit</button>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id_tugas' value='{$row_tugas['id_tugas']}'>
                                    <button class='delete' type='submit' name='delete'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    } else {
                        echo "<tr>
                            <td>{$row_tugas['id_tugas']}</td>
                            <td colspan='3'>Failed to fetch materi info.</td>
                            <td>
                                <button class='edit' onclick=\"edit({$row_tugas['id_tugas']}, {$row_tugas['id_materi']}, '{$row_tugas['nama_tugas']}', '{$row_tugas['deskripsi_tugas']}')\">Edit</button>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id_tugas' value='{$row_tugas['id_tugas']}'>
                                    <button class='delete' type='submit' name='delete'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr>
                        <td>{$row_tugas['id_tugas']}</td>
                        <td colspan='3'>Failed to execute materi query.</td>
                        <td>
                            <button class='edit' onclick=\"edit({$row_tugas['id_tugas']}, {$row_tugas['id_materi']}, '{$row_tugas['nama_tugas']}', '{$row_tugas['deskripsi_tugas']}')\">Edit</button>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id_tugas' value='{$row_tugas['id_tugas']}'>
                                <button class='delete' type='submit' name='delete'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            }
        } else {
            echo "Failed to fetch tugas info.";
        }

        // Close connection
        $conn->close();
        ?>
    </table>

    <script>
        function edit(id_tugas, id_materi, nama_tugas, deskripsi_tugas) {
            document.getElementById('id_tugas').value = id_tugas;
            document.getElementById('id_materi').value = id_materi;
            document.getElementById('nama_tugas').value = nama_tugas;
            document.getElementById('deskripsi_tugas').value = deskripsi_tugas;
        }
    </script>
</body>
</html>