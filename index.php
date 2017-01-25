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
echo "</br>";
//echo "begin database";
$link = mysqli_connect($endpoint,"clouddatabase","cloud123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// drop the Table
$sql = "drop table loginCredential";
if ($link->query($sql) === TRUE) {
  //  echo "Records Dropped";
        echo "</br>";
} else {
    //echo "Error while dropping table: " . $sql . "<br>" . $link->error;
}
// Create the Table
$create_table = 'CREATE TABLE IF NOT EXISTS loginCredential
(
    username VARCHAR(30) NOT NULL,
    password VARCHAR(25) NOT NULL,
    PRIMARY KEY(username)
)';
$create_tbl = $link->query($create_table);
if ($create_table) {
        echo "</br>";
      //  echo "<b>Login is created or No error returned.</b>";
        echo "</br>";
}
else {
        //echo "error!!";
}
//create Admin table
$create_table2 = 'CREATE TABLE IF NOT EXISTS controller
(
   id INT NOT NULL,
   upload INT
)';
//Adding record to controller table
$query1 = "SELECT * FROM controller";
$res = mysqli_query($link,$query1)or die(mysqli_error());
$row_num = mysqli_num_rows($res);
if( $row_num == 0 )
   {
        $sql = "Insert into controller values (1,1)";
        if ($link->query($sql) === TRUE) {
                echo "New record is inserted successfully:\n";
        } else {
                        echo "Error: " . $sql . "<br>" . $link->error;
        }
  } else {
            // echo "Record is already inserted";
  }
$create_tbl2 = $link->query($create_table2);
if ($create_table2) {
        echo "</br>";
      //  echo "<b>Admin Table Created.</b>";
        echo "</br>";
}
else {
        //echo "error while creating a table";
}
//Insert data into loginCredential
$sql = "INSERT INTO loginCredential (username, password)
VALUES ('hajek@iit.edu','ilovebunnies'), ('vkothifo@hawk.iit.edu', 'cloud123'), ('controller', 'controller')";
if ($link->query($sql) === TRUE) {
   // echo "New record created successfully";
        echo "</br>";
} else {
   // echo "Error: " . $sql . "<br>" . $link->error;
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form
      $myusername = mysqli_real_escape_string($link,$_POST['username']);
      $mypassword = mysqli_real_escape_string($link,$_POST['password']);
      $sql1 = "SELECT * FROM loginCredential WHERE username = '$myusername' and password = '$mypassword'";
      $result1 = mysqli_query($link,$sql1);
      $row = mysqli_fetch_array($result1,MYSQLI_ASSOC);
      $active = $row['active'];
     $count = mysqli_num_rows($result1);
      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count > 0) {
         $_SESSION['login_user'] = $myusername;
         header("location: welcome.php");
      }else {
                   $error = "Your Login Name or Password is invalid";
      }
   }
$create_table1 = 'CREATE TABLE IF NOT EXISTS items
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(32),
    phone VARCHAR(32),
    s3rawurl VARCHAR(255),
    s3finishedurl VARCHAR(255),
    issubscribed INT NOT NULL,
    status INT,
    receipt VARCHAR(255),
    PRIMARY KEY(id)
)';
$create_tbl1 = $link->query($create_table1);
if ($create_table1) {
        echo "Items Table is created or No error returned.";
}
else {
        echo "error!! while creating item table";
}
$link->close();
?>

<html>

   <head>
      <title>Login Page</title>

      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>

   </head>

   <body bgcolor = "#FFFFFF">

      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>

            <div style = "margin:30px">

               <form action = "" method = "post">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>

               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

            </div>

         </div>

      </div>

   </body>
</html>
