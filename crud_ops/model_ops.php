<?php
include '../connection.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST': 
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $mname = $conn->real_escape_string($_POST['mname']);
                $devID = intval($_POST['dev_id']);
                $suppID = intval($_POST['supp_id']);
                $sql = "INSERT INTO Model (Mname, DevID, SuppID) VALUES ('$mname', $devID, $suppID)";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/model_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/model_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                $modID = intval($_POST['mod_id']);
                $mname = $conn->real_escape_string($_POST['mname']);
                $devID = intval($_POST['dev_id']);
                $suppID = intval($_POST['supp_id']);
                $sql = "UPDATE Model SET Mname = '$mname', DevID = $devID, SuppID = $suppID WHERE ModID = $modID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/model_frontend.php?status=updated");
                } else {
                    header("Location: ../frontend/model_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'delete') {
                $modID = intval($_POST['mod_id']);
                $sql = "DELETE FROM Model WHERE ModID = $modID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/model_frontend.php?status=deleted");
                } else {
                    header("Location: ../frontend/model_frontend.php?status=error&message=" . urlencode($conn->error));
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
