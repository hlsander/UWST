<?php
//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//  Functions for admin.php
//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
include "config.php";

function insertData($query)
{
   include "config.php";
   $conn = mysqli_connect($db_host, $db_user, $db_pass, $db);
   $ins = mysqli_query($conn,$query) or die ("ERROR AF1001: DATA INSERTION ERROR:  ".mysqli_error($conn));
   if($conn !== null)
   {
      mysqli_close($conn);   
   }
}

function getAgencyName($username)
{
   include "config.php";
   $conn = mysqli_connect($db_host, $db_user, $db_pass, $db);
   $query = "select agcode from Account where userID = '$username'";
   $res = mysqli_query($conn,$query) or die ("ERROR AF1002: SEARCH ERROR  ".mysqli_error($conn));
   $num = mysqli_num_rows($res);
   
   if($num == 0)
   {
      $agency = "NO RESULTS FOUND";
   }
   else
   {
      $assoc = mysqli_fetch_assoc($res);
      $agcode = $assoc['agcode'];
      
      $agQ = "SELECT agname FROM Agency WHERE agcode='".$agcode."'";
      $agR = mysqli_query($conn,$agQ) or die ("ERROR AF1036:  SEARCH ERROR  ".mysqli_error($conn));
      
      $nrows = mysqli_num_rows($agR);
      if($nrows == 0) { $agency = "NO RESULTS FOUND"; }
      else
      {
         $agassoc = mysqli_fetch_assoc($agR);
         $agency = $agassoc['agname'];
      }
   }
   
   if($conn !== null)
   {
      mysqli_close($conn);   
   }
   return $agency;
}

function randomString($num)
{
   $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $rstring = '';
   for ($c = 0; $c < $num; $c++)
   {
      $rstring .= $chars[rand(0, strlen($chars))];
   }
   
   return $rstring;
}  

function fixAgencyTags()
{
   $conn = mysqli_connect($db_host, $db_user, $db_pass, $db);
   //Fix the tag numbers in the database so that they inc according to agency, color, etc
   //Get a list of agencies being used in the Tags table:

   $agQ = "SELECT DISTINCT accid FROM Tag";
   $agR = mysqli_query($conn,$agQ);

   while($agRow = mysqli_fetch_assoc($agR))
   {
      //Separate out the tags by color
      $colQ = "SELECT DISTINCT Tag FROM Tag WHERE accid = '".$agRow['accid']."'";
      $colR = mysqli_query($conn,$colQ);
      
      while($colRow = mysqli_fetch_assoc($colR))
      {
         $updQ = "SELECT Tag_ID WHERE accid='".$agRow['accid']."' AND Tag='".$colRow['Tag']."'";
         $updR = mysqli_query($conn,$updQ);
         
         while($updRow = mysql_fetch_assoc($updR))
         {
            //Number them up by color and agency
            $count = 1;
            $cntQ = "UPDATE Tag SET agtagnum='".$count."' WHERE accid='".$updRow['Tag_ID']."'";
            $cntR = mysql_query($conn,$cntQ) or die ("ERROR AF1086:  SEARCH ERROR  ".mysqli_error($conn));
            $count ++;
         }
      }
   }
}

?>