<?php
// Tên file CSV cần đọc
$filename = '65HTTT_Danh_sach_diem_danh.csv';
$data = [];

// Mở file CSV để đọc
if (($handle = fopen($filename, "r")) !== FALSE) {
    // Đọc dòng đầu tiên (Tiêu đề cột)
    $headers = fgetcsv($handle, 1000, ",");
    
    // Đọc các dòng dữ liệu còn lại
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $data[] = array_combine($headers, $row);
    }
    fclose($handle);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sinh Viên (CSV)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">📂 Danh Sách Tài Khoản Sinh Viên</h2>
        
        <?php if (!empty($data)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <?php foreach ($headers as $header): ?>
                                <th><?php echo htmlspecialchars($header); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $student): ?>
                            <tr>
                                <?php foreach ($student as $cell): ?>
                                    <td><?php echo htmlspecialchars($cell); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info mt-3">
                Tổng số sinh viên: <strong><?php echo count($data); ?></strong>
            </div>

        <?php else: ?>
            <div class="alert alert-warning text-center">
                Không tìm thấy file CSV hoặc file rỗng! Vui lòng kiểm tra lại file <code><?php echo $filename; ?></code>.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>