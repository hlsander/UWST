<?php
include "admfunctions.php";

if((null !== (htmlentities($_GET['un']))))
{
   $username = htmlentities($_GET['un']);
}
$agency = getAgencyName($username);
echo $agency;
?>
