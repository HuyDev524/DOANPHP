<?php
// Hàm lấy giá trị từ POST (Dùng chung cho cả 2 form)
function postIndex($index, $value="")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}

// --- DỮ LIỆU CHUNG ---
// Dùng biến action để xác định form nào được submit
$action     = postIndex("action"); 

// --- DỮ LIỆU FORM 1: ĐĂNG KÝ ---
$username   = postIndex("username");
$password1  = postIndex("password1");
$password2  = postIndex("password2");
$name       = postIndex("name");
$thong_tin  = postIndex("thong_tin");

// --- DỮ LIỆU FORM 2: REGEX TOOL ---
$img_name   = postIndex("img_name");
$regex_content = postIndex("regex_content");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 6 Tổng hợp: Xử lý Chuỗi & Regex</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
    
    /* Style chung cho Fieldset */
    fieldset { 
        width: 70%; 
        margin: 20px auto; 
        border: 1px solid #ccc; 
        padding: 20px; 
        box-shadow: 2px 2px 10px #aaa; 
        background: #fff;
    }
    legend { 
        font-weight: bold; 
        color: #fff; 
        background: #006; 
        padding: 5px 15px; 
        border-radius: 4px;
    }
    
    /* Input & Table */
    table { width: 100%; }
    td { padding: 8px; }
    input[type="text"], input[type="password"], textarea { 
        width: 95%; 
        padding: 5px; 
        border: 1px solid #ccc; 
    }
    input[type="submit"] {
        padding: 8px 20px;
        cursor: pointer;
        background: #006;
        color: #fff;
        border: none;
        font-weight: bold;
    }
    
    /* Khu vực hiển thị kết quả */
    .info { 
        width: 70%; 
        color: #006; 
        background: #e6ffff; 
        margin: 10px auto; 
        padding: 15px; 
        border: 1px solid #6FC; 
    }
    .result-section { 
        border-bottom: 1px dashed #ccc; 
        padding-bottom: 10px; 
        margin-bottom: 10px; 
    }
    
    /* Style cho đúng/sai */
    .valid { color: green; font-weight: bold; }
    .invalid { color: red; font-weight: bold; }
    ul { margin: 5px 0 10px 20px; padding: 0; }
</style>
</head>

<body>

<!-- ======================================================================= -->
<!-- PHẦN 1: XỬ LÝ CHUỖI & MẬT KHẨU (CODE CỦA BẠN) -->
<!-- ======================================================================= -->
<form action="" method="post">
<!-- Thêm hidden field để nhận diện form -->
<input type="hidden" name="action" value="register">
<fieldset>
    <legend>Phần 1: Thông tin đăng ký (Xử lý String)</legend>
        <table align="center">
            <tr>
                <td width="30%">Tên đăng nhập:</td>
                <td><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"></td>
            </tr>
            <tr>
                <td>Mật khẩu:</td>
                <td><input type="password" name="password1"/></td>
            </tr>
            <tr>
                <td>Nhập lại mật khẩu:</td>
                <td><input type="password" name="password2"/></td>
            </tr>
            <tr>
                <td>Họ Tên:</td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"/></td>
            </tr>
            <tr>
                <td valign="top">Thông tin thêm:</td>
                <td>
                    <textarea name="thong_tin" rows="5"><?php echo htmlspecialchars($thong_tin); ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Xử lý Đăng Ký">
                </td>
            </tr>
        </table>
</fieldset>
</form>

<?php
// Kiểm tra action form 1
if ($action == "register") {
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
            // 1. Xử lý Mật khẩu
            $pass_sha1 = sha1($password1);
            $pass_combined = md5(sha1($password1)); // Kết hợp MD5 lồng SHA1

            // 2. Xử lý Thông tin (thong_tin)
            $thong_tin_stripped = strip_tags($thong_tin);
            $thong_tin_hien_thi = nl2br($thong_tin_stripped);
            $thong_tin_add_slashes = addslashes($thong_tin);
            $thong_tin_strip_slashes = stripslashes($thong_tin_add_slashes);

            // --- XUẤT KẾT QUẢ FORM 1 ---
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
            echo "1. Nội dung gốc (đã loại bỏ HTML & nl2br):<br>";
            echo "<div style='background:#fff; padding:5px; border:1px solid #ccc'>$thong_tin_hien_thi</div><br>";
            echo "2. Addslashes (Lưu DB): <code>" . htmlspecialchars($thong_tin_add_slashes) . "</code><br>";
            echo "3. Stripslashes (Hiển thị): <code>" . htmlspecialchars($thong_tin_strip_slashes) . "</code>";
            echo "</div>";
        }
        ?>
    </div>
    <?php
}
?>


