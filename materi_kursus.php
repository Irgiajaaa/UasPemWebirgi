<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $nama_kursus = $_POST['nama_kursus'];
        $judul_materi = $_POST['judul_materi'];
        
        $stmt = $conn->prepare("SELECT id_kursus FROM kursus WHERE nama_kursus=?");
        $stmt->bind_param("s", $nama_kursus);
        $stmt->execute();
        $result_kursus_id = $stmt->get_result();
        
        if ($result_kursus_id->num_rows > 0) {
            $row_kursus_id = $result_kursus_id->fetch_assoc();
            $id_kursus = $row_kursus_id['id_kursus'];

            $sql = "INSERT INTO materi_kursus (id_kursus, judul_materi) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $id_kursus, $judul_materi);
            $stmt->execute();
        } else {
            echo "Gagal Input Data Materi Kursus";
        }
    } elseif (isset($_POST['update'])) {
        $id_materi = $_POST['id_materi'];
        $nama_kursus = $_POST['nama_kursus'];
        $judul_materi = $_POST['judul_materi'];

        // Ambil id_kursus berdasarkan nama_kursus
        $stmt = $conn->prepare("SELECT id_kursus FROM kursus WHERE nama_kursus=?");
        $stmt->bind_param("s", $nama_kursus);
        $stmt->execute();
        $result_kursus_id = $stmt->get_result();
        
        if ($result_kursus_id->num_rows > 0) {
            $row_kursus_id = $result_kursus_id->fetch_assoc();
            $id_kursus = $row_kursus_id['id_kursus'];

            // Update data materi_kursus
            $sql = "UPDATE materi_kursus SET id_kursus=?, judul_materi=? WHERE id_materi=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $id_kursus, $judul_materi, $id_materi);
            $stmt->execute();
        } else {
            echo "Gagal Isi Data Materi Kursus";
        }
    } elseif (isset($_POST['delete'])) {
        $id_materi = $_POST['id_materi'];

        // Delete data materi_kursus
        $sql = "DELETE FROM materi_kursus WHERE id_materi=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_materi);
        $stmt->execute();
        
        // Debugging log
        if ($stmt->affected_rows > 0) {
            echo "Berhasil Delete Data Materi Kursus";
        } else {
            echo "Gagal Delete: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembuatan Materi Kursus</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Membuat Materi Kursus</h2>
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
        <input type="hidden" name="id_materi" id="id_materi">
        Nama Kursus 
        <select name="nama_kursus" id="nama_kursus" required>
            <?php
            // Fetch data dari tabel kursus untuk dropdown
            $result_kursus = $conn->query("SELECT * FROM kursus");
            if ($result_kursus->num_rows > 0) {
                while ($row_kursus = $result_kursus->fetch_assoc()) {
                    echo "<option value='{$row_kursus['nama_kursus']}'>{$row_kursus['nama_kursus']}</option>";
                }
            } else {
                echo "<option value=''>Tidak ada kursus</option>";
            }
            ?>
        </select><br>
        Judul Materi <input type="text" name="judul_materi" id="judul_materi" required><br>
        <button type="submit" name="create">Create</button>
        <button type="submit" name="update">Update</button>
		</fieldset>
    </form>
</body>
</html>
