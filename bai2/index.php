<?php
// 1. CẤU HÌNH VÀ XỬ LÝ ĐỌC FILE
$filename = 'Quiz.txt';
$questions = [];

if (file_exists($filename)) {
    // Đọc file vào mảng, bỏ qua dòng trống
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    $current_question = [];
    $is_parsing_question = true;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // Nếu dòng bắt đầu bằng "ANSWER:" -> Kết thúc một câu hỏi
        if (strpos($line, 'ANSWER:') === 0) {
            $answer_str = substr($line, strpos($line, ':') + 1); // Lấy phần sau dấu :
            $current_question['answer'] = array_map('trim', explode(',', $answer_str)); // Tách đáp án thành mảng (ví dụ C, D)
            
            // Lưu câu hỏi vào danh sách
            if (!empty($current_question['question'])) {
                $questions[] = $current_question;
            }
            
            // Reset biến tạm để bắt đầu câu mới
            $current_question = [];
            $is_parsing_question = true;
        } 
        // Nếu dòng bắt đầu bằng A., B., C., D. -> Là các lựa chọn
        elseif (preg_match('/^[A-D]\./', $line)) {
            $is_parsing_question = false; // Đã vào phần đáp án, không còn là text câu hỏi
            $current_question['options'][] = $line;
        } 
        // Nếu không phải hai loại trên -> Là nội dung câu hỏi
        else {
            if ($is_parsing_question) {
                // Nối chuỗi nếu câu hỏi có nhiều dòng
                if (isset($current_question['question'])) {
                    $current_question['question'] .= "<br>" . $line;
                } else {
                    $current_question['question'] = $line;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Trắc Nghiệm Android</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-bottom: 50px; }
        .quiz-container { max-width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .question-text { font-weight: bold; color: #2c3e50; font-size: 1.1rem; }
        .form-check { margin-bottom: 8px; padding: 10px; border-radius: 5px; border: 1px solid #eee; transition: 0.2s; }
        .form-check:hover { background-color: #f1f1f1; }
        .correct-answer { background-color: #d4edda !important; border-color: #c3e6cb !important; color: #155724; }
        .wrong-answer { background-color: #f8d7da !important; border-color: #f5c6cb !important; color: #721c24; }
        .result-box { position: fixed; bottom: 20px; right: 20px; z-index: 1000; min-width: 200px; }
    </style>
</head>
<body>

<div class="container">
    <div class="quiz-container">
        <h2 class="text-center mb-4 text-primary">📝 Bài Thi Trắc Nghiệm Android</h2>
        <hr>

        <?php if (empty($questions)): ?>
            <div class="alert alert-danger">Không tìm thấy file câu hỏi hoặc file rỗng! Vui lòng kiểm tra lại <strong>Quiz.txt</strong>.</div>
        <?php else: ?>

            <form method="POST" action="">
                <?php foreach ($questions as $index => $q): ?>
                    <?php 
                        // Xác định xem câu này có nhiều đáp án đúng không
                        $is_multiple_choice = count($q['answer']) > 1;
                        $input_type = $is_multiple_choice ? 'checkbox' : 'radio';
                        $input_name = $is_multiple_choice ? "q{$index}[]" : "q{$index}";
                        
                        // Xử lý logic hiển thị kết quả sau khi nộp bài
                        $user_answer = $_POST["q$index"] ?? null;
                        if (!is_array($user_answer) && $user_answer !== null) $user_answer = [$user_answer]; // Chuẩn hóa về mảng
                    ?>

                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <p class="question-text">
                                Câu <?php echo $index + 1; ?>: <?php echo $q['question']; ?>
                                <?php if($is_multiple_choice): ?>
                                    <span class="badge bg-warning text-dark" style="font-size: 0.7em">(Chọn nhiều)</span>
                                <?php endif; ?>
                            </p>

                            <div class="options-list">
                                <?php foreach ($q['options'] as $opt): ?>
                                    <?php 
                                        $opt_key = substr($opt, 0, 1); // Lấy A, B, C, D
                                        
                                        // Kiểm tra trạng thái đúng sai để tô màu (chỉ hiện khi đã submit)
                                        $class_result = "";
                                        $checked = "";

                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            // Kiểm tra nếu người dùng đã chọn đáp án này
                                            if ($user_answer && in_array($opt_key, $user_answer)) {
                                                $checked = "checked";
                                                // Nếu chọn đúng -> xanh, chọn sai -> đỏ
                                                if (in_array($opt_key, $q['answer'])) {
                                                    $class_result = "correct-answer"; // Chọn đúng
                                                } else {
                                                    $class_result = "wrong-answer"; // Chọn sai
                                                }
                                            }
                                            // Luôn hiện đáp án đúng màu xanh để đối chiếu
                                            if (in_array($opt_key, $q['answer'])) {
                                                $class_result = "correct-answer"; 
                                            }
                                        }
                                    ?>
                                    
                                    <div class="form-check <?php echo $class_result; ?>">
                                        <input class="form-check-input" type="<?php echo $input_type; ?>" 
                                               name="<?php echo $input_name; ?>" 
                                               value="<?php echo $opt_key; ?>" 
                                               id="q<?php echo $index; ?>_<?php echo $opt_key; ?>"
                                               <?php echo $checked; ?>>
                                        <label class="form-check-label w-100 cursor-pointer" for="q<?php echo $index; ?>_<?php echo $opt_key; ?>">
                                            <?php echo $opt; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-grid gap-2 col-6 mx-auto mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">🚀 Nộp Bài & Xem Kết Quả</button>
                    <a href="index.php" class="btn btn-outline-secondary">Làm lại</a>
                </div>
            </form>

            <?php 
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $score = 0;
                $total = count($questions);
                foreach ($questions as $i => $qu) {
                    $u_ans = $_POST["q$i"] ?? [];
                    if (!is_array($u_ans)) $u_ans = [$u_ans];
                    
                    // So sánh mảng đáp án người dùng chọn với đáp án đúng
                    // (Sắp xếp lại để so sánh chính xác không phụ thuộc thứ tự)
                    sort($u_ans);
                    sort($qu['answer']);
                    
                    if ($u_ans == $qu['answer']) {
                        $score++;
                    }
                }
                echo "<div class='alert alert-success result-box shadow'>
                        <h4>Kết quả: $score / $total câu đúng</h4>
                      </div>";
            }
            ?>

        <?php endif; ?>
    </div>
</div>

</body>
</html>