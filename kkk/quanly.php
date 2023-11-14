<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<nav>
    <a href="login.php">Đăng nhập</a>
    <a href="quanly.php">Quản lý sản phẩm</a>
    <a href="register.php">Đăng ký</a>
</nav>

<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION["tenDangNhap"])) {
    header("Location: login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu MySQL
include 'connect.php';

$conn = connectToDatabase();


// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý chỉnh sửa thông tin sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_edit"])) {
    $maSanPham = $_POST["maSanPham"];
    $tenSanPham = $_POST["tenSanPham"];
    $soLuong = $_POST["soLuong"];
    $gia = $_POST["gia"];

    // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
    $sql_update = "UPDATE products 
                   SET tenSanPham='$tenSanPham', soLuong=$soLuong, gia=$gia 
                   WHERE maSanPham='$maSanPham'";

    if ($conn->query($sql_update) === TRUE) {
        echo "Chỉnh sửa thông tin sản phẩm thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_delete"])) {
        $maSanPhamToDelete = $_POST["maSanPham"];

        // Thực hiện thao tác xóa trong cơ sở dữ liệu
        $sql_delete = "DELETE FROM products WHERE maSanPham='$maSanPhamToDelete'";
        if ($conn->query($sql_delete) === TRUE) {
            echo "Xóa sản phẩm thành công.";
        } else {
            echo "Lỗi khi xóa sản phẩm: " . $conn->error;
        }
        
        // Chuyển hướng về trang này để tránh việc gửi lại dữ liệu khi refresh
        header("Location: quanly.php");
        exit();
    }
}

// Hiển thị danh sách sản phẩm
$sql_select = "SELECT * FROM products";
$result = $conn->query($sql_select);

echo "<table border='1'>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng còn</th>
            <th>Giá</th>
            <th>Chỉnh sửa</th>
            <th>Xóa</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['maSanPham']}</td>
            <td>{$row['tenSanPham']}</td>
            <td>{$row['soLuong']}</td>
            <td>{$row['gia']}</td>
            
            <td>
                <form method='post' action=''>
                    <input type='hidden' name='maSanPham' value='{$row['maSanPham']}'>
                    <input type='text' name='tenSanPham' value='{$row['tenSanPham']}' required>
                    <input type='number' name='soLuong' value='{$row['soLuong']}' required>
                    <input type='number' name='gia' value='{$row['gia']}' required>
                    <input type='submit' name='submit_edit' value='Chỉnh sửa'>
                    
                </form>
            </td>
            <td>
                <form method='post' action=''>
                    <input type='hidden' name='maSanPham' value='{$row['maSanPham']}'>
                    <input type='submit' name='submit_delete' value='Xóa'>
                </form>
            </td>
          </tr>";
}

echo "</table>";



?>
<!-- Form thêm sản phẩm -->
<h2>Thêm Sản Phẩm Mới</h2>
<form method="post" action="" onsubmit='DataView()'>
    <label for="maSanPham">Mã sản phẩm:</label>
    <input type="text" name="maSanPham" required>

    <label for="tenSanPham">Tên sản phẩm:</label>
    <input type="text" name="tenSanPham" required>

    <label for="soLuong">Số lượng:</label>
    <input type="number" name="soLuong" required>

    <label for="gia">Giá:</label>
    <input type="number" name="gia" required>

    <input type="submit" name="submit_add_product" value="Thêm sản phẩm">
</form>
<?php
// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_add_product"])) {
    $maSanPham = $_POST["maSanPham"];
    $tenSanPham = $_POST["tenSanPham"];
    $soLuong = $_POST["soLuong"];
    $gia = $_POST["gia"];

    // Thêm sản phẩm mới vào cơ sở dữ liệu
    $sql_add_product = "INSERT INTO products (maSanPham, tenSanPham, soLuong, gia)
                        VALUES ('$maSanPham', '$tenSanPham', $soLuong, $gia)";

    if ($conn->query($sql_add_product) === TRUE) {
        echo "Thêm sản phẩm thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
    
    header("Location: quanly.php");
    exit();
    
    
}


// Đóng kết nối
$conn->close();

?>

</body>
</html>
