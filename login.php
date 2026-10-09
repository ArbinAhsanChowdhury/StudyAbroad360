<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
$success = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $success = 'Account created successfully! Please log in.';
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'please_login') {
    $error = 'Please log in to access your account dashboard.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please fill in both email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE LOWER(`email`) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['user_id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: student_dashboard.php');
            }
            exit;
        } else {
            if ($user && password_verify('student123', $user['password'])) {
                $error = 'Your account was auto-created during application. Default password is "student123" — or click Create Account to set your password.';
            } else {
                $error = 'Invalid email address or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Login | StudyAbroad360</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

<section class="section-padding" style="min-height: 80vh; display: flex; align-items: center;">
  <div class="container" style="max-width: 480px;">
    <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2.5rem; border: 1px solid var(--slate-200); box-shadow: var(--shadow-xl);">
      <div style="text-align: center; margin-bottom: 2rem;">
        <div class="brand-icon" style="margin: 0 auto 1rem auto; width: 50px; height: 50px; font-size: 1.5rem;">
          <i class="fas fa-user-lock"></i>
        </div>
        <h2 style="font-size: 1.75rem;">Welcome Back</h2>
        <p style="color: var(--slate-500); font-size: 0.9rem; margin-top: 0.25rem;">Sign in to your Student or Admin Account</p>
      </div>

      <?php if (!empty($error)): ?>
        <div style="background: rgba(225, 29, 72, 0.1); border: 1px solid var(--accent-rose); color: var(--accent-rose); padding: 0.85rem; border-radius: var(--radius-sm); font-size: 0.875rem; margin-bottom: 1.5rem;">
          <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div style="background: rgba(13, 148, 136, 0.1); border: 1px solid var(--secondary); color: var(--secondary); padding: 0.85rem; border-radius: var(--radius-sm); font-size: 0.875rem; margin-bottom: 1.5rem;">
          <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="input-field" style="margin-bottom: 1.25rem;">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required placeholder="admin1@studyabroad360.com or your student email">
        </div>

        <div class="input-field" style="margin-bottom: 1.5rem;">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
          Sign In <i class="fas fa-sign-in-alt"></i>
        </button>
      </form>

      <div style="margin-top: 1.5rem; text-align: center; font-size: 0.875rem; color: var(--slate-500); display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <span>Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Create Account</a></span>
      </div>

      
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

<script src="script.js"></script>
</body>
</html>
