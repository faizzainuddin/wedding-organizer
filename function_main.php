<?php
function getDataPackages() {
    global $conn;

    // Siapkan statement SQL
    $stmt = $conn->prepare("SELECT * FROM packages");

    // Jalankan statement SQL
    $stmt->execute();

    // Ambil hasil query
    $result = $stmt->get_result();

    // Ambil data users sebagai array assosiatif
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}
?>