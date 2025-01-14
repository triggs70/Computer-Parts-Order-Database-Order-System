<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Search Order</title>
</head>
<body>
    <div class="back-button">
        <a href="index.php">← Back to Home</a>
    </div>
    <h1>Search Order</h1>
    <form id="searchForm" method="POST">
        <label for="req">REQ:</label>
        <select id="req" name="req" required>
            <?php
            include '../connection.php';
            $orderQuery = "SELECT REQ, OrderDate FROM Orders";
            $orderResult = $conn->query($orderQuery);
            if ($orderResult->num_rows > 0) {
                while ($order = $orderResult->fetch_assoc()) {
                    echo "<option value='{$order['REQ']}' data-orderdate='" . htmlspecialchars($order['OrderDate'], ENT_QUOTES) . "'>REQ: {$order['REQ']}</option>";
                }
            } else {
                echo "<option value='' disabled>No orders available</option>";
            }
            ?>
        </select>
        <button class="view-button" type="submit">Search</button>
    </form>

    <div class="results-container">
        <div id="results" class="results"></div>
    </div>

    <script>
        const form = document.getElementById('searchForm');
        const resultsDiv = document.getElementById('results');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const orderNumber = document.getElementById('req').value;
            try {
                const response = await fetch('../crud_ops/search_orders.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `order_number=${orderNumber}`
                });
                const result = await response.json();
                if (result.status === 'success') {
                    const data = result.data;
                    const eta = (data.ETA === "0000-00-00" || !data.ETA) ? 'No ETA Specified': data.ETA;
                    const totalPrice = (data.TotalPrice === null || data.TotalPrice === 'null') ? '-' : `$${data.TotalPrice}`;
                    resultsDiv.innerHTML = `
                        <h3>Order Details</h3>
                        <p><strong>Order Number:</strong> ${data.OrderNumber}</p>
                        <p><strong>Order Date:</strong> ${data.OrderDate}</p>
                        <p><strong>Status:</strong> ${data.Status}</p>
                        <p><strong>ETA:</strong> ${eta}</p>
                        <p><strong>Employee:</strong> ${data.EmployeeFirstName} ${data.EmployeeLastName}</p>
                        <p><strong>Department:</strong> ${data.DepartmentName}</p>
                        <p><strong>Total Price:</strong> ${totalPrice}</p>
                    `;
                    resultsDiv.style.display = 'block';
                } else {
                    resultsDiv.innerHTML = `<p style="color: red;">${result.message}</p>`;
                    resultsDiv.style.display = 'block';
                }
            } catch (error) {
                resultsDiv.innerHTML = `<p style="color: red;">An error occurred while fetching the data.</p>`;
            }
        });
    </script>
</body>
</html>
