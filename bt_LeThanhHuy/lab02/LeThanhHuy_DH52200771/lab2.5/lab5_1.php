<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <form action="" method="post">
      <div style="display: flex">
        <p>A: <input type="text" name="A" id="A" /></p>
      </div>
      <div style="display: flex">
        <p>B: <input type="text" name="B" id="B" /></p>
      </div>
      <div style="position: relative; margin-left: 70px">
        <input type="submit" value="Send" name="send" />
      </div>
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['send']))
        {
          $a = isset($_POST['A'])?(int)$_POST['A']:0;
          $b = isset($_POST['B'])?(int)$_POST['B']:0;
          
          if($a==0||$b==0)
          {
            echo "Cần nhập giá trị cho A và B";
          }
          else
          {
            {
              $nguyen=intdiv($a,$b);
              $du = $a % $b;
              echo "<h2>Kết quả của phép chia $a/$b:</h2>";
            echo "<p>Phần nguyên: <strong>$nguyen</strong></p>";
            echo "<p>Phần dư: <strong>$du</strong></p>";
            } 
          }
       }
    ?>
  </body>
</html>
