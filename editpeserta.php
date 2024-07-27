<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit'])) {
        $id_peserta = $_POST['id_peserta'];

        // Ambil data peserta berdasarkan ID
        $sql = "SELECT * FROM peserta WHERE id_peserta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_peserta);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $nama_peserta = $row['nama_peserta'];
            $email = $row['email'];
            $no_telp = $row['no_telp'];
            $tanggal_lahir = $row['tanggal_lahir'];
            $tanggal_daftar = $row['tanggal_daftar'];
            $kursus_id = $row['kursus_id'];
        } else {
            echo "Peserta tidak ditemukan.";
            exit();
        }
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

        if ($stmt->affected_rows > 0) {
            header("Location: infopeserta.php");
            exit();
        } else {
            echo "Update failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Peserta</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Edit Peserta</h2>
    </div>

    <div class="container">
        <fieldset>
            <legend>Form Edit Peserta</legend>
            <form method="post">
                <input type="hidden" name="id_peserta" value="<?php echo $id_peserta; ?>">
                Nama Peserta: <input type="text" name="nama_peserta" value="<?php echo $nama_peserta; ?>" required><br>
                Email: <input type="email" name="email" value="<?php echo $email; ?>" required><br>
                No Telpon: <input type="text" name="no_telp" value="<?php echo $no_telp; ?>" required><br>
                Tanggal Lahir: <input type="date" name="tanggal_lahir" value="<?php echo $tanggal_lahir; ?>" required><br>
                Tanggal Daftar: <input type="date" name="tanggal_daftar" value="<?php echo $tanggal_daftar; ?>" required><br>
                Kursus:
                <select name="kursus_id" required>
                    <?php
                    $result_kursus = $conn->query("SELECT * FROM kursus");
                    while ($row_kursus = $result_kursus->fetch_assoc()) {
                        $selected = ($row_kursus['id_kursus'] == $kursus_id) ? 'selected' : '';
                        echo "<option value='{$row_kursus['id_kursus']}' $selected>{$row_kursus['nama_kursus']}</option>";
                    }
                    ?>
                </select><br>
                <button type="submit" name="update">Update</button>
            </form>
        </fieldset>
    </div>

</body>
</html>