<!-- ======================================================================= -->
<!-- PHẦN 2: REGEX TOOL (YÊU CẦU MỚI) -->
<!-- ======================================================================= -->
<form action="" method="post">
<!-- Thêm hidden field để nhận diện form -->
<input type="hidden" name="action" value="regex_tool">
<fieldset>
    <legend>Phần 2: Công cụ Regex & Kiểm tra dữ liệu</legend>
    <table align="center">
        <!-- a. Kiểm tra tên hình ảnh -->
        <tr>
            <td width="30%">1. Nhập tên hình ảnh:</td>
            <td>
                <input type="text" name="img_name" value="<?php echo htmlspecialchars($img_name); ?>" placeholder="Ví dụ: hinh_anh_dep.jpg">
                <br><small><i>(Chỉ chứa chữ không dấu, số, gạch dưới. Đuôi: jpg, png, gif...)</i></small>
            </td>
        </tr>
        
        <!-- b. Textarea nhập liệu để lọc -->
        <tr>
            <td valign="top">2. Nhập văn bản cần lọc:</td>
            <td>
                <textarea name="regex_content" rows="6" placeholder="Nhập văn bản có chứa Email, SĐT hoặc Link vào đây..."><?php echo htmlspecialchars($regex_content); ?></textarea>
            </td>
        </tr>
        
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Kiểm tra & Lọc Dữ liệu">
            </td>
        </tr>
    </table>
</fieldset>
</form>

<?php
// Kiểm tra action form 2
if ($action == "regex_tool") {
    echo "<div class='info'>";
    echo "<h3>KẾT QUẢ XỬ LÝ REGEX:</h3>";

    // --- C. KIỂM TRA TÊN HÌNH ẢNH ---
    echo "<div class='result-section'>";
    $pattern_img = '/^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|gif|webp)$/i';
    echo "<b>Kiểm tra file ảnh:</b> '$img_name' &rarr; ";
    if (preg_match($pattern_img, $img_name)) {
        echo "<span class='valid'>HỢP LỆ</span>";
    } else {
        echo "<span class='invalid'>KHÔNG HỢP LỆ</span>";
    }
    echo "</div>";

    // --- A. LỌC LINK (URL) ---
    echo "<div class='result-section'>";
    echo "<b>a. Danh sách Link (URL):</b><br>";
    $pattern_link = '/https?:\/\/[^\s"<>]+/';
    preg_match_all($pattern_link, $regex_content, $matches_link);
    $links = array_unique($matches_link[0]);

    if (count($links) > 0) {
        echo "<ul>";
        foreach ($links as $lnk) echo "<li><a href='$lnk' target='_blank'>$lnk</a></li>";
        echo "</ul>";
    } else {
        echo "<i>Không tìm thấy link nào.</i><br>";
    }
    echo "</div>";

    // --- B. LỌC EMAIL & SỐ ĐIỆN THOẠI ---
    echo "<div class='result-section'>";
    echo "<b>b. Danh sách Email & SĐT:</b><br>";
    
    // Email regex
    $pattern_email = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
    preg_match_all($pattern_email, $regex_content, $matches_email);
    $emails = array_unique($matches_email[0]);

    // Phone regex (VN)
    $pattern_phone = '/(0|\+84)(3|5|7|8|9)[0-9]{8}\b/';
    preg_match_all($pattern_phone, $regex_content, $matches_phone);
    $phones = array_unique($matches_phone[0]);

    echo "<u>Email tìm thấy:</u> ";
    if(count($emails)>0) echo implode(", ", $emails); else echo "Không có";
    
    echo "<br><br><u>SĐT tìm thấy:</u> ";
    if(count($phones)>0) echo implode(", ", $phones); else echo "Không có";
    echo "</div>";

    echo "</div>"; // End div.info
}
?>

</body>
</html>