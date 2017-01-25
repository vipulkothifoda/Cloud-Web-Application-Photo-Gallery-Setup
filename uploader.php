<?php
session_start();
require 'vendor/autoload.php';
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
echo "\n" . $_SESSION['login_user'] ."\n";
//echo "</br>";
// Retrieve the POSTED file information (location, name, etc, etc)
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
#echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n"; echo "</br>";
} else {
    echo "Possible file upload attack!\n"; echo "</br>";
}
//echo 'Here is some more debugging info:'; echo "</br>";
//print_r($_FILES);
// Upload file to S3 bucket
$s3result = $s3->putObject([
    'ACL' => 'public-read',
     'Bucket' => 'raw-vip',
      'Key' =>  basename($_FILES['userfile']['name']),
      'SourceFile' => $uploadfile
// Retrieve URL of uploaded Object
]);
$url=$s3result['ObjectURL'];
echo "</br>";
echo "\n". "This is your S3 Image URL: " . $url ."\n"; echo "</br>";
// INSERT SQL record of job information
$rdsclient = new Aws\Rds\RdsClient([
  'region' => 'us-west-2',
  'version' => 'latest'
]);
$rdsresult = $rdsclient->describeDBInstances([
    'DBInstanceIdentifier' => 'itmo544-cloudvipul'
]);
$endpoint = $rdsresult['DBInstances'][0]['Endpoint']['Address'];
//echo $endpoint . "\n";
$link = mysqli_connect($endpoint,"clouddatabase","cloud123","school", 3306) or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// code to insert new record
/* Prepared statement, stage 1: prepare */
echo "</br>";
if (!($stmt = $link->prepare("INSERT INTO items(id, email, phone, s3rawurl, s3finishedurl, issubscribed, status, receipt) VALUES (NULL, ?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error; echo "</br>";
}
$email=$_SESSION['login_user'];
$phone='3129375292';
$finishedurl=' ';
$issubscribed=0;
$status=0;
$receipt=md5($url);
// prepared statements will not accept literals (pass by reference) in bind_params, you need to declare variables
$stmt->bind_param("ssssiis",$email,$phone,$url,$finishedurl, $issubscribed, $status,$receipt);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
printf("%d Row inserted.\n", $stmt->affected_rows);
echo "</br>";
/* explicit close recommended */
$stmt->close();
// SELECT * FROM items
$link->real_query("SELECT * FROM items");
$res = $link->use_result();
echo "Result set order...\n";
echo "</br>";
while ($row = $res->fetch_assoc()) {
    echo "<table>";
    echo "<tr>";
    echo "<td>";
    echo " id = " . $row['id'] . "\n";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
$link->close();
//MD5 raw URL to SQS Queue
$sqsclient = new Aws\Sqs\SqsClient([
    'region'  => 'us-west-2',
    'version' => 'latest'
]);
// Code to retrieve the Queue URLs
$sqsresult = $sqsclient->getQueueUrl([
    'QueueName' => 'MyQueue', // REQUIRED
]);
echo "</br>";
$queueUrl = $sqsresult->get('QueueUrl');
echo "This is the SQS URL: $queueUrl";
echo "</br>";
//echo $sqsresult['QueueURL'];
//$queueUrl = $sqsresult['QueueURL'];
$sqsresult = $sqsclient->sendMessage([
    'MessageBody' => $receipt, // REQUIRED
    'QueueUrl' => $queueUrl // REQUIRED
]);
echo "Message Id:" . $sqsresult['MessageId'];
?>

<html>
<head><title>Uploader</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
  <title>Login Page</title>
 <style>
  body {
   color: navy !important;;
    background-color: lightgreen !important;;
    font-family: "Comic Sans MS", cursive, sans-serif ;
  }
  </style>
</head>
<body>
   <form action="upload.php">
<button type="Back" formaction="upload.php">Back</button>
    </form>
</body>
</html>
