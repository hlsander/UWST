<?php
include "config.php";
include "admfunctions.php";
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);

if((null !== (htmlentities($_GET['ag']))))
{
   $ag = htmlentities($_GET['ag']);
}

$agQ = "SELECT agcode,agname FROM Agency WHERE agname LIKE '%".$ag."%'";

$agR = mysqli_query($conn,$agQ);
$agRow = mysqli_fetch_assoc($agR);
echo $agRow['agcode'].",".$agRow['agname'];
?>