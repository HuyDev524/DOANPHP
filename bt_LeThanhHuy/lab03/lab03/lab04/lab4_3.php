<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 4_3</title>
</head>

<body>
<?php
//Kết hợp hàm và vòng lặp
function kiemtranguyento($x)//Kiểm tra 1 số có nguyên tố hay không
{
    do {
        $i =2;
        if($x%$i==0)
        return false;
        $i++;
    } while ($i<=sqrt($x));
    return true;
}

if(kiemtranguyento(3))
	echo  "là số nguyên tố";
else
	echo "không phải số nguyên tố";

?>
</body>
</html>