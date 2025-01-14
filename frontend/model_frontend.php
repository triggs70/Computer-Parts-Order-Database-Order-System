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
    <title>Model Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Model Management</h1>
    <?php if (!$is_employee): ?>
        <?php
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            echo "<div class='status-message'>";
            switch ($status) {
                case 'added':
                    echo "<p style='color: green;'>Model added successfully!</p>";
                    break;
                case 'updated':
                    echo "<p style='color: green;'>Model updated successfully!</p>";
                    break;
                case 'deleted':
                    echo "<p style='color: green;'>Model deleted successfully!</p>";
                    break;
                case 'error':
                    $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                    echo "<p style='color: red;'>Error: $message</p>";
                    break;
            }
            echo "</div>";
        }
        ?>

        <form action="../crud_ops/model_ops.php" method="POST">
            <h3>Add a New Model</h3>
            <label for="mname">Model Name:</label>
            <input type="text" id="mname" name="mname" placeholder="e.g., RTX 4090" required>
            <label for="dev_id">Device ID:</label>
            <select id="dev_id" name="dev_id" required>
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
            <label for="supp_id">Supplier ID:</label>
            <select id="supp_id" name ="supp_id" required>
                <?php
                $supplierQuery = "SELECT SuppID, Sname FROM Supplier";
                $supplierResult = $conn->query($supplierQuery);
                if ($supplierResult->num_rows > 0) {
                    while ($supplier = $supplierResult->fetch_assoc()) {
                        echo "<option value='{$supplier['SuppID']}'>{$supplier['Sname']} (ID: {$supplier['SuppID']})</option>";
                    }
                } else {
                    echo "<option value=''>No Suppliers Available</option>";
                }
                ?>
            </select>
            <input type="hidden" name="action" value="create">
            <button class="view-button" type="submit">Add Model</button>
        </form>

        <form action="../crud_ops/model_ops.php" method="POST">
            <h3>Update Model</h3>
            <label for="mod_id">Model ID:</label>
            <select id="mod_id" name="mod_id" required>
                <?php
                $modelQuery = "SELECT ModID, Mname FROM Model";
                $modelResult = $conn->query($modelQuery);
                if ($modelResult->num_rows > 0) {
                    while ($model = $modelResult->fetch_assoc()) {
                        echo "<option value='{$model['ModID']}'>{$model['Mname']} (ID: {$model['ModID']})</option>";
                    }
                } else {
                    echo "<option value=''>No models available</option>";
                }
                ?>
            </select>
            <label for="mname">Model Name:</label>
            <input type="name" id="mname" name="mname" placeholder="Enter model Name" required>
            <label for="dev_id">Device ID:</label>
            <select id="dev_id" name="dev_id" required>
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
            <label for="supp_id">Supplier ID:</label>
            <select id="supp_id" name = "supp_id" required>
                <?php
                $supplierQuery = "SELECT SuppID, Sname FROM Supplier";
                $supplierResult = $conn->query($supplierQuery);
                if ($supplierResult->num_rows > 0) {
                    while ($supplier = $supplierResult->fetch_assoc()) {
                        echo "<option value='{$supplier['SuppID']}'>{$supplier['Sname']} (ID: {$supplier['SuppID']})</option>";
                    }
                } else {
                    echo "<option value=''>No Suppliers Available</option>";
                }
                ?>
            </select>
            <input type="hidden" name="action" value="update">
            <button class="view-button" type="submit">Update Model</button>
        </form>

        <form action="../crud_ops/model_ops.php" method="POST">
            <h3>Delete Model</h3>
            <label for="mod_id">Model ID:</label>
            <select id="mod_id" name="mod_id" required>
                <?php
                $modelQuery = "SELECT ModID, Mname FROM Model";
                $modelResult = $conn->query($modelQuery);

                if ($modelResult->num_rows > 0) {
                    while ($model = $modelResult->fetch_assoc()) {
                        echo "<option value='{$model['ModID']}'>{$model['Mname']} (ID: {$model['ModID']})</option>";
                    }
                } else {
                    echo "<option value=''>No models available</option>";
                }
                ?>
            </select>
            <input type="hidden" name="action" value="delete">
            <button class="view-button" type="submit">Delete Model</button>
        </form>
    <?php endif; ?>

    <div>
        <h3>All Models</h3>
        <table>
            <thead>
                <tr>
                    <th>Model ID</th>
                    <th>Model Name</th>
                    <th>Device ID</th>
                    <th>Supplier ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../connection.php';
                $sql = "SELECT * FROM Model";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['ModID']}</td>
                                <td>{$row['Mname']}</td>
                                <td>{$row['DevID']}</td>
                                <td>{$row['SuppID']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No models found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
