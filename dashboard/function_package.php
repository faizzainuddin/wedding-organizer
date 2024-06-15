
<?php
// Sambungkan ke database
require_once("../config.php");


function addPackage($name, $description, $price, $image) {
    global $conn;

    // Siapkan statement SQL
    $stmt = $conn->prepare("INSERT INTO packages (p_name, p_description, p_price, p_image, p_created_at) VALUES (?, ?, ?, ?, ?)");
    // Mengatur zona waktu menjadi WIB
    date_default_timezone_set('Asia/Jakarta');

    // Mendapatkan waktu saat ini
    $created_at = date('Y-m-d H:i:s');

    // Mengikat parameter
    $stmt->bind_param("ssdss", $name, $description, $price, $image, $created_at);

    // Jalankan statement SQL
    return $stmt->execute();
}

function getPackages($start_date = null, $end_date = null) {
    global $conn;

    $query = "SELECT * FROM packages";
    $params = [];
    $types = '';

    if ($start_date && $end_date) {
        $query .= " WHERE p_created_at BETWEEN ? AND ?";
        $params[] = $start_date;
        $params[] = $end_date;
        $types .= 'ss';
    }

    $stmt = $conn->prepare($query);

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];

    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }

    return $packages;
}

function editPackage($package_id, $name, $description, $price, $image) {
    global $conn;

    // Siapkan statement SQL
    $stmt = $conn->prepare("UPDATE packages SET p_name=?, p_description=?, p_price=?, p_image=? WHERE p_id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $package_id);

    // Jalankan statement SQL
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

function deletePackage($package_id) {
    global $conn;

    // Ambil path gambar dari database
    $stmt = $conn->prepare("SELECT p_image FROM packages WHERE p_id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Hapus entri database dan file gambar dari folder /uploads
    $stmt = $conn->prepare("DELETE FROM packages WHERE p_id = ?");
    $stmt->bind_param("i", $package_id);
    $result = $stmt->execute();
    $stmt->close();

    if ($result && !empty($image) && file_exists($image)) {
        unlink($image);
    }

    return $result;
}


?>

