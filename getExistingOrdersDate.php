<?php
require_once("config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    die("Package ID tidak ditemukan.");
}

// Query untuk mendapatkan tanggal yang sudah dipesan berdasarkan package_id
$query = "SELECT o_event_date FROM orders WHERE o_p_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$dates = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['o_event_date'];
}

echo json_encode($dates);
?>