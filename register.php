<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = 'Please fill out all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        // Check if email already exists (case-insensitive)
        $stmt = $pdo->prepare("SELECT `user_id`, `password` FROM `users` WHERE LOWER(`email`) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            // If user was auto-created during university application with temporary password 'student123'
            if (password_verify('student123', $existingUser['password'])) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE `users` SET `name` = :name, `password` = :password WHERE `user_id` = :uid");
                $update->execute([
                    'name' => $fullName,
                    'password' => $hashedPassword,
                    'uid' => $existingUser['user_id']
                ]);

                $_SESSION['user_id']    = $existingUser['user_id'];
                $_SESSION['user_name']  = $fullName;
                $_SESSION['user_email'] = strtolower($email);
                $_SESSION['user_role']  = 'student';

                header('Location: student_dashboard.php?msg=welcome');
                exit;
            } else {
                $error = 'An account with this email address already exists. Please log in with your password.';
            }
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES (:name, :email, :password, 'student')");
            $insert->execute([
                'name' => $fullName,
                'email' => strtolower($email),
                'password' => $hashedPassword
            ]);

            $newUserId = $pdo->lastInsertId();
            $_SESSION['user_id']    = $newUserId;
            $_SESSION['user_name']  = $fullName;
            $_SESSION['user_email'] = strtolower($email);
            $_SESSION['user_role']  = 'student';

            header('Location: student_dashboard.php?msg=welcome');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration | StudyAbroad360</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<?php include 'includes/header.php'; ?>

<section class="section-padding" style="min-height: 80vh; display: flex; align-items: center;">
  <div class="container" style="max-width: 520px;">
    <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2.5rem; border: 1px solid var(--slate-200); box-shadow: var(--shadow-xl);">
      <div style="text-align: center; margin-bottom: 2rem;">
        <span class="badge badge-success" style="margin-bottom: 0.5rem;">Single Application Account</span>
        <h2 style="font-size: 1.75rem;">Create Student Account</h2>
        <p style="color: var(--slate-500); font-size: 0.9rem; margin-top: 0.25rem;">Join 100,000+ students applying to global universities</p>
      </div>

      <?php if (!empty($error)): ?>
        <div style="background: rgba(225, 29, 72, 0.1); border: 1px solid var(--accent-rose); color: var(--accent-rose); padding: 0.85rem; border-radius: var(--radius-sm); font-size: 0.875rem; margin-bottom: 1.5rem;">
          <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="register.php">
        <div class="input-field" style="margin-bottom: 1.25rem;">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" required placeholder="John Doe">
        </div>

        <div class="input-field" style="margin-bottom: 1.25rem;">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required placeholder="john@example.com">
        </div>

        <div class="input-field" style="margin-bottom: 1.5rem;">
          <label for="password">Password (Minimum 6 characters)</label>
          <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
          Create Student Account <i class="fas fa-user-plus"></i>
        </button>
      </form>

      <div style="margin-top: 1.5rem; text-align: center; font-size: 0.875rem; color: var(--slate-500);">
        Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Log In Here</a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modals.php'; ?>

<script src="script.js"></script>
</body>
</html>
