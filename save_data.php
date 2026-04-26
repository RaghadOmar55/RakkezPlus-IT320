<?php
include 'db_connection.php';
session_start();

// نستخدم اليوزر ID رقم 1 مؤقتاً للتجربة، وبعدين نربطه بنظام الدخول الحقيقي
$user_id = 1; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // 1. حفظ بيانات الجلسة (US4)
    if ($action === 'save_session') {
        $duration = intval($_POST['duration']);
        $break_pref = intval($_POST['break_pref']);

        $sql = "INSERT INTO study_session (id, duration, break_preference) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $user_id, $duration, $break_pref);
        
        if ($stmt->execute()) {
            echo "Session saved successfully";
            // نحفظ رقم الجلسة الحالية عشان نربط فيها أسباب الانقطاع
            $_SESSION['current_session_id'] = $conn->insert_id;
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // 2. حفظ سبب الانقطاع (US5)
    if ($action === 'save_interruption') {
        $reason = $_POST['reason'];
        // نأخذ رقم الجلسة اللي لسه فاتحينها
        $session_id = $_SESSION['current_session_id'] ?? 0;

        if ($session_id > 0) {
            $sql = "INSERT INTO interruption (session_id, reason) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $session_id, $reason);
            
            if ($stmt->execute()) {
                echo "Interruption reason saved";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>