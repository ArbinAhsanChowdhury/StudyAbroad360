<?php
// forgot_password.php - Forgot Password Account Recovery & Gmail Dispatcher
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/mailer.php';

$error = '';
$success = '';
$resetDetails = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid Gmail / email address.';
    } else {
        // Query user by email (case-insensitive)
        $stmt = $pdo->prepare("SELECT `user_id`, `name`, `email` FROM `users` WHERE LOWER(`email`) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "No account found registered with $email. Please check your email or create a new student account.";
        } else {
            // Generate a secure temporary password
            $tempPass = 'Reset#' . rand(1000, 9999);
            $hashedPass = password_hash($tempPass, PASSWORD_DEFAULT);

            // Update user password in MySQL database
            $update = $pdo->prepare("UPDATE `users` SET `password` = :pass WHERE `user_id` = :uid");
            $update->execute(['pass' => $hashedPass, 'uid' => $user['user_id']]);

            // Dispatch HTML email via mailer.php
            sendPasswordResetEmail($user['email'], $user['name'], $tempPass);

            $success = "Password reset email sent successfully! We have dispatched your new reset password to <strong>" . htmlspecialchars($user['email']) . "</strong> from <strong>mdarbinahsanchowdhury@gmail.com</strong>. Please check your Gmail inbox.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password | StudyAbroad360</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

<section class="section-padding" style="min-height: 80vh; display: flex; align-items: center; background: var(--slate-100);">
  <div class="container" style="max-width: 520px;">
    <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2.5rem; border: 1px solid var(--slate-200); box-shadow: var(--shadow-xl);">
      
      <div style="text-align: center; margin-bottom: 2rem;">
        <div class="brand-icon" style="margin: 0 auto 1rem auto; width: 56px; height: 56px; font-size: 1.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <i class="fas fa-key"></i>
        </div>
        <h2 style="font-size: 1.75rem; color: var(--navy-900);">Account Recovery</h2>
        <p style="color: var(--slate-500); font-size: 0.9rem; margin-top: 0.25rem;">Enter your email to receive your reset password via Gmail</p>
      </div>

      <?php if (!empty($error)): ?>
        <div style="background: rgba(225, 29, 72, 0.1); border: 1px solid var(--accent-rose); color: var(--accent-rose); padding: 0.85rem; border-radius: var(--radius-sm); font-size: 0.875rem; margin-bottom: 1.5rem;">
          <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div style="background: rgba(13, 148, 136, 0.1); border: 1.5px solid var(--secondary); color: var(--navy-900); padding: 1.5rem; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 1.5rem;">
          <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.75rem; font-size: 1.05rem;">
            <i class="fas fa-paper-plane" style="font-size: 1.25rem;"></i> Password Reset Email Dispatched!
          </div>
          <p style="margin-bottom: 1.25rem; font-size: 0.9rem; color: var(--slate-700); line-height: 1.5;"><?php echo $success; ?></p>

          <a href="login.php" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
            Proceed To Log In <i class="fas fa-sign-in-alt"></i>
          </a>
        </div>
      <?php else: ?>

        <form method="POST" action="forgot_password.php">
          <div class="input-field" style="margin-bottom: 1.5rem;">
            <label for="email">Enter Registered Email / Gmail Address *</label>
            <input type="email" id="email" name="email" required placeholder="mdarbinahsanchowdhury@gmail.com or your registered email">
          </div>

          <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; font-size: 1rem;">
            Send Reset Password to Gmail <i class="fas fa-paper-plane"></i>
          </button>
        </form>

      <?php endif; ?>

      <div style="margin-top: 1.75rem; text-align: center; font-size: 0.875rem; color: var(--slate-500); border-top: 1px solid var(--slate-200); padding-top: 1.25rem;">
        Remembered your password? <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Back to Login</a>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

<script src="script.js"></script>
</body>
</html>
