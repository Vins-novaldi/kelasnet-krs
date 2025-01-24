<?php
require "../../koneksi.php";

header('Content-Type: application/json');

// Validasi parameter id_ruangan
if (!isset($_GET['id_ruangan']) || empty($_GET['id_ruangan'])) {
    echo json_encode([
        'success' => false,
        'message' => "ID ruangan tidak diberikan."
    ]);
    exit;
}

$id_ruangan = $_GET['id_ruangan'];
date_default_timezone_set('Asia/Jakarta'); 
$currentDateTime = date('Y-m-d H:i:s'); // Waktu sekarang dalam format lengkap

try {
    // Query jadwal berikutnya secara real-time
    $stmt = $con->prepare("SELECT jadwal_mulai 
        FROM Kelas 
        WHERE id_ruangan = ? AND jadwal_mulai > ? 
        
        UNION ALL

        SELECT k.jadwal_mulai 
        FROM Log_Pindah_Ruangan lpr
        JOIN Kelas k ON lpr.id_kelas = k.id_kelas
        WHERE lpr.id_ruangan_baru = ? AND k.jadwal_mulai > ? 
        
        ORDER BY jadwal_mulai ASC 
        LIMIT 1
    ");

    // Bind parameter ke query
    $stmt->bind_param("ssss", $id_ruangan, $currentDateTime, $id_ruangan, $currentDateTime);
    $stmt->execute();
    $stmt->bind_result($nextJadwalMulai);
    $stmt->fetch();
    $stmt->close();

    // Hitung waktu menuju jadwal berikutnya
    if ($nextJadwalMulai) {
        $nextJadwalMulaiTimestamp = strtotime($nextJadwalMulai);
        $currentTime = time();
        $timeDiff = $nextJadwalMulaiTimestamp - $currentTime;

        if ($timeDiff > 0) {
            $hours = floor($timeDiff / 3600);
            $minutes = floor(($timeDiff % 3600) / 60);
            $seconds = $timeDiff % 60;

            echo json_encode([
                'success' => true,
                'message' => "Jadwal berikutnya ditemukan.",
                'next_schedule' => $nextJadwalMulai,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Tidak ada kelas berikutnya dalam waktu dekat."
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Tidak ada jadwal berikutnya ditemukan."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => "Terjadi kesalahan: " . $e->getMessage()
    ]);
}
?>
