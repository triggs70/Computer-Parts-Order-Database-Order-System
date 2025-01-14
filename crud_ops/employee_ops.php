<?php
include '../connection.php'; 
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST': 
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $fname = $conn->real_escape_string($_POST['fname']);
                $lname = $conn->real_escape_string($_POST['lname']);
                $email = $conn->real_escape_string($_POST['email']);
                $deptID = intval($_POST['dept_id']);
                $sql = "INSERT INTO Employee (Fname, Lname, Email, DeptID) VALUES ('$fname', '$lname', '$email', $deptID)";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/employee_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/employee_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                $empID = intval($_POST['emp_id']);
                $fname = $conn->real_escape_string($_POST['fname']);
                $lname = $conn->real_escape_string($_POST['lname']);
                $email = $conn->real_escape_string($_POST['email']);
                $deptID = intval($_POST['dept_id']);
                $sql = "UPDATE Employee SET Fname = '$fname', Lname = '$lname', Email = '$email', DeptID = $deptID WHERE EmpID = $empID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/employee_frontend.php?status=updated");
                } else {
                    header("Location: ../frontend/employee_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'delete') {
                $empID = intval($_POST['emp_id']);
                $sql = "DELETE FROM Employee WHERE EmpID = $empID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/employee_frontend.php?status=deleted");
                } else {
                    header("Location: ../frontend/employee_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            }
            exit();
        }
        break;
    default:
        echo "Unsupported request method.";
}
$conn->close();
?>

