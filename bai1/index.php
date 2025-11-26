<?php

require_once 'data.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Hoa (Khách)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .flower-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
       
        .flower-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 300px; 
            transition: transform 0.3s;
        }
        .flower-card:hover {
            transform: translateY(-5px); 
        }
        .flower-card img {
            width: 100%;
            height: 200px; 
            object-fit: cover; 
        }
        .flower-content {
            padding: 15px;
        }
        .flower-content h2 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 1.25rem;
        }
        .flower-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <h1>🌸 Bộ Sưu Tập Hoa Xuân Hè</h1>

    <div class="flower-container">
        <?php if(isset($flowers) && is_array($flowers)): ?>
            <?php foreach ($flowers as $flower): ?>
                <div class="flower-card">
                    <img src="<?php echo $flower['anh']; ?>" alt="<?php echo $flower['ten_hoa']; ?>">
                    <div class="flower-content">
                        <h2><?php echo $flower['ten_hoa']; ?></h2>
                        <p><?php echo $flower['mo_ta']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: red; text-align: center;">Chưa có dữ liệu hoa. Vui lòng kiểm tra file data.php!</p>
        <?php endif; ?>
    </div>

</body>
</html>