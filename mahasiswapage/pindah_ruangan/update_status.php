<?php
// Sertakan file koneksi database
require "../../koneksi.php";

// Atur zona waktu
date_default_timezone_set('Asia/Jakarta');

/**
 * Fungsi untuk memperbarui status menjadi 'expired'
 * jika waktu sekarang melebihi jadwal_selesai_baru
 */
function updateExpiredStatus($connection) {
    // Query untuk memperbarui status
    $query = "
        UPDATE log_pindah_ruangan 
        SET status = 'expired' 
        WHERE status = 'valid' 
        AND TIME(NOW()) >= jadwal_selesai_baru
    ";

    // Eksekusi query
    if (mysqli_query($connection, $query)) {
        echo "Status berhasil diperbarui.";
    } else {
        // Tampilkan error jika query gagal
        echo "Error memperbarui status: " . mysqli_error($connection);
    }
}

// Cek koneksi database
if (!$con) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Jalankan fungsi untuk memperbarui status
updateExpiredStatus($con);

// Tutup koneksi database
mysqli_close($con);
?>
