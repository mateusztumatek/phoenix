<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "phoenix";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $sql = "UPDATE products SET count=null WHERE id=".$row['id'];
        $conn->query($sql);
    }
} else {
    echo "0 results";
}

?>