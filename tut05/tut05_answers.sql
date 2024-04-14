-- General Instructions
-- 1.	The .sql files are run automatically, so please ensure that there are no syntax errors in the file. If we are unable to run your file, you get an automatic reduction to 0 marks.
-- Comment in MYSQL 
-- 1. Select all employees from the 'Engineering' department.
π_{first\_name, last\_name, salary}(employees ⨝_{employees.department\_id = departments.department\_id} (σ_{department\_name = 'Engineering'}(departments)))

-- 2. Projection to display only the first names and salaries of all employees.
π_{first\_name, salary}(employees)

-- 3. Find employees who are managers.
π_{first\_name, last\_name, salary}(employees ⨝_{employees.emp\_id = departments.manager\_id} departments)

-- 4. Retrieve employees earning a salary greater than ?60000.
σ_{salary > 60000}(employees)

-- 5. Join employees with their respective departments.
employees ⨝_{employees.department\_id = departments.department\_id} departments

-- 6. Perform a Cartesian product between employees and projects.
employees × projects

-- 7. Find employees who are not managers.
π_{first\_name, last\_name, salary}(employees - (π_{first\_name, last\_name, salary}(employees ⨝_{employees.emp\_id = departments.manager\_id} departments)))

-- 8. Perform a natural join between departments and projects.
departments ⨝ projects

-- 9. Project the department names and locations from departments table.
π_{department\_name, location}(departments)

-- 10. Retrieve projects with budgets greater than ?100000.
σ_{budget > 100000}(projects)

-- 11. Find employees who are managers in the 'Sales' department.
π_{first\_name, last\_name, salary}(employees ⨝_{employees.emp\_id = departments.manager\_id} (σ_{department\_name = 'Sales'}(departments)))

-- 12. Union operation between two sets of employees from the 'Engineering' and 'Finance' departments.
π_{first\_name, last\_name, salary}((σ_{department\_name = 'Engineering'}(departments) ⨝ employees) ∪ (σ_{department\_name = 'Finance'}(departments) ⨝ employees))

-- 13. Find employees who are not assigned to any projects.
π_{first\_name, last\_name, salary}(employees - (employees ⨝ projects))

-- 14. Join operation to display employees along with their project assignments.
employees ⨝_{employees.emp\_id = projects.emp\_id} projects

-- 15. Find employees whose salaries are not within the range ?50000 to ?70000.
π_{first\_name, last\_name, salary}(employees - (σ_{salary >= 50000 \land salary <= 70000}(employees)))
