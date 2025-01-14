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
    <title>Order Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Order Management</h1>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        echo "<div class='status-message'>";
        switch ($status) {
            case 'added':
                echo "<p style='color: green;'>Order added successfully!</p>";
                break;
            case 'updated':
                echo "<p style='color: green;'>Order updated successfully!</p>";
                break;
            case 'deleted':
                echo "<p style='color: green;'>Order deleted successfully!</p>";
                break;
            case 'error':
                $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                echo "<p style='color: red;'>Error: $message</p>";
                break;
        }
        echo "</div>";
    }
    ?>

    <form id="createOrderForm" method="POST" action="../crud_ops/order_ops.php">
        <input type="hidden" name="action" value="create">
        <h3>Order Details</h3>
        <label for="order_date">Order Date:</label>
        <input type="date" id="order_date" name="order_date" required>
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Ordered">Ordered</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
        </select>
        <label for="eta">ETA:</label>
        <input type="date" id="eta" name="eta">
        <label for="emp_id">Employee ID:</label>
        <select id="emp_id" name="emp_id" required>
            <?php
            include '../connection.php';
            $employeeQuery = "SELECT EmpID, Fname, Lname FROM Employee";
            $employeeResult = $conn->query($employeeQuery);
            if ($employeeResult->num_rows > 0) {
                while ($employee = $employeeResult->fetch_assoc()) {
                    echo "<option value='{$employee['EmpID']}'>{$employee['Fname']} {$employee['Lname']} (ID: {$employee['EmpID']})</option>";
                }
            } else {
                echo "<option value='' disabled>No employees available</option>";
            }
            ?>
        </select>
        <h3>Order Items</h3>
        <div id="orderItemsContainer">
        </div>
        <button type="button" id="addItemButton">Add Item</button>
        <button type="submit">Create Order</button>
    </form>
    <script>
        const orderItemsContainer = document.getElementById('orderItemsContainer');
        const addItemButton = document.getElementById('addItemButton');
        addItemButton.addEventListener('click', () => {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('order-item');
            itemDiv.innerHTML = `
                <label>Model:</label>
                <select name="model_id[]" required>
                    <?php
                    $modelQuery = "SELECT ModID, Mname FROM Model";
                    $modelResult = $conn->query($modelQuery);
                    if ($modelResult->num_rows > 0) {
                        while ($model = $modelResult->fetch_assoc()) {
                            echo "<option value='{$model['ModID']}'>{$model['Mname']} (ID: {$model['ModID']})</option>";
                        }
                    }
                    ?>
                </select>
                <label>Quantity:</label>
                <input type="number" name="quantity[]" required>
                <label>Price:</label>
                <input type="number" name="price[]" step="0.01" required>
                <button type="button" class="removeItemButton">Remove</button>
            `;
            orderItemsContainer.appendChild(itemDiv);
            itemDiv.querySelector('.removeItemButton').addEventListener('click', () => {
                orderItemsContainer.removeChild(itemDiv);
            });
        });
    </script>

    <form action="../crud_ops/order_ops.php" method="POST">
        <h3>Update Order</h3>
        <label for="req">REQ:</label>
        <select id="req" name="req" required>
            <?php
            $orderQuery = "SELECT REQ, OrderDate FROM Orders";
            $orderResult = $conn->query($orderQuery);
            if ($orderResult->num_rows > 0) {
                while ($order = $orderResult->fetch_assoc()) {
                    echo "<option value='{$order['REQ']}'>REQ: {$order['REQ']}</option>";
                }
            } else {
                echo "<option value='' disabled>No orders available</option>";
            }
            ?>
        </select>
        <label for="order_date">Order Date:</label>
        <input type="date" id="order_date" name="order_date" required>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Ordered">Ordered</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
        </select>
        <label for="eta">ETA:</label>
        <input type="date" id="eta" name="eta">
        <label for="emp_id">Employee ID:</label>
        <select id="emp_id" name="emp_id">
            <?php
            $employeeQuery = "SELECT EmpID, Fname, Lname FROM Employee";
            $employeeResult = $conn->query($employeeQuery);
            if ($employeeResult->num_rows > 0) {
                while ($employee = $employeeResult->fetch_assoc()) {
                    echo "<option value='{$employee['EmpID']}'>{$employee['Fname']} {$employee['Lname']} (ID: {$employee['EmpID']})</option>";
                }
            } else {
                echo "<option value='' disabled>No employees available</option>";
            }
            ?>
        </select>
        <input type="hidden" name="action" value="update">
        <button type="submit">Update Order</button>
    </form>
    
    <?php if (!$is_employee): ?>
        <form action="../crud_ops/order_ops.php" method="POST">
            <h3>Delete Order</h3>
            <label for="req">REQ:</label>
            <select id="req" name="req" required>
                <?php
                $orderQuery = "SELECT REQ FROM Orders ORDER BY REQ ASC";
                $orderResult = $conn->query($orderQuery);
                if ($orderResult->num_rows > 0) {
                    while ($order = $orderResult->fetch_assoc()) {
                        echo "<option value='{$order['REQ']}'>REQ: {$order['REQ']}</option>";
                    }
                } else {
                    echo "<option value='' disabled>No orders available</option>";
                }
                ?>
            </select>
            <input type="hidden" name="action" value="delete">
            <button type="submit">Delete Order</button>
        </form>
    <?php endif; ?>

    <div>
        <h3>All Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>REQ</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>ETA</th>
                    <th>Employee</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "
                    SELECT o.REQ AS OrderID, o.OrderDate, o.Status, o.ETA, CONCAT(e.Fname, ' ', e.Lname, ' (', e.EmpID, ')') AS Employee 
                    FROM Orders o
                    LEFT JOIN Employee e ON o.EmpID = e.EmpID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['OrderID']}</td>
                                <td>{$row['OrderDate']}</td>
                                <td>{$row['Status']}</td>
                                <td>" . ($row['ETA'] !== '0000-00-00' ? $row['ETA'] : "No ETA Assigned") . "</td>
                                <td>{$row['Employee']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
