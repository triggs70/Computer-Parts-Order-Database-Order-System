<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../backend/login_frontend.php"); 
    exit();
}
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Inventory Management System</h1>
    <div class="navigation">
        <a href="search_orders_frontend.php" class="view-button">Search Order</a>
        <?php if ($user_role === 'manager'): ?>
            <a href="#" id="viewDeptOrdersButton" class="view-button">View Department Orders</a>
        <?php endif; ?>
        <a href="login_frontend.php" class="view-button"> Log out</a>
    </div>

    <div class="summary">
        <h3>Data Summary</h3>
        <table>
            <tr>
                <th>Entity</th>
                <th>Total Records</th>
                <th>Action</th>
            </tr>
            <?php
            include '../connection.php';
            $tables = [
                'Device' => 'device_frontend.php',
                'Model' => 'model_frontend.php',
                'Supplier' => 'supplier_frontend.php',
                'Department' => 'department_frontend.php',
                'Employee' => 'employee_frontend.php',
                'Orders' => 'order_frontend.php',
                'OrderItem' => 'orderitem_frontend.php',
                'Budget' => 'budget_frontend.php'
            ];
            foreach ($tables as $table => $link) {
                if ($user_role === 'employee' && !in_array($table, ['Device', 'Model', 'Supplier', 'Orders', 'OrderItem'])) {
                    continue;
                }
                $result = $conn->query("SELECT COUNT(*) AS count FROM `$table`");
                $row = $result->fetch_assoc();
                echo "<tr>
                        <td>$table</td>
                        <td>{$row['count']}</td>
                        <td>
                            <a href='$link' class='view-button'>View Table</a>
                        </td>
                      </tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
    <?php if ($user_role === 'manager'): ?>
        <div id="deptOrdersForm" class="dept-orders" style="display: none;">
            <h3>View Department Orders</h3>
            <form id="departmentForm" method="POST">
                <label for="department">Select Department:</label>
                <select id="department" name="department" required>
                    <?php
                    include '../connection.php';
                    $deptQuery = "SELECT Dname FROM Department";
                    $deptResult = $conn->query($deptQuery);

                    if ($deptResult->num_rows > 0) {
                        while ($dept = $deptResult->fetch_assoc()) {
                            echo "<option value='{$dept['Dname']}'>{$dept['Dname']}</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No departments available</option>";
                    }
                    ?>
                </select>
                <button type="button" class = "view-button" id="fetchDeptOrdersButton">Find All Department Orders</button>
            </form>
            <div id="deptOrdersResult" class="results"></div>
        </div>
    <?php endif; ?>

    <script>
        <?php if ($user_role === 'manager'): ?>
            const viewDeptOrdersButton = document.getElementById('viewDeptOrdersButton');
            const deptOrdersForm = document.getElementById('deptOrdersForm');
            const fetchDeptOrdersButton = document.getElementById('fetchDeptOrdersButton');
            const deptOrdersResult = document.getElementById('deptOrdersResult');
            viewDeptOrdersButton.addEventListener('click', () => {
                deptOrdersForm.style.display = deptOrdersForm.style.display === 'none' ? 'block' : 'none';
            });
            fetchDeptOrdersButton.addEventListener('click', async () => {
                const department = document.getElementById('department').value;
                try {
                    const response = await fetch('../crud_ops/dept_orders_view.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `department=${department}`
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        const orders = result.data;
                        let tableHTML = `
                            <h3>Orders for Department: ${department}</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>REQ</th>
                                        <th>Employee</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        orders.forEach(order => {
                            const totalPrice = parseFloat(order.TotalPrice) || 0;
                            tableHTML += `
                                <tr>
                                    <td>${order.REQNumber}</td>
                                    <td>${order.EmployeeName}</td>
                                    <td>$${totalPrice.toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        tableHTML += `
                                </tbody>
                            </table>
                        `;
                        deptOrdersResult.innerHTML = tableHTML;
                    } else {
                        deptOrdersResult.innerHTML = `<p style="color: red;">${result.message}</p>`;
                    }
                } catch (error) {
                    deptOrdersResult.innerHTML = `<p style="color: red;">An error occurred while fetching the data.</p>`;
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
