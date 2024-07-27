<?php
include 'koneksi.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Materi Kursus</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Materi Kursus</h2>
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
    <h3 align="center">Daftar Materi Kursus</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama Kursus</th>
            <th>Judul Materi</th>
        </tr>
        <?php
        // Fetch data materi_kursus dengan informasi kursus terkait
        $result_materi = $conn->query("SELECT mk.id_materi, k.nama_kursus, mk.judul_materi FROM materi_kursus mk INNER JOIN kursus k ON mk.id_kursus = k.id_kursus");
        
        if ($result_materi->num_rows > 0) {
            while ($row_materi = $result_materi->fetch_assoc()) {
                echo "<tr>
                    <td>{$row_materi['id_materi']}</td>
                    <td>{$row_materi['nama_kursus']}</td>
                    <td>{$row_materi['judul_materi']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data materi kursus</td></tr>";
        }
        ?>
    </table>
</body>
</html>
