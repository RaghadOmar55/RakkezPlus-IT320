<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['userID'])) {
    die("Error: User not logged in.");
}

$user_id = (int) $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'];

    if ($action === 'start_session') {

        $duration = intval($_POST['duration']);
        $break_pref = intval($_POST['break_pref']);

        $sql = "INSERT INTO study_session (id, duration, break_preference) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $user_id, $duration, $break_pref);

        if ($stmt->execute()) {
            $_SESSION['current_session_id'] = $conn->insert_id;
            echo "Session started";
        } else {
            echo "Database Error: " . $stmt->error;
        }
    }

    if ($action === 'save_interruption') {

        if (!isset($_SESSION['current_session_id'])) {
            die("Error: No active session.");
        }

        $session_id = $_SESSION['current_session_id'];
        $reason = $_POST['reason'];

        $sql = "INSERT INTO interruption (session_id, reason) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $session_id, $reason);

        if ($stmt->execute()) {
            echo "Interruption saved";
        } else {
            echo "Database Error: " . $stmt->error;
        }
    }

    if ($action === 'end_session') {

    if (!isset($_SESSION['current_session_id'])) {
        echo json_encode(["status" => "error", "message" => "No active session"]);
        exit();
    }

    $session_id = (int) $_SESSION['current_session_id'];
    $durationPlayed = intval($_POST['duration']);

    $update = "UPDATE study_session SET duration = ? WHERE session_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ii", $durationPlayed, $session_id);
    $stmt->execute();

    $check = "SELECT interruption_id, reason 
              FROM interruption 
              WHERE session_id = ? 
              ORDER BY interruption_id DESC 
              LIMIT 1";

    $stmtCheck = $conn->prepare($check);
    $stmtCheck->bind_param("i", $session_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($row = $result->fetch_assoc()) {

        $interruption_id = (int) $row['interruption_id'];
        $reason = $row['reason'];

        if ($reason == "Phone") {
            $tip_text = "Keep your phone away from your study area and turn on Do Not Disturb mode.";
            $message = "Your phone distracted you during this session. We added a focus tip to help you avoid phone distractions next time.";
        } elseif ($reason == "People") {
            $tip_text = "Tell people around you that you are in a focus session before you start studying.";
            $message = "People interrupted your focus session. We added a tip to help you create a better study environment.";
        } elseif ($reason == "Noise") {
            $tip_text = "Try using headphones or choosing a quieter place before starting your next session.";
            $message = "Noise affected your focus this time. We added a tip to help you study in a calmer place.";
        } elseif ($reason == "Hunger") {
            $tip_text = "Prepare water and a light snack before studying to avoid stopping your session.";
            $message = "Hunger or thirst interrupted your session. We added a tip to help you prepare before studying.";
        } elseif ($reason == "Fatigue") {
            $tip_text = "Take enough rest before studying and use short breaks when needed.";
            $message = "Fatigue interrupted your focus session. We added a tip to help you manage your energy better.";
        } else {
            $tip_text = "Notice what usually interrupts you and prepare for it before your next session.";
            $message = "We noticed an interruption in your session. We added a new focus tip for you.";
        }

        $tipSql = "INSERT INTO tip (tip_text, interruption_id) VALUES (?, ?)";
        $stmtTip = $conn->prepare($tipSql);
        $stmtTip->bind_param("si", $tip_text, $interruption_id);
        $stmtTip->execute();

    } else {
        $message = "Great job! You completed your focus session without any interruptions. Keep going!";
    }

    $notiSql = "INSERT INTO notification (id, message, date) VALUES (?, ?, NOW())";
    $stmtNoti = $conn->prepare($notiSql);
    $stmtNoti->bind_param("is", $user_id, $message);

    if ($stmtNoti->execute()) {
        unset($_SESSION['current_session_id']);

        echo json_encode([
            "status" => "new_notification",
            "message" => $message
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Notification was not saved"
        ]);
    }

    exit();
}
}
?>