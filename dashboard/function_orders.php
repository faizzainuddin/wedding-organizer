<?php
require_once("../config.php");

######### BAGIAN CLIENT-SIDE #########

function getUserOrders($user_id) {
    global $conn;

    $query = "SELECT o.o_id, o.o_status, o.o_event_date, o.o_created_at, p.p_name, COALESCE(pm.p_amount, 0) AS p_amount, COALESCE(pm.payment_status, '') AS payment_status 
              FROM orders o 
              JOIN packages p ON o.o_p_id = p.p_id 
              LEFT JOIN payments pm ON o.o_id = pm.p_o_id 
              WHERE o.o_user_id = ?
              ORDER BY o.o_created_at DESC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
}

function getPaymentAmountByOrderId($order_id) {
    global $conn;

    $query = "SELECT p_amount FROM payments WHERE p_o_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['p_amount'];
    } else {
        return null; // Jika tidak ada hasil yang ditemukan
    }
}

function getBankDetailsByOrderId($order_id) {
    global $conn;

    $query = "SELECT pm.pm_name, pm.pm_detail 
              FROM payment_method pm 
              JOIN orders o ON pm.pm_id = o.o_pm_id 
              WHERE o.o_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Jika tidak ada hasil yang ditemukan
    }
}

function getTransferProofByOrderId($order_id) {
    global $conn;

    $query = "SELECT py.payment_proof
              FROM payments py 
              JOIN orders o ON py.p_o_id = o.o_id 
              WHERE o.o_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Jika tidak ada hasil yang ditemukan
    }
}
####### SERVER-SIDE ########

// Mengatur zona waktu menjadi WIB
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk membuat pesanan
function createOrder($customer_id, $package_id, $status, $event_date, $payment_method, $payment_status) {
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

    $stmt = $conn->prepare("INSERT INTO orders (o_user_id, o_p_id, o_payment_id, o_status, o_event_date, o_created_at, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissss", $customer_id, $package_id, $payment_method, $status, $event_date, $currentDateTime, $payment_status);
    
    return $stmt->execute();
}

// Fungsi untuk mengedit pesanan
function editOrder($order_id, $package_id, $status, $event_date, $payment_method, $payment_status) {
    global $conn;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Update orders
        $stmt = $conn->prepare("UPDATE orders SET o_p_id = ?, o_pm_id = ?, o_status = ?, o_event_date = ? WHERE o_id = ?");
        $stmt->bind_param("iissi", $package_id, $payment_method, $status, $event_date, $order_id);
        $stmt->execute();

        // Update payment status in payments
        $stmt = $conn->prepare("UPDATE payments SET payment_status = ? WHERE p_o_id = ?");
        $stmt->bind_param("si", $payment_status, $order_id);
        $stmt->execute();

        $conn->commit();
        return true;
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        throw $exception;
    }
}

// Fungsi untuk mendapatkan pesanan
function getOrders() {
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            o.o_id,
            u.username,
            p.p_name,
            pm.pm_name,
            o.o_status,
            py.payment_status,
            o.o_event_date,
            o.o_created_at
        FROM orders o
        JOIN users u ON o.o_user_id = u.user_id
        JOIN packages p ON o.o_p_id = p.p_id
        LEFT JOIN payments py ON o.o_id = py.p_o_id
        LEFT JOIN payment_method pm ON o.o_pm_id = pm.pm_id
        WHERE u.level = 'User'
        ORDER BY o.o_created_at DESC
    ");
    
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
}

// Fungsi untuk mendapatkan semua pengguna
function getUsers() {
    global $conn;

    $stmt = $conn->prepare("SELECT user_id, username FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

// Fungsi untuk mendapatkan semua paket
function getPackages() {
    global $conn;

    $stmt = $conn->prepare("SELECT p_id, p_name FROM packages");
    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];

    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }

    return $packages;
}

// Fungsi untuk mendapatkan semua metode pembayaran
function getPaymentMethods() {
    global $conn;

    $stmt = $conn->prepare("SELECT pm_id, pm_name FROM payment_method");
    $stmt->execute();
    $result = $stmt->get_result();
    $methods = [];

    while ($row = $result->fetch_assoc()) {
        $methods[] = $row;
    }

    return $methods;
}

// Fungsi untuk mendapatkan semua status pembayaran
function getPaymentStatuses() {
    return ['Unpaid', 'Paid DP', 'Fully Paid'];
}

// Fungsi untuk mendapatkan pesanan berdasarkan ID
function getOrderById($order_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            o.o_id,
            o.o_p_id,
            o.o_pm_id,
            o.o_status,
            py.payment_status,
            o.o_event_date,
            o.o_created_at,
            p.p_name,
            pm.pm_name
        FROM orders o
        JOIN packages p ON o.o_p_id = p.p_id
        LEFT JOIN payments py ON o.o_pm_id = py.payment_id
        LEFT JOIN payment_method pm ON py.p_pm_id = pm.pm_id
        WHERE o.o_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}
// Fungsi untuk menghapus pesanan
function deleteOrder($order_id) {
    global $conn;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Hapus dari tabel payments
        $stmt = $conn->prepare("DELETE FROM payments WHERE p_o_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // Hapus dari tabel orders
        $stmt = $conn->prepare("DELETE FROM orders WHERE o_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $conn->commit();
        return true;
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        throw $exception;
    }
}
?>

