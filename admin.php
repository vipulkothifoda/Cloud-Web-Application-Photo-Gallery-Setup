<?php
echo "Upload Feature On or Off";
?>

<html>
<head>
<body>
<form action = "" method = "post">
Image Upload
<select name="upload_select_status">
  <option value="blank"> </option>
  <option value="On">ON</option>
  <option value="Off">OFF</option>
</select>
<input type="submit" value="Submit" /></br>
</form>
</body>
<br>
<br>
<a href="gallery.php"> Gallery </a>
<br/><br/>
<a href="backup.php"> Database Backup </a>
<br/><br/>
<a href="index.php"> Logout </a>
</head>
</html>

<?php
session_start();
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
//print_r($endpoint);
echo "<br/>";
echo "<br/>";
//echo "begin database";
$link = mysqli_connect($endpoint,"clouddatabase","cloud123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("mysql db Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$upload_status=$_POST["upload_select_status"];
//echo $upload_status;
if ( $upload_status == "On" )
{
echo "<br/>";
echo "Upload On";
$sql_update_status="update controller set upload=1 where id=1";
}
elseif ($upload_status == "blank" )
{
$sql_update_status="select upload from controller where id=1";
}
elseif ($upload_status == "Off" )
{
echo "<br/>";
echo "Its Off";
$sql_update_status="update controller set upload=0 where id=1";
echo "<br/>";
echo $sql_update_status;
}
if ($link->query($sql_update_status) === TRUE) {
//    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
        $link->close();
?>
