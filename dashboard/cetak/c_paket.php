<?php
require_once("../../config.php");
// Ambil package_id dari parameter URL
if (isset($_GET['id'])) {
    $package_id = intval($_GET['id']);
} else {
    die("ID package tidak ditemukan.");
}
function getPackageData($package_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT p_id, p_name, p_description, p_price, p_image, p_created_at FROM packages WHERE p_id = ?");
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Ambil data package
$packageData = getPackageData($package_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Paket - UNPWedding</title>
    <link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/chartist.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.min.css">
    <style>
    @media print 
    {
        @page { margin: 0; }
        body  { margin: 1.6cm; }
        title{ display: none; }
        img { height: 100px; width: 100px;}
    }

  </style>
</head>
<body>
    <table class="table table-striped" id="example" style="width:100%">
        <thead>
            <tr>
                <th scope="col">#UNPWedding - Print Paket</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"> PackageID :
                <td><?php echo $packageData['p_id']; ?></td>
            </tr>
            <tr>
                <th scope="row"> Package Name :
                <td><?php echo htmlspecialchars($packageData['p_name']); ?></td>
            </tr>
            <tr>
                <th scope="row"> Image :
                <td><img src='../<?php echo htmlspecialchars($packageData['p_image']); ?>' height='100px' width='50'></td>
            </tr>
            <tr>
                <th scope="row"> Description :
                <td><?php echo htmlspecialchars($packageData['p_description']); ?></td>
            </tr>
            <tr>
                <th scope="row"> Price :
                <td><?php echo htmlspecialchars($packageData['p_price']); ?></td>
            </tr>
            <tr>
                <th scope="row"> CreatedAt :
                <td><?php echo htmlspecialchars($packageData['p_created_at']); ?></td>
            </tr>
        </tbody>
    </table>

</body>
<script>window.print()</script>
<script src="../../assets/js/jquery-3.7.1.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/dataTables.js"></script>
<Script src="../../assets/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

</html>