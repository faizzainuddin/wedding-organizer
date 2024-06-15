<?php
require_once("config.php");

// Mengatur zona waktu menjadi WIB
date_default_timezone_set('Asia/Jakarta');
// Fungsi untuk membuat pesanan
function createOrder($customer_id, $package_id, $status, $event_date, $payment_method) {
    global $conn;

    $currentDateTime = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE o_p_id = ? AND o_event_date = ?");
    $stmt->bind_param("is", $package_id, $event_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['total'] > 0) {
        return false; // Sudah ada pesanan untuk paket ini pada tanggal tersebut
    }

    $stmt = $conn->prepare("INSERT INTO orders (o_user_id, o_p_id, o_pm_id, o_status, o_event_date, o_created_at ) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $customer_id, $package_id, $payment_method, $status, $event_date, $currentDateTime);
    
    return $stmt->execute();
}

function getPackageById($package_id) {
    global $conn;   

    // Siapkan statement SQL
    $stmt = $conn->prepare("SELECT * FROM packages WHERE p_id=?");
    $stmt->bind_param("i", $package_id);

    // Jalankan statement SQL
    $stmt->execute();

    // Ambil hasil query
    $result = $stmt->get_result();

    // Ambil data package sebagai array assosiatif
    return $result->fetch_assoc();
}

function getPaymentMethods() {
    global $conn; // Pastikan $conn diambil dari file config.php atau di mana koneksi database didefinisikan
    $query = "SELECT pm_id, pm_name, pm_detail FROM payment_method";
    $result = mysqli_query($conn, $query);
    $payment_methods = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $payment_methods[] = $row;
        }
    }
    return $payment_methods;
}

function getExistingEventDates() {
    global $conn;

    $query = "SELECT o_event_date FROM orders";
    $result = $conn->query($query);

    $dates = [];
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['o_event_date'];
    }

    return $dates;
}
