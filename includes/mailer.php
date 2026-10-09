<?php
// includes/mailer.php - Automated Email Notification Engine for StudyAbroad360

if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

/**
 * Send an HTML Application & Document Deadline Reminder Email to a Student
 *
 * @param string $toEmail Recipient Gmail/Email
 * @param string $studentName Recipient Name
 * @param string $programTitle Program Name (e.g. M.Sc. Data Analytics)
 * @param string $universityName University Name
 * @param string $intervalLabel Countdown Trigger (e.g. "30 Days Left", "5 Hours Left")
 * @param string $deadlineDate Formatted Deadline Date String
 * @return bool True if queued/sent successfully
 */
function sendDeadlineEmail($toEmail, $studentName, $programTitle, $universityName, $intervalLabel, $deadlineDate) {
    $subject = "⏰ Urgent Reminder: $intervalLabel for $programTitle Application!";
    
    // Determine badge urgency color
    $isUrgent = (strpos($intervalLabel, 'Hour') !== false || strpos($intervalLabel, '1 Day') !== false);
    $badgeBg = $isUrgent ? '#e11d48' : '#0284c7';
    $headerBg = $isUrgent ? 'linear-gradient(135deg, #e11d48 0%, #9f1239 100%)' : 'linear-gradient(135deg, #0284c7 0%, #1e3a8a 100%)';

    $htmlContent = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>$subject</title>
        <style>
            body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
            .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
            .header { background: $headerBg; color: #ffffff; padding: 32px 24px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
            .header p { margin: 8px 0 0 0; opacity: 0.9; font-size: 14px; }
            .badge { display: inline-block; background: $badgeBg; color: #ffffff; padding: 6px 14px; border-radius: 50px; font-weight: 700; font-size: 13px; text-transform: uppercase; margin-top: 12px; }
            .body-content { padding: 32px 28px; }
            .info-card { background: #f1f5f9; border-left: 4px solid $badgeBg; border-radius: 8px; padding: 18px 20px; margin: 20px 0; }
            .info-item { margin-bottom: 8px; font-size: 15px; }
            .info-item strong { color: #0f172a; }
            .btn { display: inline-block; background: #0284c7; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: 700; font-size: 15px; margin-top: 20px; text-align: center; }
            .footer { background: #f8fafc; text-align: center; padding: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h1>🎓 StudyAbroad360 Deadline Reminder</h1>
                <p>Don't miss your application deadline!</p>
                <div class='badge'>⏳ $intervalLabel</div>
            </div>
            
            <div class='body-content'>
                <p>Dear <strong>" . htmlspecialchars($studentName) . "</strong>,</p>
                
                <p>This is an automated reminder regarding your upcoming university application and document submission deadline.</p>
                
                <div class='info-card'>
                    <div class='info-item'><strong>🏛️ University:</strong> " . htmlspecialchars($universityName) . "</div>
                    <div class='info-item'><strong>📖 Program:</strong> " . htmlspecialchars($programTitle) . "</div>
                    <div class='info-item'><strong>📅 Submission Deadline:</strong> " . htmlspecialchars($deadlineDate) . "</div>
                    <div class='info-item'><strong>⏳ Time Remaining:</strong> <span style='color: $badgeBg; font-weight: bold;'>$intervalLabel</span></div>
                </div>

                <p>Please log in to your student dashboard to verify your required documents (Transcripts, IELTS, SOP, LORs) and submit your final application on time.</p>
                
                <div style='text-align: center;'>
                    <a href='http://localhost/UIUDHHACKATHON/student_dashboard.php' class='btn'>Complete Application Now →</a>
                </div>
            </div>

            <div class='footer'>
                <p>Sent automatically by <strong>StudyAbroad360 Notification Engine</strong>.</p>
                <p>You can manage your reminder channel preferences in your Student Dashboard.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Set Mail Headers
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: StudyAbroad360 <noreply@studyabroad360.com>" . "\r\n";

    // Attempt Native Mail dispatch or Gmail SMTP if configured
    $sentReal = false;
    if (defined('GMAIL_APP_PASSWORD') && !empty(GMAIL_APP_PASSWORD)) {
        $sentReal = sendViaGmailSMTP($toEmail, $subject, $htmlContent, 'mdarbinahsanchowdhury@gmail.com', GMAIL_APP_PASSWORD);
    }
    if (!$sentReal) {
        @mail($toEmail, $subject, $htmlContent, $headers);
    }

    global $pdo;
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO `sent_emails` (`recipient_email`, `sender_email`, `subject`, `body_html`) VALUES (:to, :from, :sub, :body)");
            $stmt->execute([
                'to' => $toEmail,
                'from' => 'noreply@studyabroad360.com',
                'sub' => $subject,
                'body' => $htmlContent
            ]);
        } catch (\Throwable $th) {}
    }

    // Log the sent email locally for development inspection
    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        @mkdir($logDir, 0777, true);
    }
    
    $logEntry = "[" . date('Y-m-d H:i:s') . "] EMAIL DISPATCHED to [$toEmail] | Trigger: [$intervalLabel] | Program: [$programTitle]\n";
    @file_put_contents($logDir . '/sent_reminders.log', $logEntry, FILE_APPEND);

    return true;
}

/**
 * Send Password Reset HTML Email to Student/User
 *
 * @param string $toEmail Recipient Email/Gmail
 * @param string $studentName Recipient Name
 * @param string $newPassword Temporary Reset Password
 * @return bool True if sent successfully
 */
function sendPasswordResetEmail($toEmail, $studentName, $newPassword) {
    $senderGmail = "mdarbinahsanchowdhury@gmail.com";
    $subject = "🔑 StudyAbroad360 Password Reset Request";

    $htmlContent = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>$subject</title>
        <style>
            body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
            .email-container { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
            .header { background: linear-gradient(135deg, #4f46e5 0%, #0284c7 100%); color: #ffffff; padding: 30px 24px; text-align: center; }
            .header h1 { margin: 0; font-size: 22px; font-weight: 800; }
            .body-content { padding: 28px 24px; }
            .pass-box { background: #f0fdf4; border: 2px dashed #16a34a; border-radius: 10px; padding: 16px; text-align: center; margin: 20px 0; }
            .pass-text { font-size: 24px; font-weight: 800; color: #15803d; letter-spacing: 2px; }
            .btn { display: inline-block; background: #4f46e5; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 14px; margin-top: 15px; }
            .footer { background: #f8fafc; text-align: center; padding: 16px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h1>🔑 StudyAbroad360 Password Reset</h1>
                <p style='margin-top:4px; opacity:0.9; font-size:13px;'>Account Recovery Service</p>
            </div>
            
            <div class='body-content'>
                <p>Hello <strong>" . htmlspecialchars($studentName) . "</strong>,</p>
                <p>We received a request to reset your password for your <strong>StudyAbroad360</strong> account (<code>" . htmlspecialchars($toEmail) . "</code>).</p>
                
                <p>Your password has been reset to the following temporary password:</p>
                
                <div class='pass-box'>
                    <div style='font-size:12px; color:#166534; font-weight:700; text-transform:uppercase;'>Your Temporary Password</div>
                    <div class='pass-text'>" . htmlspecialchars($newPassword) . "</div>
                </div>

                <p style='font-size:13px; color:#64748b;'>For security reasons, please log in with this temporary password and update your password under your account profile.</p>
                
                <div style='text-align: center;'>
                    <a href='http://localhost/UIUDHHACKATHON/login.php' class='btn'>Log In To Your Account →</a>
                </div>
            </div>

            <div class='footer'>
                <p>Sent via <strong>StudyAbroad360 Security Mailer</strong> (<code>$senderGmail</code>).</p>
                <p>If you did not request a password reset, please contact support immediately.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: StudyAbroad360 Security <$senderGmail>" . "\r\n";

    // Attempt Gmail SMTP if App Password is set, fallback to native mail
    $sentReal = false;
    if (defined('GMAIL_APP_PASSWORD') && !empty(GMAIL_APP_PASSWORD)) {
        $sentReal = sendViaGmailSMTP($toEmail, $subject, $htmlContent, $senderGmail, GMAIL_APP_PASSWORD);
    }
    if (!$sentReal) {
        @mail($toEmail, $subject, $htmlContent, $headers);
    }

    global $pdo;
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO `sent_emails` (`recipient_email`, `sender_email`, `subject`, `body_html`) VALUES (:to, :from, :sub, :body)");
            $stmt->execute([
                'to' => $toEmail,
                'from' => $senderGmail,
                'sub' => $subject,
                'body' => $htmlContent
            ]);
        } catch (\Throwable $th) {}
    }

    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        @mkdir($logDir, 0777, true);
    }
    
    $statusMsg = $sentReal ? "REAL GMAIL SENT" : "LOCAL LOGGED";
    $logEntry = "[" . date('Y-m-d H:i:s') . "] PASSWORD RESET ($statusMsg) to [$toEmail] from [$senderGmail] | TempPass: [$newPassword]\n";
    @file_put_contents($logDir . '/sent_reminders.log', $logEntry, FILE_APPEND);

    return true;
}

/**
 * Direct Socket SMTP Email Dispatcher for Gmail
 */
function sendViaGmailSMTP($toEmail, $subject, $htmlBody, $senderEmail = 'mdarbinahsanchowdhury@gmail.com', $appPassword = '') {
    if (empty($appPassword)) return false;
    
    $host = 'ssl://smtp.gmail.com';
    $port = 465;
    $timeout = 10;
    
    $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$socket) return false;
    
    fgets($socket, 512);
    
    fputs($socket, "EHLO localhost\r\n");
    fgets($socket, 512);
    
    fputs($socket, "AUTH LOGIN\r\n");
    fgets($socket, 512);
    
    fputs($socket, base64_encode($senderEmail) . "\r\n");
    fgets($socket, 512);
    
    fputs($socket, base64_encode(str_replace(' ', '', $appPassword)) . "\r\n");
    $authRes = fgets($socket, 512);
    if (strpos($authRes, '235') === false) {
        fclose($socket);
        return false;
    }
    
    fputs($socket, "MAIL FROM: <$senderEmail>\r\n");
    fgets($socket, 512);
    
    fputs($socket, "RCPT TO: <$toEmail>\r\n");
    fgets($socket, 512);
    
    fputs($socket, "DATA\r\n");
    fgets($socket, 512);
    
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: StudyAbroad360 <$senderEmail>\r\n";
    $headers .= "To: <$toEmail>\r\n";
    $headers .= "Subject: $subject\r\n\r\n";
    
    fputs($socket, $headers . $htmlBody . "\r\n.\r\n");
    fgets($socket, 512);
    
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    return true;
}
?>
