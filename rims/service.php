<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshops and Mechanics</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Workshop and Mechanic Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Workshop ID</th>
                    <th>Workshop Name</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                    <th>Mechanic Name</th>
                    <th>Specialization</th>
                    <th>Time Slots</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Replace the following with your database connection details
                $host = 'localhost';
                $user = 'root';
                $password = '';
                $dbname = 'rims';

                $conn = new mysqli($host, $user, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT 
                            w.workshop_id AS workshop_id, 
                            w.name AS workshop_name, 
                            m.longitude, 
                            m.latitude, 
                            m.name AS mechanic_name, 
                            m.specialization,
                            GROUP_CONCAT(ts.time_slots SEPARATOR ', ') AS time_slots
                        FROM 
                            workshops w
                        LEFT JOIN 
                            mechanics m ON w.workshop_id = m.workshop_id
                        LEFT JOIN 
                            time_slots ts ON w.workshop_id = ts.workshop_id
                        GROUP BY 
                            w.workshop_id, m.longitude, m.latitude, m.name, m.specialization";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['workshop_id']}</td>
                                <td>{$row['workshop_name']}</td>
                                <td>{$row['longitude']}</td>
                                <td>{$row['latitude']}</td>
                                <td>{$row['mechanic_name']}</td>
                                <td>{$row['specialization']}</td>
                            
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No data available</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
