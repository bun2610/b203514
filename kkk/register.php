<!-- Trang login.php và register.php -->
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
    <a href="login.php">Đăng nhập</a>
    <a href="register.php">Đăng ký</a>
</nav>

<div class="container">
<?php
session_start();

// Kết nối đến cơ sở dữ liệu MySQL
include 'connect.php';

$conn = connectToDatabase();


// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}?>
<h2>Đăng ký tài khoản</h2>

<!-- Form đăng ký -->
<form method="post" action="/">
    <label for="hoTen">Họ tên:</label>
    <input type="text" name="hoTen" required>

    <label for="diaChi">Địa chỉ:</label>
    <input type="text" name="diaChi" required>

    <label for="tenDangNhap">Tên đăng nhập:</label>
    <input type="text" name="tenDangNhap" required>

    <label for="matKhau">Mật khẩu:</label>
    <input type="password" name="matKhau" required>

    <input type="submit" name="submit_register" value="Đăng ký">
</form>

<!-- Hiển thị thông báo lỗi (nếu có) -->
<?php
if (isset($loi)) {
    echo "<p style='color: red;'>$loi</p>";
}
function isUsernameExists($conn, $tenDangNhap) {
    $sql_check_user = "SELECT * FROM users WHERE tenDangNhap='$tenDangNhap'";
    $result_check_user = $conn->query($sql_check_user);

    return $result_check_user->num_rows > 0;};
// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_register"])) {
    $hoTen = $_POST["hoTen"];
    $diaChi = $_POST["diaChi"];
    $tenDangNhap = $_POST["tenDangNhap"];
    $matKhau = $_POST["matKhau"];

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $sql_check_user = "SELECT * FROM users WHERE tenDangNhap='$tenDangNhap'";
    $result_check_user = $conn->query($sql_check_user);

    if (isUsernameExists($conn, $tenDangNhap)) {
        $loi = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên đăng nhập khác.";
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $sql_insert_user = "INSERT INTO users (hoTen, diaChi, tenDangNhap, matKhau)
                            VALUES ('$hoTen', '$diaChi', '$tenDangNhap', '$matKhau')";

        if ($conn->query($sql_insert_user) === TRUE) {
            echo "Đăng ký thành công. <a href='login.php'>ĐĂNG NHẬP NGAY</a>.";
        } else {
            echo "Lỗi:  " . $conn->error;
        }
    }
}

// Đóng kết nối
$conn->close();
?>


</div>

</body>
</html>
