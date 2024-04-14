-- General Instructions
-- 1.	The .sql files are run automatically, so please ensure that there are no syntax errors in the file. If we are unable to run your file, you get an automatic reduction to 0 marks.
-- Comment in MYSQL 
-- Trigger to automatically increase the salary by 10% for employees below ?60000.
DELIMITER //
CREATE TRIGGER IncreaseSalaryTrigger
BEFORE INSERT ON employees
FOR EACH ROW
BEGIN
    IF NEW.salary < 60000 THEN
        SET NEW.salary = NEW.salary * 1.1;
    END IF;
END;
//
DELIMITER ;

-- Trigger to prevent deleting records from the departments table if employees are assigned.
DELIMITER //
CREATE TRIGGER PreventDepartmentDeletionTrigger
BEFORE DELETE ON departments
FOR EACH ROW
BEGIN
    DECLARE employee_count INT;
    SELECT COUNT(*) INTO employee_count
    FROM employees
    WHERE department_id = OLD.department_id;

    IF employee_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot delete department with assigned employees';
    END IF;
END;
//
DELIMITER ;

-- Trigger to log details of salary updates into a separate audit table.
DELIMITER //
CREATE TRIGGER SalaryUpdateAuditTrigger
AFTER UPDATE ON employees
FOR EACH ROW
BEGIN
    INSERT INTO salary_audit (old_salary, new_salary, employee_name, updated_date)
    VALUES (OLD.salary, NEW.salary, CONCAT(NEW.first_name, ' ', NEW.last_name), NOW());
END;
//
DELIMITER ;

-- Trigger to automatically assign a department based on salary range.
DELIMITER //
CREATE TRIGGER AssignDepartmentTrigger
BEFORE INSERT ON employees
FOR EACH ROW
BEGIN
    IF NEW.salary <= 60000 THEN
        SET NEW.department_id = 3;
    -- Add more conditions and assignments as needed
    END IF;
END;
//
DELIMITER ;

-- Trigger to update the salary of the manager whenever a new employee is hired.
DELIMITER //
CREATE TRIGGER UpdateManagerSalaryTrigger
AFTER INSERT ON employees
FOR EACH ROW
BEGIN
    UPDATE employees
    SET salary = NEW.salary
    WHERE emp_id = (
        SELECT manager_id
        FROM departments
        WHERE department_id = NEW.department_id
        ORDER BY salary DESC
        LIMIT 1
    );
END;
//
DELIMITER ;

-- Trigger to prevent updating the department_id of an employee if they have worked on projects.
DELIMITER //
CREATE TRIGGER PreventDepartmentUpdateTrigger
BEFORE UPDATE ON employees
FOR EACH ROW
BEGIN
    DECLARE project_count INT;
    SELECT COUNT(*) INTO project_count
    FROM works_on
    WHERE emp_id = OLD.emp_id;

    IF project_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot update department for employee with assigned projects';
    END IF;
END;
//
DELIMITER ;

-- Trigger to calculate and update the average salary for each department.
DELIMITER //
CREATE TRIGGER UpdateAverageSalaryTrigger
AFTER UPDATE ON employees
FOR EACH ROW
BEGIN
    UPDATE departments d
    SET average_salary = (
        SELECT AVG(salary)
        FROM employees
        WHERE department_id = d.department_id
    );
END;
//
DELIMITER ;

-- Trigger to delete records from the works_on table for a deleted employee.
DELIMITER //
CREATE TRIGGER DeleteEmployeeWorksOnTrigger
AFTER DELETE ON employees
FOR EACH ROW
BEGIN
    DELETE FROM works_on
    WHERE emp_id = OLD.emp_id;
END;
//
DELIMITER ;

-- Trigger to prevent inserting a new employee with salary less than department minimum.
DELIMITER //
CREATE TRIGGER PreventLowSalaryInsertTrigger
BEFORE INSERT ON employees
FOR EACH ROW
BEGIN
    DECLARE min_salary DECIMAL(10, 2);
    SELECT MIN(salary) INTO min_salary
    FROM departments
    WHERE department_id = NEW.department_id;

    IF NEW.salary < min_salary THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Employee salary cannot be less than department minimum';
    END IF;
END;
//
DELIMITER ;

-- Trigger to update total salary budget for a department on employee salary update.
DELIMITER //
CREATE TRIGGER UpdateDepartmentBudgetTrigger
AFTER UPDATE ON employees
FOR EACH ROW
BEGIN
    UPDATE departments d
    SET total_salary_budget = (
        SELECT SUM(salary)
        FROM employees
        WHERE department_id = d.department_id
    );
END;
//
DELIMITER ;

-- Trigger to send email notification to HR on new employee hire.
-- Assuming email sending mechanism is implemented elsewhere.
-- Modify as per your email sending mechanism.
DELIMITER //
CREATE TRIGGER NewEmployeeEmailNotificationTrigger
AFTER INSERT ON employees
FOR EACH ROW
BEGIN
    DECLARE hr_email VARCHAR(255);
    SELECT email INTO hr_email
    FROM employees
    WHERE department_id = 1; -- Assuming HR department has department_id = 1

    -- Code to send email notification to hr_email
END;
//
DELIMITER ;

-- Trigger to prevent inserting a new department without location.
DELIMITER //
CREATE TRIGGER PreventDepartmentInsertWithoutLocationTrigger
BEFORE INSERT ON departments
FOR EACH ROW
BEGIN
    IF NEW.location IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Department location cannot be null';
    END IF;
END;
//
DELIMITER ;

-- Trigger to update department_name in employees table when corresponding department_name is updated in departments table.
DELIMITER //
CREATE TRIGGER UpdateEmployeeDepartmentNameTrigger
AFTER UPDATE ON departments
FOR EACH ROW
BEGIN
    UPDATE employees
    SET department_name = NEW.department_name
    WHERE department_id = NEW.department_id;
END;
//
DELIMITER ;

-- Trigger to log insert, update, delete operations on employees table into audit table.
DELIMITER //
CREATE TRIGGER EmployeeAuditTrigger
AFTER INSERT, UPDATE, DELETE ON employees
FOR EACH ROW
BEGIN
    IF (OLD IS NULL AND NEW IS NOT NULL) THEN
        INSERT INTO employee_audit (operation, emp_id, first_name, last_name, salary, department_id, operation_date)
        VALUES ('INSERT', NEW.emp_id, NEW.first_name, NEW.last_name, NEW.salary, NEW.department_id, NOW());
    ELSEIF (OLD IS NOT NULL AND NEW IS NULL) THEN
        INSERT INTO employee_audit (operation, emp_id, first_name, last_name, salary, department_id, operation_date)
        VALUES ('DELETE', OLD.emp_id, OLD.first_name, OLD.last_name, OLD.salary, OLD.department_id, NOW());
    ELSE
        INSERT INTO employee_audit (operation, emp_id, first_name, last_name, old_salary, new_salary, old_department_id, new_department_id, operation_date)
        VALUES ('UPDATE', OLD.emp_id, OLD.first_name, OLD.last_name, OLD.salary, NEW.salary, OLD.department_id, NEW.department_id, NOW());
    END IF;
END;
//
DELIMITER ;
