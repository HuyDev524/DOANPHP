<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quản lý loại sách - Lab 8.3</title>
    <style>
        #container { width: 600px; margin: 0 auto; font-family: sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; }
        .btn-edit { color: blue; margin-right: 10px; }
        .btn-del { color: red; }
        .msg { color: green; font-weight: bold; margin: 10px 0; }
        .err { color: red; font-weight: bold; margin: 10px 0; }
    </style>
</head>

<body>
    <div class="database_connector">
        <?php
            // KẾT NỐI DATABASE //
            try{
                $pdh = new PDO("mysql:host=localhost; dbname=bookstore","root","");
                $pdh->query("set names 'utf8'");
                // QUAN TRỌNG: Thêm dòng này để bắt lỗi trùng khóa chính (1062)
                $pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(Exception $ex)
            {
                echo "Lỗi kết nối: ".$ex->getMessage();
                exit;
            }

            $msg = "";
            $cur_id = "";
            $cur_name = "";

            // XOÁ //
            if(isset($_GET['del']))
            {
                $id_del = $_GET['del'];
                $querry = "DELETE FROM category WHERE cat_id = :id";
                $stm = $pdh->prepare($querry);
                $stm->execute([':id'=>$id_del]);
                $msg = "Đã xoá loại sách mã: $id_del";
            }

            // THÊM //
            if(isset($_POST['sm_insert']))
            {
                try
                {
                    $sql = "INSERT INTO category(cat_id, cat_name) values(:cat_id, :cat_name)";
                    $arr = array(":cat_id" => $_POST["cat_id"], ":cat_name" => $_POST["cat_name"]);
                    $stm = $pdh->prepare($sql); // Sửa lỗi cú pháp dòng này
                    $stm->execute($arr);
                    $msg = "Thêm mới thành công!";
                }
                catch(PDOException $e) // Bắt đúng lỗi PDOException
                {
                    if ($e->errorInfo[1] == 1062) $msg = "<span class='err'>Lỗi: Mã sách đã tồn tại!</span>";
                    else $msg = "<span class='err'>Lỗi thêm: " . $e->getMessage() . "</span>";
                }
            }

            // SỬA //
            if(isset($_POST['sm_update']))
            {  
                $sql = "UPDATE category SET cat_name = :cat_name WHERE cat_id = :cat_id";
                $arr = array(":cat_id" => $_POST["cat_id"], ":cat_name" => $_POST["cat_name"]);
                $stm = $pdh->prepare($sql);
                $stm->execute($arr);
                $msg = "Sửa thành công";
            }

            // LẤY DỮ LIỆU ĐỂ SỬA //
            if(isset($_GET['edit']) && !isset($_POST['sm_update'])) 
            {
                $stm = $pdh->prepare("SELECT * FROM category WHERE cat_id = :id");
                $stm->execute([':id' => $_GET['edit']]);
                $row = $stm->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $cur_id = $row['cat_id'];
                    $cur_name = $row['cat_name'];
                }
            }
        ?>
    </div>

    <div id="container">
        <h3>QUẢN LÝ LOẠI SÁCH</h3>
        
        <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>
        
        <form action="" method="post">
            <table>
                <tr>
                    <td>Mã loại:</td>
                    <td>
                        <input type="text" name="cat_id" value="<?php echo $cur_id;?>" 
                        <?php if($cur_id != "") echo "readonly style='background-color:#eee'"; ?> >                   
                    </td>
                </tr>
                <tr>
                    <td>Tên loại:</td>
                    <td>
                        <input type="text" name="cat_name" value="<?php echo $cur_name;?>" required>                    
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <?php if ($cur_id == ""): ?>
                            <input type="submit" name="sm_insert" value="Thêm mới" />
                        <?php else: ?>
                            <input type="submit" name="sm_update" value="Cập nhật" />
                            <a href="lab8_3.php"><button type="button">Hủy</button></a>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </form>
        <hr>
        
        <?php
            $stm = $pdh->query("SELECT * FROM category");
            $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <table>
            <tr>
                <th>Mã</th>
                <th>Tên loại</th>
                <th>Hành động</th>
            </tr>
            <?php foreach($rows as $row): ?>
            <tr>
                <td><?php echo $row['cat_id']; ?></td>
                <td><?php echo $row['cat_name']; ?></td>
                <td align="center">
                    <a class="btn-edit" href="?edit=<?php echo $row['cat_id']; ?>">Sửa</a> | 
                    <a class="btn-del" href="?del=<?php echo $row['cat_id']; ?>" onclick="return confirm('Chắc chắn xóa?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>