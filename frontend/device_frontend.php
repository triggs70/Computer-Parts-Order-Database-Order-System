<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../frontend/login_frontend.php");
    exit();
}
$user_role = $_SESSION['user_role']; 
$is_employee = $user_role === 'employee';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Device Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Device Management</h1>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        echo "<div class='status-message'>";
        switch ($status) {
            case 'added':
                echo "<p style='color: green;'>Device added successfully!</p>";
                break;
            case 'updated':
                echo "<p style='color: green;'>Device updated successfully!</p>";
                break;
            case 'deleted':
                echo "<p style='color: green;'>Device deleted successfully!</p>";
                break;
            case 'error':
                $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                echo "<p style='color: red;'>Error: $message</p>";
                break;
        }
        echo "</div>";
    }
    ?>
    <?php if (!$is_employee): ?>
        <form action="../crud_ops/device_ops.php" method="POST">
            <h3>Add a New Device</h3>
            <label for="type">Device Type:</label>
            <input type="text" id="type" name="type" placeholder="e.g., GPU" required>
            <input type="hidden" name="action" value="create">
            <button class="view-button" type="submit">Add Device</button>
        </form>
        
        <form action="../crud_ops/device_ops.php" method="POST">
            <h3>Update Device</h3>
            <label for="dev_id">Device ID:</label>
            <select id="dev_id" name="id" required>
                <?php
                include '../connection.php';
                $deviceQuery = "SELECT DevID, Type FROM Device";
                $deviceResult = $conn->query($deviceQuery);
                if ($deviceResult->num_rows > 0) {
                    while ($device = $deviceResult->fetch_assoc()) {
                        echo "<option value='{$device['DevID']}'>{$device['Type']} (ID: {$device['DevID']})</option>";
                    }
                } else {
                    echo "<option value=''>No devices available</option>";
                }
                ?>
            </select>
            <label for="new_type">Device Type:</label>
            <input type="text" id="new_type" name="type" placeholder="Enter new device type">
            <input type="hidden" name="action" value="update">
            <button class="view-button" type="submit">Update Device</button>
        </form>

        <form action="../crud_ops/device_ops.php" method="POST">
            <h3>Delete Device</h3>
            <label for="dev_id">Device ID:</label>
            <select id="dev_id" name="id" required>
                <?php
                $deviceQuery = "SELECT DevID, Type FROM Device";
                $deviceResult = $conn->query($deviceQuery);
                if ($deviceResult->num_rows > 0) {
                    while ($device = $deviceResult->fetch_assoc()) {
                        echo "<option value='{$device['DevID']}'>{$device['Type']} (ID: {$device['DevID']})</option>";
                    }
                } else {
                    echo "<option value=''>No devices available</option>";
                }
                ?>
            </select>
            <input type="hidden" name="action" value="delete">
            <button class="view-button" type="submit">Delete Device</button>
        </form>
    <?php endif; ?>

    <div>
        <h3>All Devices</h3>
        <table>
            <thead>
                <tr>
                    <th>Device ID</th>
                    <th>Device Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../connection.php';
                $sql = "SELECT * FROM Device";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['DevID']}</td>
                                <td>{$row['Type']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No devices found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
