<?php
include 'koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Peserta</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Peserta</h2>
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
    
    <fieldset>
        <h3 align="center">Daftar Peserta</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>No Telpon</th>
                <th>Tanggal Lahir</th>
                <th>Tanggal Daftar</th>
                <th>Kursus</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT peserta.*, kursus.nama_kursus FROM peserta JOIN kursus ON peserta.kursus_id = kursus.id_kursus");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id_peserta']}</td>
                    <td>{$row['nama_peserta']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['no_telp']}</td>
                    <td>{$row['tanggal_lahir']}</td>
                    <td>{$row['tanggal_daftar']}</td>
                    <td>{$row['nama_kursus']}</td>
                    <td>
                        <form method='post' action='editpeserta.php' style='display:inline;'>
                            <input type='hidden' name='id_peserta' value='{$row['id_peserta']}'>
                            <button class='edit' type='submit' name='edit'>Edit</button>
                        </form>
                        <form method='post' action='deletepeserta.php' style='display:inline;'>
                            <input type='hidden' name='id_peserta' value='{$row['id_peserta']}'>
                            <button class='delete' type='submit' name='delete'>Delete</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </fieldset>

    <script>
        function edit(id_peserta, nama_peserta, email, no_telp, tanggal_lahir, tanggal_daftar, kursus_id) {
            document.getElementById('id_peserta_edit').value = id_peserta;
            document.getElementById('nama_peserta_edit').value = nama_peserta;
            document.getElementById('email_edit').value = email;
            document.getElementById('no_telp_edit').value = no_telp;
            document.getElementById('tanggal_lahir_edit').value = tanggal_lahir;
            document.getElementById('tanggal_daftar_edit').value = tanggal_daftar;
            document.getElementById('kursus_id_edit').value = kursus_id;
        }
    </script>
</body>
</html>
