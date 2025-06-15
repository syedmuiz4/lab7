<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_7";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$user = null;
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $sql = "SELECT * FROM users WHERE matric = '$matric'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = '$name', password = '$password', role = '$role' WHERE matric = '$matric'";
    } else {
        $sql = "UPDATE users SET name = '$name', role = '$role' WHERE matric = '$matric'";
    }
    
    if ($conn->query($sql)) {
        header("Location: users.php?message=User+updated+successfully&type=success");
        exit();
    } else {
        $message = "Error updating user: " . $conn->error;
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit User</h2>
    
    <?php if (isset($message)): ?>
        <div class="<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($user): ?>
    <form method="post">
        <input type="hidden" name="matric" value="<?php echo $user['matric']; ?>">
        
        <div class="form-group">
            <label for="matric">Matric Number:</label>
            <input type="text" id="matric" value="<?php echo $user['matric']; ?>" disabled>
        </div>
        
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password">
        </div>
        
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="lecturer" <?php echo ($user['role'] == 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
            </select>
        </div>
        
        <div class="button-group">
            <input type="submit" class="btn" value="Update User">
            <a href="users.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
    <?php else: ?>
        <p>User not found.</p>
        <a href="users.php" class="btn">Back to Users</a>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>