<?php
require_once '../db.php'; // Kết nối CSDL

// --- XỬ LÝ UPLOAD ---
$msg = "";
if (isset($_POST['upload'])) {
    if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
        $file = $_FILES['file_csv']['tmp_name'];
        if (($handle = fopen($file, "r")) !== FALSE) {
            fgetcsv($handle); // Bỏ qua dòng tiêu đề
            
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // 1. Kiểm tra số cột (phải đủ 7 cột)
                if(count($row) < 7) continue; 

                // 2. QUAN TRỌNG: Kiểm tra username có bị rỗng không?
                $username = trim($row[0]);
                if (empty($username)) continue; // Nếu rỗng thì bỏ qua dòng này ngay

                try {
                    $sql = "INSERT INTO students (username, password, lastname, firstname, city, email, course1) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$username, $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]]);
                } catch(Exception $e) { 
                    // Nếu lỗi (ví dụ trùng mã sinh viên) thì bỏ qua, không báo lỗi đỏ lòm
                }
            }
            fclose($handle);
            $msg = "<div class='alert alert-success'>✅ Đã nạp dữ liệu thành công!</div>";
        }
    }
}

// --- LẤY DỮ LIỆU ---
// Thêm kiểm tra bảng tồn tại để tránh lỗi nếu chưa tạo bảng
try {
    $students = $conn->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $students = [];
    $msg = "<div class='alert alert-danger'>Lỗi: Bảng 'students' chưa được tạo hoặc bị lỗi.</div>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 3: Sinh viên (MySQL)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-primary text-center">Danh Sách Sinh Viên (Từ CSDL)</h2>
    
    <div class="card p-3 my-3 bg-light">
        <form method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            <input type="file" name="file_csv" class="form-control" required>
            <button type="submit" name="upload" class="btn btn-success">Upload CSV</button>
        </form>
        <?php echo $msg; ?>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>ID</th><th>Họ Tên</th><th>User</th><th>Email</th><th>Lớp</th></tr>
        </thead>
        <tbody>
            <?php foreach ($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= $s['lastname'] . ' ' . $s['firstname'] ?></td>
                <td><?= $s['username'] ?></td>
                <td><?= $s['email'] ?></td>
                <td><?= $s['city'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>