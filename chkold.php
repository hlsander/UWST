<?php
include "config.php";
include "admfunctions.php";
if((null !== (htmlentities($_GET['pw']))))
{
   $pw = htmlentities($_GET['pw']);
}
if((null !== (htmlentities($_GET['un']))))
{
   $un = htmlentities($_GET['un']);
}

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);
$chkuser = "select * from AccountInfo where userID='$username'";
$chkresult = mysqli_query($conn, $chkuser) or die ("ERROR CO1010:  Unable to check username ".mysqli_error($conn));
$chkrow = mysqli_fetch_assoc($chkresult);

$dbpass = $chkrow['password'];
$salt = substr($dbpass,0,2);
$crypt = crypt($pw, $salt);

if($crypt == $dbpass)
{
   echo "CORRECT";
}
else
{
   echo "INCORRECT";
}
?>