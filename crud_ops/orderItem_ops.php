<?php
include '../connection.php'; 
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST': 
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'create') {
                $req = intval($_POST['req']);
                $modID = intval($_POST['mod_id']);
                $quantity = intval($_POST['quantity']);
                $price = floatval($_POST['price']);
                $sql = "INSERT INTO OrderItem (REQ, ModID, Quantity, Price) VALUES ($req, $modID, $quantity, $price)";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/orderItem_frontend.php?status=added");
                } else {
                    header("Location: ../frontend/orderItem_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'update') {
                $itemID = intval($_POST['item_id']);
                $quantity = intval($_POST['quantity']);
                $sql = "UPDATE OrderItem SET Quantity = $quantity WHERE ItemID = $itemID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/orderItem_frontend.php?status=updated");
                } else {
                    header("Location: ../frontend/orderItem_frontend.php?status=error&message=" . urlencode($conn->error));
                }
            } elseif ($action === 'delete') {
                $itemID = intval($_POST['item_id']);
                $sql = "DELETE FROM OrderItem WHERE ItemID = $itemID";
                if ($conn->query($sql) === TRUE) {
                    header("Location: ../frontend/orderItem_frontend.php?status=deleted");
                } else {
                    header("Location: ../frontend/orderItem_frontend.php?status=error&message=" . urlencode($conn->error));
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


