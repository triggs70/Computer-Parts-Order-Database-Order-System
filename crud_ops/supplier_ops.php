<?php
include '../connection.php'; 
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST': 
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $sname = $conn->real_escape_string($_POST['sname']);
                $phone = $conn->real_escape_string($_POST['phone']);
                $sql = "INSERT INTO Supplier (Sname, Phone) VALUES ('$sname', '$phone')";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/supplier_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/supplier_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                $suppID = intval($_POST['supp_id']);
                $sname = $conn->real_escape_string($_POST['sname']);
                $phone = $conn->real_escape_string($_POST['phone']);
                $sql = "UPDATE Supplier SET Sname = '$sname', Phone = '$phone' WHERE SuppID = $suppID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/supplier_frontend.php?status=updated");
                } else {
                    header("Location: ../frontend/supplier_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'delete') {
                $suppID = intval($_POST['supp_id']);
                $sql = "DELETE FROM Supplier WHERE SuppID = $suppID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/supplier_frontend.php?status=deleted");
                } else {
                    header("Location: ../frontend/supplier_frontend.php?status=error&message=" . urlencode($conn->error));
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

