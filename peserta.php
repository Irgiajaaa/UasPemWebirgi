<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $nama_peserta = $_POST['nama_peserta'];
        $email = $_POST['email'];
        $no_telp = $_POST['no_telp'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $tanggal_daftar = $_POST['tanggal_daftar'];
        $kursus_id = $_POST['kursus_id'];
        
        $sql = "INSERT INTO peserta (nama_peserta, email, no_telp, tanggal_lahir, tanggal_daftar, kursus_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama_peserta, $email, $no_telp, $tanggal_lahir, $tanggal_daftar, $kursus_id);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $id_peserta = $_POST['id_peserta'];
        $nama_peserta = $_POST['nama_peserta'];
        $email = $_POST['email'];
        $no_telp = $_POST['no_telp'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $tanggal_daftar = $_POST['tanggal_daftar'];
        $kursus_id = $_POST['kursus_id'];
        
        $sql = "UPDATE peserta SET nama_peserta = ?, email = ?, no_telp = ?, tanggal_lahir = ?, tanggal_daftar = ?, kursus_id = ? WHERE id_peserta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nama_peserta, $email, $no_telp, $tanggal_lahir, $tanggal_daftar, $kursus_id, $id_peserta);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id_peserta = $_POST['id_peserta'];
        
        $sql = "DELETE FROM peserta WHERE id_peserta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_peserta);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Peserta</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Pendaftaran Peserta</h2>
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
    
    <div class="container">
        <form method="post">
            <fieldset>
                <input type="hidden" name="id_peserta" id="id_peserta">
                Nama Peserta: <input type="text" name="nama_peserta" id="nama_peserta" required><br>
                Email: <input type="email" name="email" id="email" required><br>
                No Telpon: <input type="text" name="no_telp" id="no_telp" required><br>
                Tanggal Lahir: <input type="date" name="tanggal_lahir" id="tanggal_lahir" required><br>
                Tanggal Daftar: <input type="date" name="tanggal_daftar" id="tanggal_daftar" required><br>
                Kursus: 
                <select name="kursus_id" id="kursus_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM kursus");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id_kursus']}'>{$row['nama_kursus']}</option>";
                    }
                    ?>
                </select><br>
                <button type="submit" name="create">Create</button>
                <button type="submit" name="update">Update</button>
            </fieldset>
        </form>
    </div>
</body>
</html>
