<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> 
    <title>Employee Management</title>
</head>
<body>
    <div class="back-button">
        <a href="../frontend/index.php">← Back to Home</a>
    </div>
    <h1>Employee Management</h1>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        echo "<div class='status-message'>";
        switch ($status) {
            case 'added':
                echo "<p style='color: green;'>Employee added successfully!</p>";
                break;
            case 'updated':
                echo "<p style='color: green;'>Employee updated successfully!</p>";
                break;
            case 'deleted':
                echo "<p style='color: green;'>Employee deleted successfully!</p>";
                break;
            case 'error':
                $message = isset($_GET['message']) ? $_GET['message'] : "An error occurred.";
                echo "<p style='color: red;'>Error: $message</p>";
                break;
        }
        echo "</div>";
    }
    ?>

    <form action="../crud_ops/employee_ops.php" method="POST">
        <h3>Add a New Employee</h3>
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="Enter first name" required>
        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter last name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>
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
        <input type="hidden" name="action" value="create">
        <button class="view-button" type="submit">Add Employee</button>
    </form>

    <form action="../crud_ops/employee_ops.php" method="POST">
        <h3>Update Employee</h3>
        <label for="emp_id">Employee ID:</label>
        <select id="emp_id" name="emp_id">
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
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="Enter first name">
        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter last name">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email">
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
        <input type="hidden" name="action" value="update">
        <button class="view-button" type="submit">Update Employee</button>
    </form>

    <form action="../crud_ops/employee_ops.php" method="POST">
        <h3>Delete Employee</h3>
        <label for="emp_id">Employee ID:</label>
        <select id="emp_id" name="emp_id">
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
        <input type="hidden" name="action" value="delete">
        <button class="view-button" type="submit">Delete Employee</button>
    </form>

    <div>
        <h3>All Employees</h3>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Department ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../connection.php';
                $sql = "SELECT * FROM Employee";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['EmpID']}</td>
                                <td>{$row['Fname']}</td>
                                <td>{$row['Lname']}</td>
                                <td>{$row['Email']}</td>
                                <td>{$row['DeptID']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No employees found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

