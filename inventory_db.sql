CREATE DATABASE IF NOT EXISTS inventory_db;
USE inventory_db;

-- Tables
CREATE TABLE Device (
    DevID INT AUTO_INCREMENT PRIMARY KEY,
    Type VARCHAR(50) NOT NULL
);


CREATE TABLE Supplier (
    SuppID INT AUTO_INCREMENT PRIMARY KEY,
    Sname VARCHAR(100) NOT NULL,
    Phone VARCHAR(15)
);


CREATE TABLE Model (
    ModID INT AUTO_INCREMENT PRIMARY KEY,
    Mname VARCHAR(100) NOT NULL,
    DevID INT NOT NULL,
    SuppID INT NOT NULL,
    FOREIGN KEY (DevID) REFERENCES Device(DevID) ON DELETE CASCADE,
    FOREIGN KEY (SuppID) REFERENCES Supplier(SuppID) ON DELETE CASCADE
);


CREATE TABLE Department (
    DeptID INT AUTO_INCREMENT PRIMARY KEY,
    Dname VARCHAR(100) NOT NULL
);


CREATE TABLE Employee (
    EmpID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100) NOT NULL,
    Fname VARCHAR(50) NOT NULL,
    Lname VARCHAR(50) NOT NULL,
    DeptID INT NOT NULL,
    FOREIGN KEY (DeptID) REFERENCES Department(DeptID) ON DELETE CASCADE
);

ALTER TABLE Department
ADD ManagerID INT NULL,
ADD FOREIGN KEY (ManagerID) REFERENCES Employee(EmpID) ON DELETE SET NULL;


CREATE TABLE `Orders` (
    REQ INT AUTO_INCREMENT PRIMARY KEY,
    OrderDate DATE NOT NULL,
    Status ENUM('Ordered', 'Shipped', 'Delivered') NOT NULL,
    ETA DATE NULL,
    EmpID INT NOT NULL,
    FOREIGN KEY (EmpID) REFERENCES Employee(EmpID)
);


CREATE TABLE OrderItem (
    ItemID INT AUTO_INCREMENT PRIMARY KEY,
    REQ INT NOT NULL,
    ModID INT ,
    Quantity INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (REQ) REFERENCES `Orders`(REQ) ON DELETE CASCADE,
    FOREIGN KEY (ModID) REFERENCES Model(ModID) ON DELETE SET NULL
);


CREATE TABLE Budget (
    BudgetID INT AUTO_INCREMENT PRIMARY KEY,
    DeptID INT NOT NULL UNIQUE,
    Total DECIMAL(10, 2) NOT NULL,
    Spent DECIMAL(10, 2) NOT NULL DEFAULT 0,
    Remaining DECIMAL(10, 2) AS (Total - Spent) STORED,
    FOREIGN KEY (DeptID) REFERENCES Department(DeptID) ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('manager', 'employee') NOT NULL
);

INSERT INTO users (username, password, role)
VALUES
('manager1', 'Manager123', 'manager'),
('employee1', 'Employee123', 'employee');

-- View
CREATE OR REPLACE VIEW DeptOrders AS
SELECT 
    d.Dname AS DepartmentName,
    o.REQ AS REQNumber,
    CONCAT(e.Fname, ' ', e.Lname) AS EmployeeName,
    SUM(oi.Price * oi.Quantity) AS TotalPrice
FROM 
    Department d
JOIN 
    Employee e ON d.DeptID = e.DeptID
JOIN 
    Orders o ON e.EmpID = o.EmpID
LEFT JOIN 
    OrderItem oi ON o.REQ = oi.REQ
GROUP BY 
    d.Dname, o.REQ, EmployeeName;

-- Trigger
DELIMITER $$
CREATE TRIGGER UpdateSpent AFTER INSERT ON OrderItem
FOR EACH ROW
BEGIN
    UPDATE Budget
    SET Spent = Spent + (NEW.Quantity * NEW.Price)
    WHERE DeptID = (SELECT DeptID FROM Employee WHERE EmpID = (SELECT EmpID FROM `Orders` WHERE REQ = NEW.REQ))
    LIMIT 1;
END$$
DELIMITER ;

-- Procedure 1
DELIMITER $$
CREATE PROCEDURE RecalculateBudgets()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE dept_id INT;
    DECLARE dept_cursor CURSOR FOR SELECT DeptID FROM Department;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    OPEN dept_cursor;
    dept_loop: LOOP
        FETCH dept_cursor INTO dept_id;
        IF done THEN
            LEAVE dept_loop;
        END IF;
        UPDATE Budget
        SET Spent = (
            SELECT IFNULL(SUM(oi.Quantity * oi.Price), 0)
            FROM Orders o
            JOIN OrderItem oi ON o.REQ = oi.REQ
            JOIN Employee e ON o.EmpID = e.EmpID
            WHERE e.DeptID = dept_id
        )
        WHERE DeptID = dept_id;
    END LOOP;
    CLOSE dept_cursor;
END$$
DELIMITER ;

-- Procedure 2
DELIMITER $$
CREATE PROCEDURE AddOrder(
    IN empID INT,
    IN orderDate DATE,
    IN status VARCHAR(10),
    IN eta DATE
)
BEGIN
    INSERT INTO `Orders` (EmpID, OrderDate, Status, ETA) VALUES (empID, orderDate, status, eta);
END$$
DELIMITER ;

-- Function
DELIMITER $$
CREATE FUNCTION RemainingBudget(deptID INT) RETURNS DECIMAL(10, 2)
DETERMINISTIC
BEGIN
    DECLARE remaining DECIMAL(10, 2);
    SELECT Total - Spent INTO remaining FROM Budget WHERE DeptID = deptID;
    RETURN remaining;
END$$
DELIMITER ;

-- Indexes
CREATE INDEX idx_DeptID ON Employee(DeptID);
CREATE INDEX idx_Status ON `Orders`(Status);
CREATE INDEX idx_ModID ON OrderItem(ModID);
