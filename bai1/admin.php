<?php
require_once 'data.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hoa (Admin)</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd; 
            padding: 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2; 
            color: #333;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; 
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            text-decoration: none;
            margin-right: 10px;
        }
        .edit-btn { color: #007bff; }
        .delete-btn { color: #dc3545; }
    </style>
</head>
<body>

    <h1>⚙️ Quản Lý Danh Sách Hoa</h1>
    
    <a href="#" class="btn-add">+ Thêm hoa mới</a>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">STT</th>
                <th>Tên Hoa</th>
                <th>Mô Tả</th>
                <th style="width: 100px;">Ảnh</th>
                <th style="width: 100px;">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($flowers as $index => $flower): ?>
            <tr>
                <td style="text-align: center;"><?php echo $index + 1; ?></td>
                
                <td><strong><?php echo $flower['ten_hoa']; ?></strong></td>
                
                <td><?php echo $flower['mo_ta']; ?></td>
                
                <td style="text-align: center;">
                    <img src="<?php echo $flower['anh']; ?>" style="width: 80px; height: 80px; object-fit: cover;">
                </td>
                
                <td style="text-align: center;">
                    <a href="#" class="action-btn edit-btn">✏️ Sửa</a>
                    <a href="#" class="action-btn delete-btn">🗑️ Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>