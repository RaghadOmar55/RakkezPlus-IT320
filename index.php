<?php 
  include 'db_connection.php'; 
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deep Focus Zone - Rakkez+</title>
    <style>
        /* ===== إعدادات الصفحة الأساسية  ===== */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #F5F5F5;
            color: #2E2E2E;
            overflow-x: hidden;
        }

        body { display: flex; flex-direction: column; }

        .notification-menu-btn {
            position: fixed; top: 15px; left: 20px; font-size: 24px;
            background: none; border: none; cursor: pointer; z-index: 1300;
        }

        .home-navbar {
            background-color: white; padding: 15px 80px;
            display: flex; justify-content: space-between; align-items: center;
            position: relative; z-index: 900;
        }
        
        .home-nav-links { display: flex; gap: 15px; }
        .home-nav-links a { text-decoration: none; color: #2E2E2E; font-weight: bold; }
        .home-nav-links a:hover { color: #3A78A1; }

        /* ===== SIDEBAR ===== */
        .notification-sidebar {
            position: fixed; top: 0; left: -280px; width: 260px; height: 100vh;
            background: white; box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            transition: 0.3s; z-index: 2100; display: flex; flex-direction: column;
        }
        .notification-sidebar.active { left: 0; }
        .notification-sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid #eee; }
        .notification-sidebar-header img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; }
        .notification-sidebar-links { padding: 20px; display: flex; flex-direction: column; gap: 10px; }
        .notification-sidebar-links a { text-decoration: none; color: #2E2E2E; padding: 10px; border-radius: 6px; font-weight: 500; }
        .notification-sidebar-links a:hover { background: #F5F5F5; color: #3A78A1; }
        .notification-logout-btn { width: 90%; margin: 20px auto; padding: 10px; background: #F28C28; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .notification-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0,0,0,0.3); display: none; z-index: 2050; }
        .notification-overlay.active { display: block; }

        /* ===== المحتوى الأساسي ===== */
        .main-content { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .full-width-container {
            width: 100%; max-width: 800px; background: white; padding: 40px;
            text-align: center; border-top: 6px solid #3A78A1; box-sizing: border-box;
            border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .setup-section { margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 10px; max-width: 500px; margin: 0 auto 20px auto; }
        select, .btn { padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin: 5px; font-weight: bold; }
        
        .timer-wrapper { position: relative; width: 220px; height: 220px; margin: 30px auto; }
        .progress-ring__circle { transition: stroke-dashoffset 1s linear; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .timer-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 45px; font-weight: bold; }
        
        .btn { color: white; cursor: pointer; border: none; min-width: 100px; transition: 0.3s; }
        .btn-start { background: #3A78A1; }
        .btn-pause { background: #F28C28; }
        .btn-resume { background: #2ecc71; display: none; }
        .btn-end { background: #e74c3c; }

        #interruption-modal { display: none; margin-top: 20px; padding: 15px; border: 2px solid #F28C28; border-radius: 10px; background: #fffcf5; max-width: 400px; margin: 20px auto; }
        .footer { background: white; text-align: center; padding: 15px; box-shadow: 0 -2px 5px rgba(0,0,0,0.05); font-size: 14px; }
    </style>
</head>
<body>
    <div class="home-navbar">
        <div class="home-logo"><a href="index.php"><img src="logo.jpeg" width="100" alt="Logo"></a></div>
        <div class="home-nav-links">
            <a href="index.php">Main</a>
            <a href="login.html">Log Out</a>
        </div>
    </div>
    
    <button class="notification-menu-btn" onclick="toggleMenu()">☰</button>

    <div id="notification-sidebar" class="notification-sidebar">
        <div class="notification-sidebar-header">
            <img src="profile.jpg" alt="Profile">
            <h3>Raghad</h3>
        </div>
        <div class="notification-sidebar-links">
            <a href="profile.html">Profile</a>
            <a href="notifications.html">Notifications</a>
            <a href="tips.html">Tips</a>
            <a href="support.html">Support</a>
        </div>
        <div class="notification-sidebar-footer"><button class="notification-logout-btn">Log Out</button></div>
    </div>
    <div id="notification-overlay" class="notification-overlay" onclick="toggleMenu()"></div>

    <div class="main-content">
        <div class="full-width-container">
            <h2 id="status-text" style="color: #3A78A1; margin-bottom: 5px;">Deep Focus Zone</h2>
            <p style="color: #7FAEC6; font-size: 14px; margin-bottom: 30px;">Eliminate distractions, achieve more.</p>
            
            <div id="setup-area" class="setup-section">
                <label>Duration: </label>
                <select id="duration-select">
                    <option value="25">25 Minutes</option>
                    <option value="50">50 Minutes</option>
                    <option value="75">75 Minutes</option>
                    <option value="90">90 Minutes</option>
                </select>
                <br><br>
                <input type="checkbox" id="break-checkbox"> <label>Enable 5-min breaks every 25 mins</label>
            </div>

            <div class="timer-wrapper">
                <svg width="220" height="220">
                    <circle stroke="#e0e0e0" stroke-width="8" fill="transparent" r="100" cx="110" cy="110"/>
                    <circle id="progress-circle" class="progress-ring__circle" stroke="#3A78A1" stroke-width="8" fill="transparent" r="100" cx="110" cy="110"/>
                </svg>
                <div class="timer-text"><span id="timer-display">25:00</span></div>
            </div>

            <div class="controls">
                <button id="start-btn" class="btn btn-start">Start</button>
                <button id="pause-btn" class="btn btn-pause" style="display:none;">Pause</button>
                <button id="resume-btn" class="btn btn-resume">Resume</button>
                <button id="end-btn" class="btn btn-end">End</button>
            </div>

            <div id="interruption-modal">
                <h4>Why did you pause? (Required)</h4>
                <select id="reason-select">
                    <option value="">-- Select Reason --</option>
                    <option value="Phone">Mobile Phone / Social Media</option>
                    <option value="People">Family / Friends</option>
                    <option value="Noise">Environmental Noise</option>
                    <option value="Hunger">Hunger / Thirst</option>
                    <option value="Fatigue">Fatigue</option>
                </select>
                <button class="btn btn-start" onclick="submitReason()">OK</button>
            </div>
        </div>
    </div>

    <div class="footer"><p>© Rakkez+.. Helping students build better study habits</p></div>

    <script>
        function toggleMenu() {
            document.getElementById("notification-sidebar").classList.toggle("active");
            document.getElementById("notification-overlay").classList.toggle("active");
        }

        let timerInterval, totalSec, remainingSec, isBreak = false;
        const display = document.getElementById('timer-display');
        const circle = document.getElementById('progress-circle');
        const setupArea = document.getElementById('setup-area');
        const circ = 2 * Math.PI * 100;
        circle.style.strokeDasharray = circ;

        function updateUI() {
            let m = Math.floor(remainingSec / 60);
            let s = remainingSec % 60;
            display.innerText = `${m}:${s < 10 ? '0' + s : s}`;
            circle.style.strokeDashoffset = circ - (remainingSec / totalSec) * circ;
        }

        document.getElementById('start-btn').onclick = function() {
            let mins = parseInt(document.getElementById('duration-select').value);
            totalSec = mins * 60;
            remainingSec = totalSec;
            startTimer();
            this.style.display = 'none';
            document.getElementById('pause-btn').style.display = 'inline-block';
            setupArea.style.pointerEvents = 'none';
            setupArea.style.opacity = '0.5';
        };

        function startTimer() {
            timerInterval = setInterval(() => {
                remainingSec--;
                updateUI();
                
                if (document.getElementById('break-checkbox').checked && !isBreak && (totalSec - remainingSec) % 1500 === 0 && remainingSec > 0) {
                    pauseTimer();
                    alert("Time for a 5-minute break!");
                    startBreak();
                }

                if (remainingSec <= 0) {
                    clearInterval(timerInterval);
                    alert(isBreak ? "Break over!" : "Session Finished!");
                    endSession();
                }
            }, 1000);
        }

        function pauseTimer() {
            clearInterval(timerInterval);
            document.getElementById('pause-btn').style.display = 'none';
            document.getElementById('resume-btn').style.display = 'inline-block';
        }

        document.getElementById('pause-btn').onclick = pauseTimer;

        document.getElementById('resume-btn').onclick = function() {
            document.getElementById('interruption-modal').style.display = 'block';
        };

        function submitReason() {
            let reason = document.getElementById('reason-select').value;
            if (reason === "") {
                alert("Please select a reason (US5 requirement)");
                return;
            }

            fetch('save_data.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=save_interruption&reason=${encodeURIComponent(reason)}`
            });

            document.getElementById('interruption-modal').style.display = 'none';
            document.getElementById('resume-btn').style.display = 'none';
            document.getElementById('pause-btn').style.display = 'inline-block';
            startTimer();
        }

        function endSession() {
            let durationPlayed = Math.round((totalSec - remainingSec) / 60);
            let breakPref = document.getElementById('break-checkbox').checked ? 1 : 0;

            if(durationPlayed > 0) {
                fetch('save_data.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=save_session&duration=${durationPlayed}&break_pref=${breakPref}`
                });
            }

            clearInterval(timerInterval);
            remainingSec = 25 * 60;
            totalSec = 25 * 60;
            updateUI();
            document.getElementById('start-btn').style.display = 'inline-block';
            document.getElementById('pause-btn').style.display = 'none';
            document.getElementById('resume-btn').style.display = 'none';
            setupArea.style.pointerEvents = 'auto';
            setupArea.style.opacity = '1';
            document.getElementById('status-text').innerText = "Deep Focus Zone";
            isBreak = false;
        }

        document.getElementById('end-btn').onclick = endSession;

        function startBreak() {
            isBreak = true;
            remainingSec = 5 * 60;
            totalSec = 5 * 60;
            document.getElementById('status-text').innerText = "Break Time! ☕";
            startTimer();
        }
    </script>
</body>
</html>