<?php
include '../connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderNumber = intval($_POST['order_number']);
    $sql = "
        SELECT 
            o.REQ AS OrderNumber,
            o.OrderDate,
            o.Status,
            o.ETA,
            e.Fname AS EmployeeFirstName,
            e.Lname AS EmployeeLastName,
            d.Dname AS DepartmentName,
            SUM(oi.Quantity * oi.Price) AS TotalPrice
        FROM `Orders` o
        LEFT JOIN Employee e ON o.EmpID = e.EmpID
        LEFT JOIN Department d ON e.DeptID = d.DeptID
        LEFT JOIN OrderItem oi ON o.REQ = oi.REQ
        WHERE o.REQ = ?
        GROUP BY o.REQ, o.OrderDate, o.Status, o.ETA, e.Fname, e.Lname, d.Dname;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $orderInfo = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $orderInfo]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Order not found.']);
    }
    $stmt->close();
    $conn->close();
    exit();
}

