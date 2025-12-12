<?php
// Hàm lấy giá trị từ POST
function postIndex($index, $value="")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}

// --- DỮ LIỆU FORM SCRAPING ---
$action = postIndex("action"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 6_3: Web Scraping (VnExpress)</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; line-height: 1.6; }
    
    /* Style chung cho Fieldset */
    fieldset { 
        width: 80%; 
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
    
    /* Table Styles */
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    td, th { padding: 10px; border: 1px solid #ddd; }
    th { background-color: #006; color: white; text-align: left; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    
    input[type="submit"] {
        padding: 10px 25px;
        cursor: pointer;
        background: #006;
        color: #fff;
        border: none;
        font-weight: bold;
        font-size: 14px;
        border-radius: 4px;
    }
    input[type="submit"]:hover { background: #004080; }
    
    /* Khu vực hiển thị kết quả */
    .info { 
        width: 80%; 
        margin: 10px auto; 
        padding: 15px; 
        background: #fff; 
        border: 1px solid #ccc; 
        border-top: 3px solid #006;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Style cho các thông báo */
    .warning { color: red; font-weight: bold; background: #ffe6e6; padding: 10px; border: 1px solid red; }
    .debug { background: #333; color: #0f0; padding: 10px; overflow: auto; height: 150px; margin-bottom: 20px; font-size: 12px; font-family: monospace; }
    
    a { text-decoration: none; color: #006; }
    a:hover { text-decoration: underline; }
</style>
</head>

<body>

<!-- ======================================================================= -->
<!-- WEB SCRAPING (VNEXPRESS) -->
<!-- ======================================================================= -->
<form action="" method="post">
<input type="hidden" name="action" value="scraping">
<fieldset>
    <legend>Lab 6_3: Web Scraping</legend>
    <p>Nhấn nút bên dưới để lấy danh sách tin tức mới nhất từ <b>VnExpress Thể Thao</b>.</p>
    <p><i>(Hệ thống sẽ tự động lọc bỏ các mã script rác nếu có)</i></p>
    <div align="center">
        <input type="submit" value="Lấy tin tức ngay">
    </div>
</fieldset>
</form>

<?php
if ($action == "scraping") {
    echo "<div class='info'>";
    echo "<h3>KẾT QUẢ QUÉT TIN TỨC:</h3>";

    // 1. Kiểm tra allow_url_fopen
    if (!ini_get('allow_url_fopen')) {
        echo "<p class='warning'>Cảnh báo: 'allow_url_fopen' đang tắt trong php.ini. Bạn cần bật nó lên để script hoạt động.</p>";
    } else {
        // 2. Cấu hình
        $url = "https://vnexpress.net/the-thao"; 
        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36\r\n"
            )
        );
        $context = stream_context_create($options);

        // 3. Lấy nội dung
        $content = @file_get_contents($url, false, $context);

        if ($content === FALSE) {
            echo "<p class='warning'>Không thể kết nối lấy dữ liệu từ URL: $url</p>";
        } else {
            echo "<p>Đã kết nối thành công tới: <a href='$url' target='_blank'>$url</a></p>";

            // 4. Regex xử lý (Lấy href và title trong thẻ a nằm trong h3.title-news)
            $pattern = '/<h3 class="title-news">.*?<a[^>]*href="([^"]+)"[^>]*title="([^"]+)"/ims';        
            preg_match_all($pattern, $content, $matches);

            // In dữ liệu thô (Debug) - Có thể comment dòng này nếu không muốn hiện code thô
            echo "<b>Dữ liệu thô (Debug regex):</b>";
            echo "<div class='debug'><pre>";
            if(isset($matches[0])) print_r(array_slice($matches, 0, 3)); 
            echo "... (ẩn bớt)";
            echo "</pre></div>";

            // 5. Hiển thị bảng
            ?>
            <h3>Danh sách tin mới nhất</h3>
            <table width="100%">
                <tr>
                    <th width="5%">STT</th>
                    <th width="70%">Tiêu đề tin</th>
                    <th width="25%">Link gốc</th>
                </tr>
                <?php
                if (isset($matches[1]) && count($matches[1]) > 0) {
                    $stt = 0;
                    for ($i = 0; $i < count($matches[1]); $i++) {
                        $link = trim($matches[1][$i]);
                        $title = trim(strip_tags($matches[2][$i])); 

                        // --- LỌC LỖI JS/RÁC ---
                        // Bỏ qua nếu tiêu đề chứa code, rỗng, hoặc icon living
                        if (strpos($title, 'articleData') !== false || $title == "" || strpos($title, 'living_icon') !== false) {
                            continue; 
                        }
                        
                        $stt++;
                        echo "<tr>";
                        echo "<td align='center'>" . $stt . "</td>";
                        echo "<td><strong>$title</strong></td>";
                        echo "<td><a href='$link' target='_blank'>Xem bài viết</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' align='center'>Không tìm thấy tin nào hoặc cấu trúc web thay đổi.</td></tr>";
                }
                ?>
            </table>
            <?php
        }
    }
    echo "</div>";
}
?>

</body>
</html>