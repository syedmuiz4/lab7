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

// Handle delete action
if (isset($_GET['delete'])) {
    $matric = $_GET['delete'];
    $sql = "DELETE FROM users WHERE matric = '$matric'";
    if ($conn->query($sql) === TRUE) {
        $message = "User deleted successfully";
        $message_type = "success";
    } else {
        $message = "Error deleting user: " . $conn->error;
        $message_type = "error";
    }
}

// Get all users
$sql = "SELECT matric, name, role FROM users ORDER BY role, name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Registered Users</h2>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="<?php echo $_GET['type']; ?>"><?php echo urldecode($_GET['message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($message)): ?>
        <div class="<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['matric']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="role-<?php echo $row['role']; ?>">
                            <?php echo ucfirst($row['role']); ?>
                        </td>
                        <td class="actions">
                            <a href="edit_user.php?matric=<?php echo $row['matric']; ?>" class="btn-edit">Edit</a>
                            <a href="?delete=<?php echo $row['matric']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users registered yet.</p>
    <?php endif; ?>
    
    <a href="register.php" class="btn">Add New User</a>
</body>
</html>

<?php
$conn->close();
?>