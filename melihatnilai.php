<?php
include 'koneksi.php';

// Handle create, update, delete requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $id_tugas = $_POST['id_tugas'];
        $id_peserta = $_POST['id_peserta'];
        $nilai = $_POST['nilai'];

        $sql = "INSERT INTO penilaian (id_tugas, id_peserta, nilai) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $id_tugas, $id_peserta, $nilai);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $id_penilaian = $_POST['id_penilaian'];
        $id_tugas = $_POST['id_tugas'];
        $id_peserta = $_POST['id_peserta'];
        $nilai = $_POST['nilai'];

        $sql = "UPDATE penilaian SET id_tugas = ?, id_peserta = ?, nilai = ? WHERE id_penilaian = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $id_tugas, $id_peserta, $nilai, $id_penilaian);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id_penilaian = $_POST['id_penilaian'];

        $sql = "DELETE FROM penilaian WHERE id_penilaian = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_penilaian);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Penilaian</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
	<div class="container">
        <h2>Lihat Penilaian</h2>
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
    <table>
        <tr>
            <th>ID Penilaian</th>
            <th>Nama Tugas</th>
            <th>Nama Peserta</th>
            <th>Nilai</th>
        </tr>
        <?php
        $result_penilaian = $conn->query("SELECT * FROM penilaian");
        if ($result_penilaian) {
            while ($row_penilaian = $result_penilaian->fetch_assoc()) {
                // Ambil informasi tugas dari tabel tugas
                $stmt = $conn->prepare("SELECT * FROM tugas WHERE id_tugas = ?");
                $stmt->bind_param("i", $row_penilaian['id_tugas']);
                $stmt->execute();
                $result_tugas_info = $stmt->get_result();

                // Ambil informasi peserta dari tabel peserta
                $stmt = $conn->prepare("SELECT * FROM peserta WHERE id_peserta = ?");
                $stmt->bind_param("i", $row_penilaian['id_peserta']);
                $stmt->execute();
                $result_peserta_info = $stmt->get_result();

                if ($result_tugas_info && $result_peserta_info) {
                    if ($result_tugas_info->num_rows > 0 && $result_peserta_info->num_rows > 0) {
                        $row_tugas_info = $result_tugas_info->fetch_assoc();
                        $row_peserta_info = $result_peserta_info->fetch_assoc();
                        echo "<tr>
                            <td>{$row_penilaian['id_penilaian']}</td>
                            <td>{$row_tugas_info['nama_tugas']}</td>
                            <td>{$row_peserta_info['nama_peserta']}</td>
                            <td>{$row_penilaian['nilai']}</td>
                        </tr>";
                    } else {
                        echo "<tr>
                            <td>{$row_penilaian['id_penilaian']}</td>
                            <td colspan='3'>Failed to fetch tugas or peserta info.</td>
                        </tr>";
                    }
                } else {
                    echo "<tr>
                        <td>{$row_penilaian['id_penilaian']}</td>
                        <td colspan='3'>Failed to execute tugas or peserta query.</td>
                    </tr>";
                }
            }
        } else {
            echo "<tr><td colspan='4'>Failed to fetch penilaian info.</td></tr>";
        }

        // Close connection
        $conn->close();
        ?>
    </table>
</body>
</html>