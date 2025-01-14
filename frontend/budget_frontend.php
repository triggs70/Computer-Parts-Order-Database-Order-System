<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Budget Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Budget Management</h1>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        echo "<div class='status-message'>";
        switch ($status) {
            case 'added':
                echo "<p style='color: green;'>Budget added successfully!</p>";
                break;
            case 'updated':
                echo "<p style='color: green;'>Budget updated successfully!</p>";
                break;
            case 'deleted':
                echo "<p style='color: green;'>Budget deleted successfully!</p>";
                break;
            case 'error':
                $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                echo "<p style='color: red;'>Error: $message</p>";
                break;
        }
        echo "</div>";
    }
    ?>

    <form action="../crud_ops/budget_ops.php" method="POST">
        <h3>Add a New Budget</h3>
        <label for="dept_id">Department ID:</label>
        <select id="dept_id" name="dept_id" required>
            <?php
            include '../connection.php';
            $departmentQuery = "SELECT DeptID, Dname FROM Department";
            $departmentResult = $conn->query($departmentQuery);

            if ($departmentResult->num_rows > 0) {
                while ($department = $departmentResult->fetch_assoc()) {
                    echo "<option value='{$department['DeptID']}'>{$department['Dname']} (ID: {$department['DeptID']})</option>";
                }
            } else {
                echo "<option value='' disabled>No departments available</option>";
            }
            ?>
        </select>
        <label for="total">Total Budget:</label>
        <input type="number" step="0.01" id="total" name="total" placeholder="Enter total budget" required>
        <input type="hidden" name="action" value="create">
        <button class="view-button" type="submit">Add Budget</button>
    </form>

    <form action="../crud_ops/budget_ops.php" method="POST">
        <h3>Update Budget</h3>
        <label for="budget_id">Budget ID:</label>
        <select id="budget_id" name="budget_id" required>
            <?php
            include '../connection.php';
            $budgetQuery = "SELECT BudgetID FROM Budget";
            $budgetResult = $conn->query($budgetQuery);
            if ($budgetResult->num_rows > 0) {
                while ($budget = $budgetResult->fetch_assoc()) {
                    echo "<option value='{$budget['BudgetID']}'>Budget ID: {$budget['BudgetID']}</option>";
                }
            } else {
                echo "<option value='' disabled>No budgets available</option>";
            }
            ?>
        </select>
        <label for="total">New Total Budget:</label>
        <input type="number" step="0.01" id="total" name="total" placeholder="Enter new total budget" required>
        <input type="hidden" name="action" value="update">
        <button class="view-button" type="submit">Update Budget</button>
    </form>

    <form action="../crud_ops/budget_ops.php" method="POST">
        <h3>Delete Budget</h3>
        <label for="budget_id">Budget ID:</label>
        <select id="budget_id" name="budget_id" required>
            <?php
            include '../connection.php';
            $budgetQuery = "SELECT BudgetID FROM Budget";
            $budgetResult = $conn->query($budgetQuery);
            if ($budgetResult->num_rows > 0) {
                while ($budget = $budgetResult->fetch_assoc()) {
                    echo "<option value='{$budget['BudgetID']}'>Budget ID: {$budget['BudgetID']}</option>";
                }
            } else {
                echo "<option value='' disabled>No budgets available</option>";
            }
            ?>
        </select>
        <input type="hidden" name="action" value="delete">
        <button class="view-button" type="submit">Delete Budget</button>
    </form>

    <div>
        <h3>All Budgets</h3>
        <table>
            <thead>
                <tr>
                    <th>Budget ID</th>
                    <th>Department ID</th>
                    <th>Total Budget</th>
                    <th>Spent</th>
                    <th>Remaining</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recalculateBudgetsSQL = "CALL RecalculateBudgets()";
                if (!$conn->query($recalculateBudgetsSQL)) {
                    echo "<tr><td colspan='5' style='color: red;'>Error recalculating budgets: " . $conn->error . "</td></tr>";
                }
                $sql = "SELECT * FROM Budget";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['BudgetID']}</td>
                                <td>{$row['DeptID']}</td>
                                <td>{$row['Total']}</td>
                                <td>{$row['Spent']}</td>
                                <td>{$row['Remaining']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No budgets found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

