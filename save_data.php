<?php
include 'db_connection.php';
session_start();

// الربط مع نظام جنى: نأخذ ID المستخدم الحقيقي اللي سجل دخول
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}
$user_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // 1. حفظ بيانات الجلسة (عند الضغط على Start أو End حسب منطق صفحتك)
    if ($action === 'save_session') {
        $duration = intval($_POST['duration']);
        $break_pref = intval($_POST['break_pref']);

        // نستخدم id لربطها باليوزر
        $sql = "INSERT INTO study_session (id, duration, break_preference) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $user_id, $duration, $break_pref);
        
        if ($stmt->execute()) {
            $_SESSION['current_session_id'] = $conn->insert_id; // حفظ رقم الجلسة للربط
            echo "Session saved successfully. ID: " . $_SESSION['current_session_id'];
        } else {
            echo "Database Error: " . $stmt->error;
        }
    }

    // 2. حفظ سبب الانقطاع (US5)
    if ($action === 'save_interruption') {
        $reason = $_POST['reason'];
        
        // إذا لسه ما فيه جلسة محفوظة، نحفظ جلسة "مؤقتة" عشان نربط فيها السبب
        if (!isset($_SESSION['current_session_id'])) {
            $sql_init = "INSERT INTO study_session (id, duration, break_preference) VALUES (?, 0, 0)";
            $stmt_init = $conn->prepare($sql_init);
            $stmt_init->bind_param("i", $user_id);
            $stmt_init->execute();
            $_SESSION['current_session_id'] = $conn->insert_id;
        }

        $session_id = $_SESSION['current_session_id'];

        $sql = "INSERT INTO interruption (session_id, reason) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $session_id, $reason);
        
        if ($stmt->execute()) {
            echo "Interruption reason saved for session: " . $session_id;
        } else {
            echo "Database Error: " . $stmt->error;
        }
    }
}
?>
