<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/styles.css">
    <title>OrderItem Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>OrderItem Management</h1>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        echo "<div class='status-message'>";
        switch ($status) {
            case 'added':
                echo "<p style='color: green;'>Order Item added successfully!</p>";
                break;
            case 'updated':
                echo "<p style='color: green;'>Order Item updated successfully!</p>";
                break;
            case 'deleted':
                echo "<p style='color: green;'>Order Item deleted successfully!</p>";
                break;
            case 'error':
                $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                echo "<p style='color: red;'>Error: $message</p>";
                break;
        }
        echo "</div>";
    }
    ?>

    <form action="../crud_ops/orderItem_ops.php" method="POST">
        <h3>Add a New Order Item</h3>
        <label for="req">Order ID:</label>
        <select id="req" name="req" required>
            <?php
            include '../connection.php';
            $orderQuery = "SELECT REQ FROM Orders";
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
        <label for="mod_id">Model ID:</label>
        <select id="mod_id" name="mod_id" required>
            <?php
            include '../connection.php';
            $modelQuery = "SELECT ModID, Mname FROM Model";
            $modelResult = $conn->query($modelQuery);

            if ($modelResult->num_rows > 0) {
                while ($model = $modelResult->fetch_assoc()) {
                    echo "<option value='{$model['ModID']}'>{$model['Mname']} (ID: {$model['ModID']})</option>";
                }
            } else {
                echo "<option value=''disabled>No models available</option>";
            }
            ?>
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <input type="hidden" name="action" value="create">
        <button class="view-button" type="submit">Add Order Item</button>
    </form>

    <form action="../crud_ops/orderItem_ops.php" method="POST">
        <h3>Update Order Item</h3>
        <label for="item_id">Order Item ID:</label>
        <select id="item_id" name="item_id" required>
            <?php
            include '../connection.php';
            $orderItemQuery = "SELECT ItemID FROM OrderItem";
            $orderItemResult = $conn->query($orderItemQuery);

            if ($orderItemResult->num_rows > 0) {
                while ($orderItem = $orderItemResult->fetch_assoc()) {
                    echo "<option value='{$orderItem['ItemID']}'>Order Item ID: {$orderItem['ItemID']}</option>";
                }
            } else {
                echo "<option value='' disabled>No order items available</option>";
            }
            ?>
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <input type="hidden" name="action" value="update">
        <button class="view-button" type="submit">Update Order Item</button>
    </form>

    <form action="../crud_ops/orderItem_ops.php" method="POST">
        <h3>Delete Order Item</h3>
        <label for="item_id">Order Item ID:</label>
        <select id="item_id" name="item_id" required>
            <?php
            include '../connection.php';
            $orderItemQuery = "SELECT ItemID FROM OrderItem";
            $orderItemResult = $conn->query($orderItemQuery);

            if ($orderItemResult->num_rows > 0) {
                while ($orderItem = $orderItemResult->fetch_assoc()) {
                    echo "<option value='{$orderItem['ItemID']}'>Order Item ID: {$orderItem['ItemID']}</option>";
                }
            } else {
                echo "<option value='' disabled>No order items available</option>";
            }
            ?>
        </select>
        <input type="hidden" name="action" value="delete">
        <button class="view-button" type="submit">Delete Order Item</button>
    </form>

    <div>
        <h3>All Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Order ID</th>
                    <th>Model Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../connection.php';
                $sql = "SELECT orderitem.ItemID, orderitem.REQ, model.mName, orderitem.Quantity, orderitem.Price
                        FROM orderitem
                        JOIN model ON orderitem.ModID = model.ModID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['ItemID']}</td>
                                <td>{$row['REQ']}</td>
                                <td>{$row['mName']}</td>
                                <td>{$row['Quantity']}</td>
                                <td>{$row['Price']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No order items found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
