<?php
include '../connection.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $dname = $_POST['dname'];
                $sql = "INSERT INTO Department (Dname) VALUES ('$dname')"; 
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/department_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/department_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                $deptID = intval($_POST['dept_id']);
                $dname = $_POST['dname'];
                $managerID = !empty($_POST['manager_id']) ? intval($_POST['manager_id']) : null;
                $sql = "UPDATE Department SET Dname = '$dname', ManagerID = " . ($managerID ? $managerID : 'NULL') . " WHERE DeptID = $deptID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/department_frontend.php?status=updated");
                } else {
                    header("Location: ../frontend/department_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'delete') {
                $deptID = intval($_POST['dept_id']);
                $sql = "DELETE FROM Department WHERE DeptID = $deptID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/department_frontend.php?status=deleted");
                } else {
                    header("Location: ../frontend/department_frontend.php?status=error&message=" . urlencode($conn->error));
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

