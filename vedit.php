<?php
include "config.php";
include "./formvars.php";
include "./admfunctions.php";

if((null !== (htmlentities($_GET['bpass']))))
{
   $bpass = $_GET['content'];
   $dpass = base64_decode($bpass);
   $arrB = preg_split("/,/",$dpass);
   $cid = $arrB[0];
   $uid = $arrB[1];
   //print "bpass: $bpass<br>dpass: $dpass<br>cid: $cid<br>uid: $uid<br>";
}

if((null !== (htmlentities($_POST['pcid']))))
{
   $pcid = htmlentities($_POST['pcid']);
   $puid = htmlentities($_POST['puid']);
}

$_SESSION['username'] = $uid;

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);

/*
if(empty($uid) && empty($cid)) 
{
   header("Location: http://$host$uri/index.php");
}
else
{
   $_SESSION['username'] = $uid;
}
*/

print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
print "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
print "<head>";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />";
print "<title>Sharing Tree - Update Tag Data</title>";
print "</title>";
print "<link href=\"./template/main.css\" rel=\"stylesheet\" type=\"text/css\" />";
?>
<script type="text/javascript" src="admin.js"></script>
<?
print "</head>";
print "";
print "<body class=\"editor\">";


   //$gtQuery = "Select Tag_ID, Client_ID, First_Name, Last_Name, Middle_Initial, Suffix_Name, Gift_Description, Gift_Name, Price, Category, Agency_Name from GreenTagInfo where Tag_ID = '".$cid."'";
   //$gtResult = mysqli_query($gtQuery);
   //$atRow = mysqli_fetch_assoc($gtResult);
   
   //$clid = $atRow['Client_ID'];
   //$clQuery = "SELECT Pant_Size,Pant_Text,Shoes_Size,Shoes_Text,Shirt_Size,Shirt_Text from Clothing WHERE Client_ID='".$clid."'";
   //$clResult = mysqli_query($clQuery);
   //$clRow = mysqli_fetch_assoc($clResult);
   
   $atQuery = "SELECT * FROM AllTagInfo WHERE Tag_ID='".$cid."'"; 
   $atResult = mysqli_query($conn, $atQuery);
   $atRow = mysqli_fetch_assoc($atResult);
   
   if($atRow['Tag'] == 'Green')
   {
      $cQuery = "SELECT Client_ID FROM GreenTagInfo WHERE Tag_ID='".$cid."'";
      //print $cQuery."<br>";
      $cResult = mysqli_query($conn, $cQuery);
      $cRow = mysqli_fetch_assoc($cResult);
      $clid = $cRow['Client_ID'];
      //print "clid:  $clid<br>";
   }
   
   $clQ = "SELECT * FROM Clothing WHERE Client_ID='".$cRow['Client_ID']."'";
   $clR = mysqli_query($conn, $clQ);
   $clRow = mysqli_fetch_assoc($clR);
   
   $zQuery = "SELECT city,state FROM ZipCodes where zip LIKE '".$atRow['Zip']."'";
   $zResult = mysqli_query($conn, $zQuery) or die ("ERROR 1069:  Unable to get result.  ".mysqli_error($conn));
   $zRow = mysqli_fetch_assoc($zResult);
   
   

   if (array_key_exists('chk', $_POST))
   {
      $clid = htmlentities($_POST['clid']);
      $cupdQ = "update Clients set First_Name=\"$firstname\", Last_Name=\"$lastname\", Middle_Name=\"$mi\", Suffix_Name=\"$suffix\", DOB=\"$DOB\", Phone_Number=\"$phone\", Address=\"$address\", 
                Zip=\"$zip\" WHERE Client_ID='".$clid."'";
      $cupdR = mysqli_query($conn, $cupdQ) or die ("ERROR 1050:  Unable to update record".mysqli_error($conn));
      //print $cupdQ."<br><br><br>";
      
      $chkRQ = "SELECT * FROM Requests WHERE Client_ID='".$clid."'";
      $chkRR = mysqli_query($conn, $chkRQ)  or die ("ERROR 1093:  Unable to update record".mysqli_error($conn));
      $numrows = mysqli_num_rows($chkRR);
      //print "num:  $numrows <br>";

      if(isset($_POST['checkedin']))
      {
         $chkQ = "UPDATE Tag SET checkedin='1' WHERE Tag_ID='".$pcid."'";
         //print $chkQ."<br>";
         $chkR = mysqli_query($conn, $chkQ)  or die ("ERROR 1098:  Unable to update record".mysqli_error($conn));
      }
      else
      {
         $chkQ = "UPDATE Tag SET checkedin='0' WHERE Tag_ID='".$pcid."'";
         //print $chkQ."<br>";
         $chkR = mysqli_query($conn, $chkQ)  or die ("ERROR 1103:  Unable to update record".mysqli_error($conn));
      }      
      if(isset($_POST['elftag']))
      {
         $elfQ = "UPDATE Tag SET elftag='1' WHERE Tag_ID='".$pcid."'";
         //print $elfQ."<br>";
         $elfR = mysqli_query($conn, $elfQ)  or die ("ERROR 1098:  Unable to update record".mysqli_error($conn));
      }
      else
      {
         $elfQ = "UPDATE Tag SET elftag='0' WHERE Tag_ID='".$pcid."'";
         //print $elfQ."<br>";
         $elfR = mysqli_query($conn, $elfQ)  or die ("ERROR 1103:  Unable to update record".mysqli_error($conn));
      }
      if(isset($_POST['elfplus']))
      {
         $elfPQ = "UPDATE Tag SET elfplus='1' WHERE Tag_ID='".$pcid."'";
         //print $elfPQ."<br>";
         $elfPR = mysqli_query($conn, $elfPQ) or die ("ERROR 1108:  Unable to update record".mysqli_error($conn));
      }
      else
      {
         $elfPQ = "UPDATE Tag SET elfplus='0' WHERE Tag_ID='".$pcid."'";
         //print $elfQ."<br>";
         $elfPR = mysqli_query($conn, $elfPQ) or die ("ERROR 1113:  Unable to update record".mysqli_error($conn));
      }
      
      if(mysqli_num_rows($chkRR) == 0)
      {
         $rinsQ = "INSERT INTO Requests (Client_ID,Gift_Name, Price, Category, Gift_Description, Gift_Value) VALUES ('$pcid','$giftname', '$gprice', '$category', '$giftdesc', '$gvalue')";
         //print $rinsQ."<BR><BR>";
         $rinsR = mysqli_query($conn,$rinsQ) or die ("ERROR 1086:  Unable to update record".mysqli_error($conn));
      }
      else
      {
         $rupdQ = "update Requests set Gift_Name=\"$giftname\", Price=\"$gprice\", Category=\"$category\", Gift_Description=\"$giftdesc\", Gift_Value=\"$gvalue\" Where Tag_ID='".$pcid."'";
         $rupdR = mysqli_query($conn, $rupdQ) or die ("ERROR 1051:  Unable to update record <br> $rupdQ <br>.".mysqli_error($conn));
         //print $rupdQ."<br><br><br>";
      }
      
      $chkGQ = "SELECT * FROM Clothing WHERE Client_ID='".$pcid."'";
      $chkGR = mysqli_query($conn, $chkGQ);
      if(mysqli_num_rows($chkGR) ==0)
      {
         $ginsQ = "INSERT INTO Clothing (Client_ID,Pant_Size, Pant_Text, Shoes_Size, Shoes_Text, Shirt_Size, Shirt_Text) VALUES ('$pcid','$pantsize', '$panttext', '$shoesize', '$shoetext', '$shirtsize', '$shirttext')";
         //print $ginsQ."<BR><BR>";
         $gupdQ = mysqli_query($conn, $ginsQ) or die ("ERROR 1100:  Unable to update record".mysqli_error($conn));         
      }
      else
      {
         $gupdQ = "UPDATE Clothing set Pant_Text=\"$panttext\", Pant_Size=\"$pantsize\", Shoes_Text=\"$shoetext\", Shoes_Size=\"$shoessize\", Shirt_Text=\"$shirttext\", Shirt_Size=\"$shirtsize\" WHERE Client_ID='".$pcid."'";
         $gupdR = mysqli_query($conn, $gupdQ) or die ("ERROR 1052:  Unable to update record.".mysqli_error($conn));
         //print $gupdQ."<br>";
      }
      //print "<h2>Update Completed</h2>";
   ?>

   <button type="button" class="btn" onclick="javascript:window.close()">Close Window</button>
   <?   
   }
   else
   {
   ?>
   <form name="doForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="chk" value="1">
   <input type="hidden" name="pcid" value="<?=$cid?>">
   <input type="hidden" name="puid" value="<?=$uid?>">
   <input type="hidden" name="clid" value="<?=$clid?>">
   
   <h3>Tag Information:</h3>
   <table class="editor">
    <tr class="editor">
     <td class="editor"><div class="ed1">Elf Tag:</div></td>
     <td class="editor"><input type="checkbox" name="elftag" value="1" <?php echo ($atRow['elftag']==1 ? 'checked' : ' ');?> disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Elf Plus:</div></td>
     <td class="editor"><input type="checkbox" name="elfplus" value="1" <?php echo ($atRow['elfplus']==1 ? 'checked' : ' ');?> ></td>
    </tr>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Checked in:</div></td>
     <td class="editor"><input type="checkbox" name="checkedin" value="1" <?php echo ($atRow['checkedin']==1 ? 'checked' : ' ');?> ></td>
    </tr>
<?
      if($atRow['Tag'] == "Green")
      {
?> 
    <tr class="editor">
     <td class="editor"><div class="ed1">Last Name:</div></td>
     <td class="editor"><input type="text" name="Last_Name" value="<?=$atRow['Last_Name']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">First Name:</div></td>
     <td class="editor"><input type="text" name="First_Name" value="<?=$atRow['First_Name']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Middle Initial:</div></td>
     <td class="editor"><input type="text" name="Middle_Initial" value="<?=$atRow['Middle_Name']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Suffix:</div></td>
     <td class="editor"><input type="text" name="Suffix_Name" value="<?=$atRow['Suffix_Name']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">DOB:</div></td>
     <td class="editor"><div class="ed1"><input type="text" name="DOB" value="<?=$atRow['DOB']?>" disabled="disabled" ></td>
    </tr>
<?
      }
?>
    <tr class="editor">
     <td class="editor"><div class="ed1">Gift Description:</div></td>
     <td class="editor"><textarea name="Gift_Description" rows="5" cols="50" maxlength="250" spellcheck="true" onkeyup="showCharsLeft(this.form.Gift_Description,this.form.counter,250)" onkeydown="showCharsLeft(this.form.Gift_Description,this.form.counter,250)"><?echo $atRow['Gift_Description']?></textarea>
     <br>
      <div class="charcounter">
      (Maximum characters: 250)<br>
      You have&nbsp;<input readonly name="counter" size="3" value="250">&nbsp;characters left.
      </div>
     </td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Requested Gift Price:</div></td>
     <td class="editor"><input type="text" name="Gift_Price" value="<?=$atRow['Price']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Gift Accepted:</div></td>
     <td class="editor"><textarea name="Gift_Name"  rows="5" cols="50" maxlength="250" spellcheck="true" onkeyup="showChars(this.form.Gift_Name,this.form.counter1,250)" onkeydown="showCharsLeft(this.form.Gift_Name,this.form.counter1,250)" ><?echo $atRow['Gift_Name'], "&nbsp;"?></textarea>
     <br>
      <div class="charcounter">
      (Maximum characters: 250)<br>
      You have&nbsp;<input readonly name="counter1" size="3" value="250">&nbsp;characters left.
      </div>
     </td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Accepted Gift Value:</div></td>
     <td class="editor"><input type="text" name="Gift_Value" value="<?=$atRow['Gift_Value']?>"></td>
    </tr>

    <tr class="editor">
     <td class="editor"><div class="ed1">Tag:</div></td>
     <td class="editor"><input type="text" name="Tag" value="<?=$atRow['Tag']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Category:</div></td>
     <td class="editor"><input type="text" name="Category" value="<?=$atRow['Category']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor" colspan="2">&nbsp;</td>
    </tr> 
<?
      if($atRow['Tag'] == "Green")
      {
?> 
    <tr class="editor">
     <td class="editor" colspan="2" width="5%"><div align="center"><h3>Full Information Below For Future Use</h3></div></td>
    </tr>
    <tr class="editor">
     <td class="editor" colspan="2">&nbsp;</td>
    </tr> 
    <tr class="editor">
     <td class="editor"><div class="ed1">Address:</div></td>
     <td class="editor"><input type="text" name="Address" value="<?=$atRow['Address']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">City:</div></td>
     <td class="editor"><input type="text" name="City" id="city" value="<?=$zRow['city']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">State:</div></td>
     <td class="editor"><input type="text" name="State" id="state" value="<?=$zRow['state']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Zip Code:</div></td>
     <td class="editor"><input type="text" name="Zip_Code" id="zip" value="<?=$atRow['Zip']?>" disabled="disabled"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Phone:</div></td>
     <td class="editor"><input type="text" name="Phone_Number" value="<?=$atRow['Phone_Number']?>" disabled="disabled"></td>
    </tr>
<?
      }
?>

<!--
    <tr class="editor">
     <td class="editor"><div class="ed1">Gift 2:</div></td>
     <td class="editor"><input type="text" name="Gift_Name_2" value="<?=$atRow['Gift_Name_2']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Gift 2 Price:</div></td>
     <td class="editor"><input type="text" name="Gift_2_Price" value="<?=$atRow['Gift_2_Price']?>"></td>
    </tr>
-->
<?
      if($atRow['Tag'] == "Green")
      {
?> 
    <tr class="editor">
     <td class="editor"><div class="ed1">Pants Size:</div></td>
     <td class="editor"><input type="text" name="Pant_Size" value="<?=$clRow['Pant_Size']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Pants Info:</div></td>
     <td class="editor"><input type="text" name="Pant_Text" value="<?=$clRow['Pant_Text']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Shoe Size:</div></td>
     <td class="editor"><input type="text" name="Shoe_Size" value="<?=$clRow['Shoe_Size']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Shoe Info:</div></td>
     <td class="editor"><input type="text" name="Shoe_Text" value="<?=$clRow['Shoe_Text']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Shirt Size:</div></td>
     <td class="editor"><input type="text" name="Shirt_Size" value="<?=$clRow['Shirt_Size']?>"></td>
    </tr>
    <tr class="editor">
     <td class="editor"><div class="ed1">Shirt Info:</div></td>
     <td class="editor"><input type="text" name="Shirt_Text" value="<?=$clRow['Shirt_Text']?>"></td>
    </tr>
<?
      }
?>
   </table>
   <button type="button" name="update" class="btn" onclick="document.doForm.submit()">&nbsp;Update Record&nbsp;</button>
   <button type="button" class="btn" onclick="javascript:window.close()">Close Window</button>
   </form>
   <?
   }
   
   if($conn !== null)
   {
      mysqli_close($conn);   
   }

?>
</body>
</html>