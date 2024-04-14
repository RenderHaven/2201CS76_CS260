-- General Instructions
-- 1.	The .sql files are run automatically, so please ensure that there are no syntax errors in the file. If we are unable to run your file, you get an automatic reduction to 0 marks.
-- Comment in MYSQL 
-- 1. Procedure to calculate the average salary of employees in a given department.
DELIMITER //
CREATE PROCEDURE CalculateAverageSalary(IN department_name VARCHAR(255), OUT avg_salary DECIMAL(10, 2))
BEGIN
    SELECT AVG(salary) INTO avg_salary
    FROM employees e
    INNER JOIN departments d ON e.department_id = d.department_id
    WHERE d.department_name = department_name;
END //
DELIMITER ;

-- 2. Procedure to update the salary of an employee by a specified percentage.
DELIMITER //
CREATE PROCEDURE UpdateSalaryByPercentage(IN emp_id INT, IN percentage DECIMAL(10, 2))
BEGIN
    UPDATE employees
    SET salary = salary * (1 + percentage / 100)
    WHERE emp_id = emp_id;
END //
DELIMITER ;

-- 3. Procedure to list all employees in a given department.
DELIMITER //
CREATE PROCEDURE ListEmployeesInDepartment(IN department_name VARCHAR(255))
BEGIN
    SELECT *
    FROM employees e
    INNER JOIN departments d ON e.department_id = d.department_id
    WHERE d.department_name = department_name;
END //
DELIMITER ;

-- 4. Procedure to calculate the total budget allocated to a specific project.
DELIMITER //
CREATE PROCEDURE CalculateProjectBudget(IN project_id INT, OUT total_budget DECIMAL(10, 2))
BEGIN
    SELECT budget INTO total_budget
    FROM projects
    WHERE project_id = project_id;
END //
DELIMITER ;

-- 5. Procedure to find the employee with the highest salary in a given department.
DELIMITER //
CREATE PROCEDURE FindHighestSalaryEmployee(IN department_name VARCHAR(255), OUT emp_name VARCHAR(255), OUT max_salary DECIMAL(10, 2))
BEGIN
    SELECT CONCAT(first_name, ' ', last_name) INTO emp_name, MAX(salary) INTO max_salary
    FROM employees e
    INNER JOIN departments d ON e.department_id = d.department_id
    WHERE d.department_name = department_name;
END //
DELIMITER ;

-- 6. Procedure to list all projects that are due to end within a specified number of days.
DELIMITER //
CREATE PROCEDURE ListProjectsEndingWithin(IN days INT)
BEGIN
    SELECT *
    FROM projects
    WHERE end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL days DAY);
END //
DELIMITER ;

-- 7. Procedure to calculate the total salary expenditure for a given department.
DELIMITER //
CREATE PROCEDURE CalculateDepartmentSalaryExpenditure(IN department_name VARCHAR(255), OUT total_salary DECIMAL(10, 2))
BEGIN
    SELECT SUM(salary) INTO total_salary
    FROM employees e
    INNER JOIN departments d ON e.department_id = d.department_id
    WHERE d.department_name = department_name;
END //
DELIMITER ;

-- 8. Procedure to generate a report listing all employees along with their department and salary details.
DELIMITER //
CREATE PROCEDURE GenerateEmployeeReport()
BEGIN
    SELECT e.first_name, e.last_name, d.department_name, e.salary
    FROM employees e
    INNER JOIN departments d ON e.department_id = d.department_id;
END //
DELIMITER ;

-- 9. Procedure to find the project with the highest budget.
DELIMITER //
CREATE PROCEDURE FindProjectWithHighestBudget(OUT project_name VARCHAR(255), OUT max_budget DECIMAL(10, 2))
BEGIN
    SELECT project_name INTO project_name, MAX(budget) INTO max_budget
    FROM projects;
END //
DELIMITER ;

-- 10. Procedure to calculate the average salary of employees across all departments.
DELIMITER //
CREATE PROCEDURE CalculateOverallAverageSalary(OUT avg_salary DECIMAL(10, 2))
BEGIN
    SELECT AVG(salary) INTO avg_salary
    FROM employees;
END //
DELIMITER ;

-- 11. Procedure to assign a new manager to a department and update the manager_id in the departments table.
DELIMITER //
CREATE PROCEDURE AssignNewManager(IN department_id INT, IN new_manager_id INT)
BEGIN
    UPDATE departments
    SET manager_id = new_manager_id
    WHERE department_id = department_id;
END //
DELIMITER ;

-- 12. Procedure to calculate the remaining budget for a specific project.
DELIMITER //
CREATE PROCEDURE CalculateRemainingProjectBudget(IN project_id INT, OUT remaining_budget DECIMAL(10, 2))
BEGIN
    SELECT budget - SUM(salary) INTO remaining_budget
    FROM projects p
    LEFT JOIN employees e ON p.project_id = e.department_id
    WHERE p.project_id = project_id;
END //
DELIMITER ;

-- 13. Procedure to generate a report of employees who joined the company in a specific year.
DELIMITER //
CREATE PROCEDURE GenerateEmployeeJoiningReport(IN join_year INT)
BEGIN
    SELECT *
    FROM employees
    WHERE YEAR(join_date) = join_year;
END //
DELIMITER ;

-- 14. Procedure to update the end date of a project based on its start date and duration.
DELIMITER //
CREATE PROCEDURE UpdateProjectEndDate(IN project_id INT, IN duration INT)
BEGIN
    UPDATE projects
    SET end_date = DATE_ADD(start_date, INTERVAL duration DAY)
    WHERE project_id = project_id;
END //
DELIMITER ;

-- 15. Procedure to calculate the total number of employees in each department.
DELIMITER //
CREATE PROCEDURE CalculateEmployeeCountPerDepartment()
BEGIN
    SELECT d.department_name, COUNT(e.emp_id) AS employee_count
    FROM departments d
    LEFT JOIN employees e ON d.department_id = e.department_id
    GROUP BY d.department_name;
END //
DELIMITER ;
