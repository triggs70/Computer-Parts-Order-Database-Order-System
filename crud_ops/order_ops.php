<?php
include '../connection.php';
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        if ($action == 'create') {
            $conn->begin_transaction(); 
            try {
                $orderDate = $_POST['order_date'];
                $status = $_POST['status'];
                $eta = $_POST['eta'];
                $empID = $_POST['emp_id'];
                $orderQuery = "INSERT INTO Orders (OrderDate, Status, ETA, EmpID) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($orderQuery);
                if (!$stmt) {
                    throw new Exception("Prepare failed (Orders): " . $conn->error);
                }
                if (!$stmt->bind_param("sssi", $orderDate, $status, $eta, $empID)) {
                    throw new Exception("Bind failed (Orders): " . $stmt->error);
                }
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed (Orders): " . $stmt->error);
                }
                $orderID = $stmt->insert_id;
                $stmt->close();
                $itemQuery = "INSERT INTO OrderItem (REQ, ModID, Quantity, Price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($itemQuery);
                if (!$stmt) {
                    throw new Exception("Prepare failed (OrderItem): " . $conn->error);
                }
                $modelIds = $_POST['model_id']; 
                $quantities = $_POST['quantity']; 
                $prices = $_POST['price']; 
                for ($i = 0; $i < count($modelIds); $i++) {
                    $modID = $modelIds[$i];
                    $qty = $quantities[$i];
                    $price = $prices[$i];
                    if (!$stmt->bind_param("iiid", $orderID, $modID, $qty, $price)) {
                        throw new Exception("Bind failed (OrderItem): " . $stmt->error);
                    }
                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed (OrderItem): " . $stmt->error);
                    }
                }
                $stmt->close();
                $conn->commit();
                header("Location: ../frontend/order_frontend.php?status=added");
                exit();
            } catch (Exception $e) {
                $conn->rollback(); 
                header("Location: ../frontend/order_frontend.php?status=error&message=" . urlencode($e->getMessage()));
                exit();
            }
        } elseif ($action == 'update') {
            try {
                $req = $_POST['req'];
                $orderDate = $_POST['order_date'];
                $status = $_POST['status'];
                $eta = $_POST['eta'];
                $empId = $_POST['emp_id'];
                if (empty($orderDate)) {
                    throw new Exception("Order date is required but missing");
                }
                $updateQuery = "UPDATE Orders SET OrderDate = ?, Status = ?, ETA = ?, EmpID = ? WHERE REQ = ?";
                $stmt = $conn->prepare($updateQuery);
                if (!$stmt) {
                    throw new Exception("Prepare failed (Update Order): " . $conn->error);
                }
                if (!$stmt->bind_param("ssssi", $orderDate, $status, $eta, $empId, $req)) {
                    throw new Exception("Bind failed (Update Order): " . $stmt->error);
                }
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed (Update Order): " . $stmt->error);
                }
                $stmt->close();
                header("Location: ../frontend/order_frontend.php?status=updated");
                exit();
            } catch (Exception $e) {
                header("Location: ../frontend/order_frontend.php?status=error&message=" . urlencode($e->getMessage()));
                exit();
            }
        } elseif ($action == 'delete') {
            try {
                $req = $_POST['req'];
                $deleteOrderQuery = "DELETE FROM Orders WHERE REQ = ?";
                $stmt = $conn->prepare($deleteOrderQuery);
                if (!$stmt) {
                    throw new Exception("Prepare failed (Delete Order): " . $conn->error);
                }
                if (!$stmt->bind_param("i", $req)) {
                    throw new Exception("Bind failed (Delete Order): " . $stmt->error);
                }
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed (Delete Order): " . $stmt->error);
                }
                $stmt->close();
                header("Location: ../frontend/order_frontend.php?status=deleted");
                exit();
            } catch (Exception $e) {
                $conn->rollback(); 
                header("Location: ../frontend/order_frontend.php?status=error&message=" . urlencode($e->getMessage()));
                exit();
            }
        }
    }
} catch (Exception $e) {
    header("Location: ../frontend/order_frontend.php?status=error&message=" . urlencode($e->getMessage()));
}
$conn->close();


