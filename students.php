<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student List</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Student List</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sample data for demonstration
                $students = [
                    ['id' => 1, 'name' => 'Alice', 'age' => 20, 'grade' => 'A'],
                    ['id' => 2, 'name' => 'Bob', 'age' => 22, 'grade' => 'B'],
                    ['id' => 3, 'name' => 'Charlie', 'age' => 19, 'grade' => 'C'],
                ];

                foreach ($students as $student) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($student['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($student['grade']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>