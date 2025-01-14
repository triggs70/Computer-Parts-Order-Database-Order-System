<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../frontend/styles.css">
    <title>Department Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Department Management</h1>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        echo "<div class='status-message'>";
        switch ($status) {
            case 'added':
                echo "<p style='color: green;'>Department added successfully!</p>";
                break;
            case 'updated':
                echo "<p style='color: green;'>Department updated successfully!</p>";
                break;
            case 'deleted':
                echo "<p style='color: green;'>Department deleted successfully!</p>";
                break;
            case 'error':
                $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                echo "<p style='color: red;'>Error: $message</p>";
                break;
        }
        echo "</div>";
    }
    ?>

    <form action="../crud_ops/department_ops.php" method="POST">
        <h3>Add a New Department</h3>
        <label for="dname">Department Name:</label>
        <input type="text" id="dname" name="dname" placeholder="Enter department name" required>
        <input type="hidden" name="action" value="create">
        <button class="view-button" type="submit">Add Department</button>
    </form>

    <form action="../crud_ops/department_ops.php" method="POST">
        <h3>Update Department</h3>
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
        <label for="new_name">New Department Name:</label>
        <input type="text" id="new_name" name="dname" placeholder="Enter new department name">
        <label for="manager_id">New Manager ID:</label>
        <select id="manager_id" name="manager_id">
            <?php
            include '../connection.php';
            $employeeQuery = "SELECT EmpID, Fname, Lname FROM Employee";
            $employeeResult = $conn->query($employeeQuery);
            if ($employeeResult->num_rows > 0) {
                while ($employee = $employeeResult->fetch_assoc()) {
                    echo "<option  value='{$employee['EmpID']}'>{$employee['Fname']} {$employee['Lname']} (ID: {$employee['EmpID']})</option>";
                }
            } else {
                echo "<option value='' disabled>No employees available</option>";
            }
            ?>
        </select>
        <input type="hidden" name="action" value="update">
        <button class="view-button" type="submit">Update Department</button>
    </form>

    <form action="../crud_ops/department_ops.php" method="POST">
        <h3>Delete Department</h3>
        <label for="delete_id">Department ID:</label>
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
        <input type="hidden" name="action" value="delete">
        <button class="view-button" type="submit">Delete Department</button>
    </form>

    <div>
        <h3>All Departments</h3>
        <table>
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Manager</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../connection.php';
                $sql = "
                    SELECT d.DeptID, d.Dname, CONCAT(e.Fname, ' ', e.Lname, ' (', e.EmpID, ')') AS Manager
                    FROM Department d
                    LEFT JOIN Employee e ON d.ManagerID = e.EmpID
                    ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['DeptID'] . "</td>";
                        echo "<td>" . $row['Dname'] . "</td>";
                        echo "<td>" . ($row['Manager'] ? $row['Manager'] : "No Manager Assigned") . "</td>";
                        echo "</tr>"; 
                    }
                } else {
                    echo "<tr><td colspan='3'>No departments found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
