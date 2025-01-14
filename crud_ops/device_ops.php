<?php
include '../connection.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $type = $conn->real_escape_string($_POST['type']);
                $sql = "INSERT INTO Device (Type) VALUES ('$type')";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/device_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/device_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                if (isset($_POST['id']) && isset($_POST['type'])) {
                    $id = intval($_POST['id']);
                    $type = $conn->real_escape_string($_POST['type']);
                    $sql = "UPDATE Device SET Type = '$type' WHERE DevID = $id";
                    if ($conn->query($sql) === TRUE) {
                        header("Location: ../frontend/device_frontend.php?status=updated");
                    } else {
                        header("Location: ../frontend/device_frontend.php?status=error&message=" . urlencode($conn->error));
                    }
                } else {
                    header("Location: ../frontend/device_frontend.php?status=error&message=Invalid+input");
                }
            } elseif ($action === 'delete') {
                if (isset($_POST['id'])) {
                    $id = intval($_POST['id']);
                    $sql = "DELETE FROM Device WHERE DevID = $id";
                    if ($conn->query($sql) === TRUE) {
                        header("Location: ../frontend/device_frontend.php?status=deleted");
                    } else {
                        header("Location: ../frontend/device_frontend.php?status=error&message=" . urlencode($conn->error));
                    }
                } else {
                    header("Location: ../frontend/device_frontend.php?status=error&message=Invalid+input");
                }
            }
        }
        break;
}
$conn->close();
?>
