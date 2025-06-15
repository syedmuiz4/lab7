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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $matric, $name, $password, $role);
        
        if ($stmt->execute()) {
            $message = "Registration successful!";
            $message_type = "success";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $message = "Error: Matric number already exists!";
        } else {
            $message = "Error: " . $e->getMessage();
        }
        $message_type = "error";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>User Registration</h2>
    
    <?php if (isset($message)): ?>
        <div class="<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="matric">Matric Number:</label>
            <input type="text" id="matric" name="matric" required maxlength="10">
        </div>
        
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required maxlength="100">
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Please select</option>
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
            </select>
        </div>
        
        <div class="button-group">
            <input type="submit" class="btn" value="Submit">
            <a href="users.php" class="btn-cancel">View Users</a>
        </div>
    </form>
</body>
</html>