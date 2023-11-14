<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<nav>
    <a href="login.php">Đăng nhập </a>
    <a href="register.php">Đăng ký</a>
</nav>
<?php
session_start();

// Kết nối đến cơ sở dữ liệu MySQL
include 'connect.php';

$conn = connectToDatabase();

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = $_POST["tenDangNhap"];
    $matKhau = $_POST["matKhau"];

    // Kiểm tra với cơ sở dữ liệu
    $sql = "SELECT * FROM users WHERE tenDangNhap = '$tenDangNhap' AND matKhau = '$matKhau'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION["tenDangNhap"] = $tenDangNhap;
        header("Location: quanly.php");
        exit();
    } else {
        $loi = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}

// Đóng kết nối
$conn->close();
?>

<!-- Form đăng nhập -->
<form method="post" action="">
    <label for="tenDangNhap">Tên đăng nhập:</label>
    <input type="text" name="tenDangNhap" required>

    <label for="matKhau">Mật khẩu:</label>
    <input type="password" name="matKhau" required>

    <input type="submit" value="Đăng nhập">
</form>

<?php
// Hiển thị thông báo lỗi (nếu có)
if (isset($loi)) {
    echo "<p style='color: red;'>$loi</p>";
}
?>


</body>
</html>
