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
    <title>Supplier Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Supplier Management</h1>
    <?php if (!$is_employee): ?>
        <?php
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            echo "<div class='status-message'>";
            switch ($status) {
                case 'added':
                    echo "<p style='color: green;'>Supplier added successfully!</p>";
                    break;
                case 'updated':
                    echo "<p style='color: green;'>Supplier updated successfully!</p>";
                    break;
                case 'deleted':
                    echo "<p style='color: green;'>Supplier deleted successfully!</p>";
                    break;
                case 'error':
                    $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                    echo "<p style='color: red;'>Error: $message</p>";
                    break;
            }
            echo "</div>";
        }
        ?>

        <form action="../crud_ops/supplier_ops.php" method="POST">
            <h3>Add a New Supplier</h3>
            <label for="sname">Supplier Name:</label>
            <input type="text" id="sname" name="sname" placeholder="Enter supplier name" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter phone number">
            <input type="hidden" name="action" value="create">
            <button class="view-button" type="submit">Add Supplier</button>
        </form>

        <form action="../crud_ops/supplier_ops.php" method="POST">
            <h3>Update Supplier</h3>
            <label for="supp_id">Supplier ID:</label>
            <select id="supp_id" name="supp_id" required>
                <?php
                include '../connection.php';
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
            <label for="sname">Supplier Name:</label>
            <input type="text" id="sname" name="sname" placeholder="Enter new supplier name">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter new phone number">
            <input type="hidden" name="action" value="update">
            <button class="view-button" type="submit">Update Supplier</button>
        </form>

        <form action="../crud_ops/supplier_ops.php" method="POST">
            <h3>Delete Supplier</h3>
            <label for="supp_id">Supplier ID:</label>
            <select id="supp_id" name="supp_id" required>
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
            <input type="hidden" name="action" value="delete">
            <button class="view-button" type="submit">Delete Supplier</button>
        </form>
    <?php endif; ?>

    <div>
        <h3>All Suppliers</h3>
        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier Name</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../connection.php';
                $sql = "SELECT * FROM Supplier";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['SuppID']}</td>
                                <td>{$row['Sname']}</td>
                                <td>{$row['Phone']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No suppliers found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
