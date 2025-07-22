<?php
$servername = "localhost";
$username   = "staracademy7975";
$password   = "Kathryn-bryn6@";
$dbname     = "fieldtrips";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed.']));
}

$sql = "
SELECT
    s.id AS profile_id,
    s.selection AS selection_type,
    s.triptitle,
    s.tripdesc AS product_desc,
    (
        SELECT ci.file_path
        FROM carousel_images ci
        WHERE ci.profile_id = s.id AND ci.alt_text = 'Popular Experience 1'
        LIMIT 1
    ) AS product_picture,
    (
        SELECT sch.start_time
        FROM schedules sch
        WHERE sch.profile_id = s.id
        ORDER BY sch.schedule_id DESC
        LIMIT 1
    ) AS product_start,
    (
        SELECT sch.price
        FROM schedules sch
        WHERE sch.profile_id = s.id
        ORDER BY sch.schedule_id DESC
        LIMIT 1
    ) AS price,
    u.profile_picture AS user_profile_picture
FROM selections s
LEFT JOIN `user` u ON s.user_id = u.id
ORDER BY s.id ASC
LIMIT 10
";


$result = $conn->query($sql);
$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($products);
?>
