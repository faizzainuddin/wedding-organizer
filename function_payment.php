<?php
require_once("config.php");

function createPayment($order_id, $customer_id, $payment_method, $sender, $total_payment_with_code, $payment_status, $payment_proof) {
    global $conn;
    $query = "INSERT INTO payments (p_o_id, p_user_id, p_pm_id, payment_sender, p_amount, payment_status, payment_proof) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissss", $order_id, $customer_id, $payment_method, $sender, $total_payment_with_code, $payment_status, $payment_proof);
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        return false;
    }
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
?>
