<?php
function postIndex($index, $value="")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}

function checkUserName($string)
{
    // Cho phép a-z, A-Z, 0-9, dấu chấm, gạch dưới, gạch ngang
    if (preg_match("/^[a-zA-Z0-9._-]*$/", $string)) 
        return true;
    return false;
}

function checkEmail($string)
{   
    if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $string))
        return true;
    return false; 
}

// --- CÁC HÀM MỚI THÊM ---

function checkPassword($string)
{
    // Tối thiểu 8 ký tự, ít nhất 1 số, 1 hoa, 1 thường
    // (?=.*\d) : Có ít nhất 1 số
    // (?=.*[a-z]) : Có ít nhất 1 chữ thường
    // (?=.*[A-Z]) : Có ít nhất 1 chữ hoa
    // .{8,} : Độ dài tối thiểu 8 ký tự
    if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $string))
        return true;
    return false;
}

function checkPhone($string)
{
    // Chỉ chứa các ký tự số
    if (preg_match("/^[0-9]+$/", $string))
        return true;
    return false;
}

function checkUserDate($string)
{
    // Đổi tên hàm từ checkDate thành checkUserDate để tránh trùng với hàm checkdate() có sẵn của PHP
    // Định dạng dd/mm/yyyy hoặc dd-mm-yyyy
    // 0[1-9]|[1-2][0-9]|3[0-1] : Ngày từ 01 đến 31
    // 0[1-9]|1[0-2] : Tháng từ 01 đến 12
    // [0-9]{4} : Năm 4 chữ số
    if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])[\/-](0[1-9]|1[0-2])[\/-][0-9]{4}$/", $string))
        return true;
    return false;
}

// Lấy dữ liệu từ form
$sm = postIndex("submit");
$username = postIndex("username");
$password = postIndex("password");
$email = postIndex("email");
$date = postIndex("date");
$phone = postIndex("phone");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab6_3 - Validation</title>
<style>
    body { font-family: Arial, sans-serif; }
    fieldset { width: 50%; margin: 50px auto; border: 1px solid #ccc; box-shadow: 2px 2px 5px #ccc; padding: 20px;}
    legend { font-weight: bold; color: #006; padding: 5px; }
    .info { width: 600px; color: red; background: #fff0f0; margin: 20px auto; padding: 10px; border: 1px solid red; }
    .success { width: 600px; color: green; background: #f0fff0; margin: 20px auto; padding: 10px; border: 1px solid green; }
    #frm1 input { width: 300px; padding: 5px; margin: 5px 0; }
    td { padding: 5px; }
</style>
</head>

<body>
<fieldset>
<legend>Đăng ký thông tin</legend>
<!-- Action để trống để submit về chính trang hiện tại -->
<form action="" method="post" enctype="multipart/form-data" id='frm1'>
<table align="center">
    <tr>
        <td width="100">UserName</td>
        <td><input type="text" name="username" value="<?php echo htmlspecialchars($username);?>"/> *</td>
    </tr>
    <tr>
        <td>Mật khẩu</td>
        <td><input type="password" name="password" value="<?php echo htmlspecialchars($password);?>"/> *</td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="text" name="email" value="<?php echo htmlspecialchars($email);?>"/> *</td>
    </tr>
    <tr>
        <td>Ngày sinh</td>
        <td><input type="text" name="date" value="<?php echo htmlspecialchars($date);?>" placeholder="dd/mm/yyyy" /> *</td>
    </tr>
    <tr>
        <td>Điện thoại</td>
        <td><input type="text" name="phone" value="<?php echo htmlspecialchars($phone);?>" /> *</td>
    </tr>
      
    <tr><td colspan="2" align="center"><input type="submit" value="Đăng ký" name="submit"></td></tr>
</table>
</form>
</fieldset>

<?php
if ($sm != "")
{
    $errors = "";

    // Validate Username
    if (checkUserName($username) == false) 
        $errors .= "- Username: Các ký tự được phép: a-z, A-Z, số 0-9, ký tự ., _ và - <br>";
    
    // Validate Email
    if (checkEmail($email) == false) 
        $errors .= "- Email: Định dạng email sai!<br>";

    // Validate Password
    if (checkPassword($password) == false)
        $errors .= "- Mật khẩu: Phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số.<br>";
    
    // Validate Phone
    if (checkPhone($phone) == false)
        $errors .= "- Điện thoại: Chỉ được nhập số.<br>";
    
    // Validate Date
    if (checkUserDate($date) == false)
        $errors .= "- Ngày sinh: Sai định dạng (yêu cầu: dd/mm/yyyy hoặc dd-mm-yyyy).<br>";

    // Hiển thị kết quả
    if ($errors != "") {
        echo "<div class='info'><b>Có lỗi xảy ra:</b><br />$errors</div>";
    } else {
        echo "<div class='success'><b>Đăng ký thành công!</b><br />";
        echo "Xin chào, $username ($email)</div>";
    }
}
?>
</body>
</html>