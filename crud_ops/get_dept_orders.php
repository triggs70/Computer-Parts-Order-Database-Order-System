<?php
include '../connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'];
    $query = "SELECT Dname, TotalOrders FROM DeptOrders WHERE Dname = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No orders found for the selected department.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
$conn->close();
?>