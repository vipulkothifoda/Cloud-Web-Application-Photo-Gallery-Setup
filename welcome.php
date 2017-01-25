<?php
session_start();
$username1 = $_SESSION['login_user'];
echo "Your username is: " . $username1 . "\n";
$temp = ("controller");
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
echo "<br/>";
echo "<br/>";
echo "begin database";
$link = mysqli_connect($endpoint,"clouddatabase","cloud123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("mysql db Connect failed: %s\n", mysqli_connect_error());
    exit();
}
echo "Before query";
#$sql = "Select upload from controller where id=1";
#$res = mysqli_query($sql);
#$total = mysqli_fetch_array($res);
$link->real_query("Select upload from controller where id=1");
$res = $link->use_result();
echo "</br>";
echo "Result set order...\n";
echo "</br>";
while ($row = $res->fetch_assoc()) {
    echo " value1 = " . $row['upload'] . "\n";
    $value = $row['upload'];
    echo "</br>";
}
echo $value;
$link->close();
?>

<html>
<head><title>Welcome</title>
</head>
<body>
<hr />

<?php if ($username1 == $temp): ?>
<a href="gallery.php"> Gallery </a> | <a href="upload.php"> Upload </a> | <a href="admin.php"> Admin </a> |  <a href="index.php?logout=1"> logout</a>

<?php elseif: ($value == "1") ?>
<a href="gallery.php"> Gallery </a> | <a href="upload.php"> Upload </a> | <a href="index.php?logout=1"> logout</a>
  
<?php else: ?>
<a href="gallery.php"> Gallery </a> | <a href="index.php?logout=1"> logout</a>

<?php endif ?>

</body></html>
