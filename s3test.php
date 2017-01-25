<?php
echo "Hello World!";
require 'vendor/autoload.php';
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
$result = $s3->listBuckets();
foreach ($result['Buckets'] as $bucket) {
    echo $bucket['Name'] . "\n";
}
$resultdelete = $s3->deleteObject(array(
    // Bucket is required
    'Bucket' => 'raw-vip',
    // Key is required
    'Key' => 'switchonarex.png',
));
// Convert the result object to a PHP array
$array = $result->toArray();
$resultimg = $s3->putObject(array(
    'Bucket' => 'raw-vip',
    'Key'    => 'switchonarex.png',
    'SourceFile' => '/var/www/html/switchonarex.png',
    'ACL' => 'public-read',
    'Body'   => 'Hello!'
));
echo $resultimg['ObjectURL'] . "<br>";
?>
<html>
<body>
<img src="<?php echo $resultimg['ObjectURL'] ?>" width="600" height="600">
</body>
</html>
