<?php
include '../connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'];
    if (empty($department)) {
        echo json_encode(['status' => 'error', 'message' => 'No department specified.']);
        exit;
    }
    $query = "SELECT REQNumber, EmployeeName, TotalPrice FROM DeptOrders WHERE DepartmentName = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $department);
    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch results: ' . $stmt->error]);
        exit;
    }
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    if (count($orders) > 0) {
        echo json_encode(['status' => 'success', 'data' => $orders]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No orders found for the selected department.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
$conn->close();
?>

