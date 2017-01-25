<?php
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));
$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo544-cloudvipul',
));
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print_r($endpoint);
echo "</br>";
echo "begin database";
$link = mysqli_connect($endpoint,"clouddatabase","cloud123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$create_table = 'CREATE TABLE IF NOT EXISTS Students
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    age INT(3) NOT NULL,
    PRIMARY KEY(id)
)';
$create_tbl = $link->query($create_table);
if ($create_table) {
                echo "</br>";
        echo "<b>Table is created or No error returned.</b>";
        echo "</br>";
}
else {
        echo "error!!";
}
echo "students Table Created";
//delete data from items
$sql = "delete FROM items";
if ($link->query($sql) === TRUE) {
    echo "Items Records Deleted";
        echo "</br>";
} else {
    echo "Error while deleting: " . $sql . "<br>" . $link->error;
}
//delete data from students
$sql = "delete FROM Students";
if ($link->query($sql) === TRUE) {
    echo "Records Deleted";
        echo "</br>";
} else {
    echo "Error while deleting: " . $sql . "<br>" . $link->error;
}
//Insert data
$sql = "INSERT INTO Students (name, age)
VALUES ('Joe', 10), ('Vipul', 24), ('Sam', 22), ('Tom', 55), ('David', 71)";
if ($link->query($sql) === TRUE) {
    echo "New record created successfully";
        echo "</br>";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}
//display records
$link->real_query("SELECT * FROM Students");
$res = $link->use_result();
echo "Result set order...\n";
   echo "<br/>";
   echo "<table>";
   echo "<tr>";
   echo "<th> ID </th>";
   echo "<th> Name </th>";
   echo "<th> Age </th>";
   echo "</tr>";
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>";
    echo $row['id'];
    echo "</td>";
    echo "<td>";
    echo $row['name'];
    echo "</td>";
    echo "<td>";
    echo $row['age'];
    echo "</td>";
    echo "</tr>";
    //echo " id = " . $row['id'] . "\n";
}
$link->close();
?>
