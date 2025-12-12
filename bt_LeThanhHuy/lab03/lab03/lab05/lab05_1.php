<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
    <div>
        <p>Nhap n so nguyen to: </p>
        <input type="text" name="n_value" id="snt">
        <button type="submit" name="submit_btn">Send</button>
    </div>
    </form>
<?php
    function checkSNT($n)
    {
        $count=0;
        for($i=1;$i<=$n;$i++)
        {
            if($n%$i==0)
            {
                $count++;
            }
        }
        if($count===2)
        return true;
        else return false;
    }
    if(isset($_POST['submit_btn']))
    {
        $n = $_POST['n_value'];
        $n = (int)$n;
    }
    $count_primes = 0;   
    $current_number = 2; 
    while ($count_primes < $n) {
        if (checkSNT($current_number)) {
            echo "So nguyen to: ".$current_number . "<br>"; 
            $count_primes++;             
        }
        $current_number++; 
    }

?>
</body>
</html>