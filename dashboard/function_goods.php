    <?php
    require_once("../config.php");

    // Set default timezone
    date_default_timezone_set('Asia/Jakarta');

    // Fungsi untuk menambahkan barang
    function goodsAdd($name, $price, $qty, $package_id) {
        global $conn;

        // Ambil tanggal dan waktu saat ini
        $currentDateTime = date("Y-m-d H:i:s");

        // Siapkan statement SQL
        $stmt = $conn->prepare("INSERT INTO goods (g_name, g_price, g_qty, g_created_at, g_p_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisi", $name, $price, $qty, $currentDateTime, $package_id);
        
        // Jalankan statement SQL
        if ($stmt->execute()) {
            return true; // Penambahan berhasil
        } else {
            return false; // Penambahan gagal
        }
    }

    // Fungsi untuk mengedit barang
    function goodsEdit($id, $name, $price, $qty, $package_id) {
        global $conn;

        // Siapkan statement SQL
        $stmt = $conn->prepare("UPDATE goods SET g_name = ?, g_price = ?, g_qty = ?, g_p_id = ? WHERE g_id = ?");
        $stmt->bind_param("sdisi", $name, $price, $qty, $package_id, $id);

        // Jalankan statement SQL
        if ($stmt->execute()) {
            return true; // Pengeditan berhasil
        } else {
            return false; // Pengeditan gagal
        }
    }

    function getDataGoods() {
        global $conn;

        // Siapkan statement SQL
        $stmt = $conn->prepare("SELECT g.*, p.p_name AS p_name FROM goods g LEFT JOIN packages p ON g.g_p_id = p.p_id ORDER BY g_created_at DESC");

        // Jalankan statement SQL
        $stmt->execute();

        // Ambil hasil query
        $result = $stmt->get_result();

        // Ambil data barang sebagai array assosiatif
        $goods = [];
        while ($row = $result->fetch_assoc()) {
            $goods[] = $row;
        }

        return $goods;
    }


    // Fungsi untuk mendapatkan data barang berdasarkan ID
    function getGoodsById($id) {
        global $conn;

        // Siapkan statement SQL
        $stmt = $conn->prepare("SELECT * FROM goods WHERE g_id = ?");
        $stmt->bind_param("i", $id);

        // Jalankan statement SQL
        $stmt->execute();

        // Ambil hasil query
        $result = $stmt->get_result();

        // Ambil data barang sebagai array assosiatif
        return $result->fetch_assoc();
    }


    // Fungsi untuk mendapatkan semua paket
    function getPackages() {
        global $conn;

        // Siapkan statement SQL
        $stmt = $conn->prepare("SELECT p_id, p_name FROM packages");

        // Jalankan statement SQL
        $stmt->execute();

        // Ambil hasil query
        $result = $stmt->get_result();

        // Ambil data paket sebagai array assosiatif
        $packages = [];
        while ($row = $result->fetch_assoc()) {
            $packages[] = $row;
        }
        while ($row = $result->fetch_assoc()) {
            $goods[] = $row;
        }

        return $packages;
    }

    function deleteGoods($goods_id) {
        global $conn;
    
        // Siapkan statement SQL
        $stmt = $conn->prepare("DELETE FROM goods WHERE g_id = ?");
        $stmt->bind_param("i", $goods_id);
    
        // Jalankan statement SQL
        if ($stmt->execute()) {
            return true; // Penghapusan berhasil
        } else {
            return false; // Penghapusan gagal
        }
    }
    function goodsOutcome() {
        global $conn;
    
        // Siapkan statement SQL untuk menghitung total harga barang
        $stmt = $conn->prepare("SELECT SUM(g_price * g_qty) AS total_outcome FROM goods");
    
        // Jalankan statement SQL
        $stmt->execute();
    
        // Ambil hasil query
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        // Kembalikan total pengeluaran
        return $row['total_outcome'] ?? 0;
    }
?>
