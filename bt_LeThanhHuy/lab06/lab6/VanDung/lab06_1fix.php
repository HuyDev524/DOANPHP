<?php
// Hàm lấy giá trị từ POST
function postIndex($index, $value="")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}

// Lấy dữ liệu từ form
$username   = postIndex("username");
$password1  = postIndex("password1");
$password2  = postIndex("password2");
$name       = postIndex("name");
$thong_tin  = postIndex("thong_tin"); // Lấy dữ liệu từ textarea
$sm         = postIndex("submit");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab6_1 - Xử lý chuỗi và Mật khẩu</title>
<style>
    body { font-family: Arial, sans-serif; }
    fieldset { width: 50%; margin: 50px auto; border: 1px solid #ccc; padding: 20px; box-shadow: 2px 2px 10px #aaa; }
    legend { font-weight: bold; color: #006; padding: 5px; }
    .info { width: 600px; color: #006; background: #e6ffff; margin: 20px auto; padding: 15px; border: 1px solid #6FC; }
    table { width: 100%; }
    td { padding: 5px; }
    textarea { width: 95%; }
    .result-section { border-bottom: 1px dashed #ccc; padding-bottom: 10px; margin-bottom: 10px; }
</style>
</head>

<body>
<fieldset>
    <legend>Thông tin đăng ký</legend>
    <!-- Action để trống để submit về chính trang hiện tại -->
    <form action="" method="post" enctype="multipart/form-data">
        <table align="center">
            <tr>
                <td width="30%">Tên đăng nhập:</td>
                <td><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" style="width:95%"></td>
            </tr>
            <tr>
                <td>Mật khẩu:</td>
                <td><input type="password" name="password1" style="width:95%"/></td>
            </tr>
            <tr>
                <td>Nhập lại mật khẩu:</td>
                <td><input type="password" name="password2" style="width:95%"/></td>
            </tr>
            <tr>
                <td>Họ Tên:</td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" style="width:95%"/></td>
            </tr>
            <!-- Thêm phần textArea thong_tin -->
            <tr>
                <td valign="top">Thông tin thêm:</td>
                <td>
                    <textarea name="thong_tin" rows="5"><?php echo htmlspecialchars($thong_tin); ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="Đăng ký" name="submit"></td>
            </tr>
        </table>
    </form>
</fieldset>

<?php
if ($sm != "") {
    $err = "";
    // Validate dữ liệu
    if (strlen($username) < 6)
        $err .= "Username ít nhất phải 6 ký tự!<br>";
    if ($password1 != $password2)
        $err .= "Mật khẩu và mật khẩu nhập lại không khớp.<br>";
    if (strlen($password1) < 8)
        $err .= "Mật khẩu phải ít nhất 8 ký tự.<br>";
    if (str_word_count($name) < 2)
        $err .= "Họ tên phải chứa ít nhất 2 từ.<br>";

    ?>
    <div class="info">
        <?php 
        if ($err != "") {
            echo "<span style='color:red'>$err</span>";
        } else {
            // 1. Xử lý Mật khẩu (Yêu cầu: SHA1 hoặc kết hợp SHA1 và MD5)
            $pass_sha1 = sha1($password1);
            $pass_combined = md5(sha1($password1)); // Kết hợp MD5 lồng SHA1

            // 2. Xử lý Thông tin (thong_tin)
            // Loại bỏ thẻ HTML
            $thong_tin_stripped = strip_tags($thong_tin);
            
            // Thay thế xuống dòng (\n) bằng <br> để hiển thị
            $thong_tin_hien_thi = nl2br($thong_tin_stripped);

            // Thêm slash (\) trước ký tự đặc biệt (ví dụ: dấu ')
            $thong_tin_add_slashes = addslashes($thong_tin);

            // Bỏ slash (\) (trả về nguyên gốc)
            $thong_tin_strip_slashes = stripslashes($thong_tin_add_slashes);

            // --- XUẤT KẾT QUẢ ---
            echo "<div class='result-section'>";
            echo "<b>Username:</b> $username <br>";
            echo "<b>Họ tên:</b> " . ucwords($name) . "<br>";
            echo "</div>";

            echo "<div class='result-section'>";
            echo "<b>Mật khẩu mã hóa:</b><br>";
            echo "- MD5 (Cũ): " . md5($password1) . "<br>";
            echo "- SHA1 (Mới): $pass_sha1 <br>";
            echo "- Kết hợp (MD5 của SHA1): $pass_combined <br>";
            echo "</div>";

            echo "<div class='result-section'>";
            echo "<b>Xử lý phần 'Thông tin':</b><br>";
            
            echo "1. Nội dung gốc (đã loại bỏ HTML & chuyển xuống dòng):<br>";
            echo "<div style='background:#fff; padding:5px; border:1px solid #ccc'>$thong_tin_hien_thi</div><br>";

            echo "2. Thêm dấu gạch chéo (addslashes) để lưu DB:<br>";
            echo "<code>" . htmlspecialchars($thong_tin_add_slashes) . "</code><br><br>";

            echo "3. Loại bỏ dấu gạch chéo (stripslashes) để hiển thị lại:<br>";
            echo "<code>" . htmlspecialchars($thong_tin_strip_slashes) . "</code>";
            echo "</div>";
        }
        ?>
    </div>
    <?php
}
?>
</body>
</html>