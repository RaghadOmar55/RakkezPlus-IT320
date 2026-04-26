<?php
$servername = "localhost";
$username = "root";
$password = "root"; 
$dbname = "rakkez_db";
$port = 8889; // بورت MySQL الافتراضي في MAMP ويندوز

// محاولة الاتصال مع كتم الخطأ مؤقتاً لتجنب الـ 500
$conn = @new mysqli($servername, $username, $password, $dbname, $port);

// إذا فشل الاتصال بيطبع لنا السبب بدل ما ينهار السيرفر
if ($conn->connect_error) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $conn->connect_error . 
        "<br>تأكدي من اسم الداتابيس والباسورد في MAMP.");
}

$conn->set_charset("utf8mb4");
?>