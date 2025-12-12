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
        <input type="submit" value="Send" name="send" />
      </div>
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['send']))
        {
          $a = isset($_POST['A'])?$_POST['A']+0:0;
            if($a==0)
            {
                echo "Nhập a";
            } 
                if(is_float($a))
                    {
                     echo "$a là số thực";
                    }
                else 
                 {
                     echo "$a là số nguyên";
                 }
         }
    ?>
  </body>
</html>
