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
        if ($stmt === false) {
            // Error handling
            die('Error prepare: ' . $conn->error);
        }
        $stmt->bind_param("iii", $id_tugas, $id_peserta, $nilai);
        if ($stmt->execute()) {
            // Insert berhasil
            // Redirect or show success message
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            // Insert gagal
            echo "Gagal menambahkan penilaian: ".$stmt->error;
        }
    } elseif (isset($_POST['update'])) {
        $id_penilaian = $_POST['id_penilaian'];
        $id_tugas = $_POST['id_tugas'];
        $id_peserta = $_POST['id_peserta'];
        $nilai = $_POST['nilai'];

        $sql = "UPDATE penilaian SET id_tugas = ?, id_peserta = ?, nilai = ? WHERE id_penilaian = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // Error handling
            die('Error prepare: ' . $conn->error);
        }
        $stmt->bind_param("iiii", $id_tugas, $id_peserta, $nilai, $id_penilaian);
        if ($stmt->execute()) {
            // Update berhasil
            // Redirect or show success message
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            // Update gagal
            echo "Gagal mengupdate penilaian: ".$stmt->error;
        }
    } elseif (isset($_POST['delete'])) {
        $id_penilaian = $_POST['id_penilaian'];

        $sql = "DELETE FROM penilaian WHERE id_penilaian = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // Error handling
            die('Error prepare: ' . $conn->error);
        }
        $stmt->bind_param("i", $id_penilaian);
        if ($stmt->execute()) {
            // Delete berhasil
            // Redirect or show success message
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            // Delete gagal
            echo "Gagal menghapus penilaian: ".$stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Penilaian</title>
    <link rel="stylesheet" type="text/css" href="test1.css">
</head>
<body>
    <div class="container">
        <h2>Manajemen Penilaian</h2>
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
        <input type="hidden" name="id_penilaian" id="id_penilaian">
        Tugas 
        <select name="id_tugas" id="id_tugas" required>
            <?php
            // Fetch data dari tabel tugas untuk dropdown
            $result_tugas = $conn->query("SELECT * FROM tugas");
            if ($result_tugas) {
                while ($row_tugas = $result_tugas->fetch_assoc()) {
                    echo "<option value='{$row_tugas['id_tugas']}'>{$row_tugas['nama_tugas']}</option>";
                }
            } else {
                echo "Failed to fetch tugas info.";
            }
            ?>
        </select><br>
        Peserta 
        <select name="id_peserta" id="id_peserta" required>
            <?php
            // Fetch data dari tabel peserta untuk dropdown
            $result_peserta = $conn->query("SELECT * FROM peserta");
            if ($result_peserta) {
                while ($row_peserta = $result_peserta->fetch_assoc()) {
                    echo "<option value='{$row_peserta['id_peserta']}'>{$row_peserta['nama_peserta']}</option>";
                }
            } else {
                echo "Failed to fetch peserta info.";
            }
            ?>
        </select><br>
        Nilai <input type="number" name="nilai" id="nilai" required><br>
        <button type="submit" name="create">Create</button>
        <button type="submit" name="update">Update</button>
		</fieldset>
    </form>

    <h3 align="center">Daftar Penilaian</h3>
    <table>
        <tr>
            <th>ID Penilaian</th>
            <th>Nama Tugas</th>
            <th>Nama Peserta</th>
            <th>Nilai</th>
            <th>Action</th>
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
                            <td>
                                <button class='edit' onclick=\"edit({$row_penilaian['id_penilaian']}, {$row_penilaian['id_tugas']}, {$row_penilaian['id_peserta']}, {$row_penilaian['nilai']})\">Edit</button>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id_penilaian' value='{$row_penilaian['id_penilaian']}'>
                                    <button class='delete' type='submit' name='delete'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    } else {
                        echo "<tr>
                            <td>{$row_penilaian['id_penilaian']}</td>
                            <td colspan='3'>Failed to fetch tugas or peserta info.</td>
                            <td>
                                <button class='edit' onclick=\"edit({$row_penilaian['id_penilaian']}, {$row_penilaian['id_tugas']}, {$row_penilaian['id_peserta']}, {$row_penilaian['nilai']})\">Edit</button>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id_penilaian' value='{$row_penilaian['id_penilaian']}'>
                                    <button class='delete' type='submit' name='delete'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr>
                        <td>{$row_penilaian['id_penilaian']}</td>
                        <td colspan='3'>Failed to execute tugas or peserta query.</td>
                        <td>
                            <button class='edit' onclick=\"edit({$row_penilaian['id_penilaian']}, {$row_penilaian['id_tugas']}, {$row_penilaian['id_peserta']}, {$row_penilaian['nilai']})\">Edit</button>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id_penilaian' value='{$row_penilaian['id_penilaian']}'>
                                <button class='delete' type='submit' name='delete'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            }
        } else {
            echo "Failed to fetch penilaian info.";
        }

        // Close connection
        $conn->close();
        ?>
    </table>

    <script>
        function edit(id_penilaian, id_tugas, id_peserta, nilai) {
            document.getElementById('id_penilaian').value = id_penilaian;
            document.getElementById('id_tugas').value = id_tugas;
            document.getElementById('id_peserta').value = id_peserta;
            document.getElementById('nilai').value = nilai;
        }
    </script>
</body>
</html>
