<?php
include '../connection.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST': 
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $deptID = intval($_POST['dept_id']);
                $total = floatval($_POST['total']);
                $sql = "INSERT INTO Budget (DeptID, Total, Spent) VALUES ($deptID, $total, 0)";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/budget_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/budget_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                $budgetID = intval($_POST['budget_id']);
                $total = floatval($_POST['total']);
                $sql = "UPDATE Budget SET Total = $total WHERE BudgetID = $budgetID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/budget_frontend.php?status=updated");
                } else {
                    header("Location: ../frontend/budget_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'delete') {
                $budgetID = intval($_POST['budget_id']);
                $sql = "DELETE FROM Budget WHERE BudgetID = $budgetID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/budget_frontend.php?status=deleted");
                } else {
                    header("Location: ../frontend/budget_frontend.php?status=error&message=" . urlencode($conn->error));
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

