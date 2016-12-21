<?php
$PageTitle="Sharing Tree - United Way";

include "./template/header.php";
include "./formvars.php";
include "./admfunctions.php";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);

?>
<script type="text/javascript" src="admin.js"></script>
<?
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if(empty($_SESSION['username'])) 
{
   header("Location: http://$host$uri/index.php");
}
else
{
   $username = $_SESSION['username'];
}

$agency = getAgencyName($username);

?>
 <br>
 <hr>
 <div style="width: 100%; overflow: hidden;">
 <div style="width: 300px; float: left;">
 <FORM name="doForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="chk" value="1">
   <div style="margin-left: 8px;">
   <h3>Hello, <?=$agency?></h3>
   </div>
     &nbsp;&nbsp; What Would You Like to Do?<br><br>

   &nbsp;&nbsp;<SELECT name="job" onChange="doForm.submit()">
    <OPTION SELECTED value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;Select an Option&gt; </OPTION>
   
     <OPTGROUP label="Agency Admin">
      <OPTION value="entertags">Agency Enter Tags</OPTION>
      <OPTION value="edittags">Display/Edit Tags</OPTION>
      <OPTION value="resetpwd">Reset My Password</OPTION>
     </OPTION>

</SELECT><br><br>
  </FORM>
  </div>
  <div style="margin-left: 800px;">
  <a href="http://<?echo $host.$uri;?>/index.php"><button type="button" class="btn">Log Off</button></a>
  </div>
  </div>
  <br><hr>

<?
if (array_key_exists('chk', $_POST))
{
   $job = htmlentities($_POST['job']);

   switch($job)
   {
      case "entertags";
      {  
   	//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//      
		//         START ENTER TAGS
		//
		//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
?>
   <br><h3>Agency Entry Form:  New Tags</h3>
   <br>
	<form name="ENTRY" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="pass2" value="submittags">
   
   <table class="editor">
   <tr class="editor">
   <td class="editor"><div class="ed2">Tag Type:</div></td>
   <td class="editor"><div class="ed3">
   <select name="Tag" id="tag" onchange="tagSelect()" required>
      <option value="">Select Tag Type</option>
      <option value="Green">Green</option>
      <option value="Red">Red</option>
   </select>
   </td>
   </tr>
   
   <tr class="editor">
   <td class="editor"><div class="ed2">Tag Category:</div></td>
   <td class="editor"><div class="ed3">
   <div id="greentags" class="hiddenContent">
<?
  $gtQ = "SELECT catdesc FROM Categories WHERE color='Green'";
  $gtR = mysqli_query($conn,$gtQ);
?>
      <select name="gCategory" id="gc" required>
        <option value="">Please Select Category</option>
<?
   while($gtRow = mysqli_fetch_array($gtR))
   {
?>
         <option value="<?=$gtRow['catdesc']?>"><?=$gtRow['catdesc']?></option>
<?
   }
?>     
      </select>
    </div>
<?
   $rtQ = "SELECT catdesc FROM Categories WHERE color='Red'";
   $rtR = mysqli_query($conn, $rtQ);
?>
    <div id="redtags" class="hiddenContent">
    <select name="rCategory" id="rc" required >
     <option value="">Please Select Category</option>
<?
   while($rtRow = mysqli_fetch_array($rtR))
   {
?>
         <option value="<?=$rtRow['catdesc']?>"><?=$rtRow['catdesc']?></option>
<?
   }
?>   
      </select>       
   </div>
   </td>
   </tr>
   
   <tr class="editor">
   <td class="editor"><div class="ed2">Tag Description:</div></td>
   <td class="editor"><div class="ed3"><textarea name="Gift_Description"  rows="5" cols="50" maxlength="250" spellcheck="true" 
                                        onkeyup="showCharsLeft(this.form.Gift_Description,this.form.counter,250)"
                                        onkeydown="showCharsLeft(this.form.Gift_Description,this.form.counter,250)" required></textarea>
   <br>
   <div class="charcounter">
   (Maximum characters: 250)<br>
   You have&nbsp;<input readonly name="counter" size="3" value="250">&nbsp;characters left.
   </div>
   </td>
   </tr>
   </table>
   <div id="tgreentags" class="hiddenContent">
   <table class="editor">
   <tr class="editor">
   <td class="editor"><div class="ed2">Gift Price:</div></td>
   <td class="editor"><div class="ed3">$
   <select name="Price" >
      <option value="Price">Price</option>

   <?
   for($c=1; $c<31; $c++)
   {
   ?>
      <option value="<?echo $c?>"><?echo $c?></option>
   <?
   }
   ?>
   </select>
   </td>
   </tr>
   
  
  
  <tr class="editor">
   <td><div class="ed2">Gift Recipient Information:</div></b> 
   </td>
   <td class="editor"><div class="ed3"></td>
   </tr>  
   
   <tr class="editor">
   <td class="editor"><div class="ed2">First Name:</div></td>
   <td class="editor"><div class="ed3"><input name="First_Name" id="fn" type="text" required/> &nbsp;
   </td>
   </tr>
   
   <tr class="editor">
   <td class="editor"><div class="ed2">M.I.:</div></td>
   <td class="editor"><div class="ed3"><input name="Middle_Initial" id="mi" type="text"/></td>
   </tr> 
   
   <tr class="editor">
   <td class="editor"><div class="ed2">Last Name:</div></div></td>
   <td class="editor"><div class="ed3"><input name="Last_Name" id="ln" type="text" required />
   </td>
   </tr>
   
   <tr class="editor">
   <td class="editor"><div class="ed2">Suffix (Jr, Sr, III):</div></td>
   <td class="editor"><div class="ed3"><input name="Suffix_Name" type="text"/>
   </td>
   </tr>
   
   <tr class="editor">
   <td class="editor"><div class="ed2">Date Of Birth:</div></td>
   <td>
       <select name="DOB_M" id="dm" required>
      <option value="">Month</option>
   <?
   for($c=1; $c<13; $c++)
   {
   ?>
      <option value="<?echo $c?>"><?printf("%02d", $c)?></option>
   <?
   }
   ?>
   </select> <select name="DOB_D" id="dd" required>
      <option value="">Day</option>

   <?
   for($c=1; $c<32; $c++)
   {
   ?>
      <option value="<?echo $c?>"><?printf("%02d", $c)?></option>
   <?
   }
   ?>
   </select> <select name="DOB_Y" id="dy" required>
      <option value="">Year</option>
   <?
   for($c=1920; $c<2017; $c++)
   {
   ?>
      <option value="<?echo $c?>"><?echo $c?></option>
   <?
   }
   ?>

   </select>
   </td>
   </tr>
   
   <tr class="editor">
   <td class="editor"><div class="ed2">Phone Number:</div></td>
   <td class="editor"><div class="ed3"><input name="Phone_Number" type="text" size="15" /></td>
   </tr>
   </table>
   </div>
 
   <div id="tredtags" class="hiddenContent">
   <table>
   <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
   
   
   </table>
   </div>  
      <span id="tagstatus"></span>
     <input type="submit" class="btn" name="submit" value="Submit Tags">
     <!--
      <button type="button" name="Submit Tags" id="subtags" class="btn" onclick="submitMyTags()">Create Tag</button>
     -->
   </form>
<?

         break;
			//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			//      
			//         END ENTER TAGS
			//
			//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      }
      case "edittags";
      //=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//      
		//         START EDIT TAGS
		//
		//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      {  
         $clQuery="Select AgTag, Tag_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, agname from AllTagInfo";
     
         $clQuery=$clQuery." where agname = '".$agency."' ORDER BY Tag_ID ASC";
         //print "clQuery:  $clQuery<br>";
         
         $clResult = mysqli_query($conn, $clQuery);

         if(mysqli_num_rows($clResult) == 0)
         {
            print "There are no tags to display at this time <br>";
         }
         else
         {
?>

<div class="scrollContainer">
<div class="scrollArea">
<div id="printarea">
<table width="100%" class="results">
<thead class="results">
<tr class="results">
 <th class="results"><div class="cid">Tag Number</div></th> 
 <th class="results"><div class="an">Agency Name</div></th> 
 <th class="results"><div class="cat">Category</div></th> 
 <th class="results"><div class="gd">Gift Description</div></th> 
 <th class="results"><div class="pr">Price</div></th> 
 <th class="results"><div class="fn">First</div></th> 
 <th class="results"><div class="mi">MI</div></th> 
 <th class="results"><div class="ln">Last</div></th> 
 <th class="results"><div class="su">Suffix</div></th> 
 <th class="results"><div class="dob">DOB</div></th> 
 <th class="results"><div class="ph">Phone</div></th> 
 <th class="results"><div class="tag">Tag</div></th>
</tr> 
</thead>
<?
            $c = 0;
            while($clRow = mysqli_fetch_array($clResult))
            {
               //Make a identifier to pass to the next page, to obfuscate the get
               $passer=$clRow['Tag_ID'].",".$username;
               $bpass=base64_encode($passer);
?>
<tbody class="results">
<div class="edit">
<div class="tooltip">
<tr class="results" onclick="window.open('http://<?=$host?><?=$uri?>/edit.php?content=<?=$bpass?>','_blank','location=yes,height=850,width=620,scrollbars=yes,status=yes');">
 <td class="results">
 <div class="cid">
   <?=$clRow['AgTag']?>
 </div>  
 </td>
 <td class="results">
 <div class="an">
   <?=$clRow['agname']?>
 </div>
 </td>
  <td class="results">
  <div class="cat">
   <?=$clRow['Category']?>
  </div>
  </td>
 <td class="results">
 <div class="gd">
   <?=$clRow['Gift_Description']?>
 </div>
 </td>
  <td class="results">
  <div class="pr">
   <?=$clRow['Price']?>
  </div>
 </td>
 <td class="results">
 <div class="fn">
   <?=$clRow['First_Name']?>
 </div>
 </td>
  <td class="results">
  <div class="mi">
   <?=$clRow['Middle_Name']?>
 </div>
 </td>
 <td class="results">
 <div class="ln">
   <?=$clRow['Last_Name']?>
 </div>
 </td>

 <td class="results">
 <div class="su">
   <?=$clRow['Suffix_Name']?>
 </div>
 </td>
 <td class="results">
 <div class="dob">
   <?=$clRow['DOB']?>
 </div>
 </td>
 <td class="results">
 <div class="ph">
   <?=$clRow['Phone_Number']?>
 </div>
 </td>
 <td class="results">
 <div class="tag">
   <?=$clRow['Tag']?>
 </div>
 </td>
</tr>
</div>
</div>
</div>
<?
            }
         } 
      
            
?>
</tbody>
</table>
</div>
</div>
<?
         break;
      //=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//      
		//         END EDIT TAGS
		//
		//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      }
      case "resetpwd";
      {         
 ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="myPassForm">
<input type="hidden" name="pass2" value="resetagpw">
<table class="editor">
<tr class="editor">
 <td class="editor"><div class="ed2">Username:</td></div>
 <td class="editor"><div class="ed3"><input type="text" name="rauser" id="ouser" value="<?=$username?>" readonly="true" /></div></td>
</tr>
<tr class="editor">
 <td class="editor"><div class="ed2">Agency:</td></div>
 <td class="editor"><div class="ed3"><input type="text" name="raagency" id="oagency" value="<?=$agency?>" readonly="true" /></div></td>
</tr>
<tr class="editor">
 <td class="editor"><div class="ed2">Old Password:</td></div>
 <td class="editor"><div class="ed3"><input type="password" name="rpassword" id="oldpass" class="" onkeyup="oldPasswordChk()" />  &nbsp; <span id="oldstatus">Password Strength</span></div></td>
</tr>
<tr class="editor">
 <td class="editor"><div class="ed2">Password:</td></div>
 <td class="editor"><div class="ed3"><input type="password" name="rpassword" id="pass" class="" onkeyup="passwordChanged()" />  &nbsp; <span id="strength">Password Strength</span></div></td>
</tr>
<tr class="editor">
 <td class="editor"><div class="ed2">Confirm:</td></div>
 <td class="editor"><div class="ed3"><input type="password" name="rconfirm" id="conf" class="" onkeyup="check4match()" /></div></td>
</tr>
</table>
<p><span id="pwdstatus"></span></p>
<button type="button" name="Submit Form" class="btn" onclick="submitMyPassword()">Reset Password</button>
</form>
<? 
          break;      
      }
   }
}

   //=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=- End Pass 1 -=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=  
// Deal with form submissions created by Pass 1
elseif (array_key_exists('pass2', $_POST))
{
   $pass2 = htmlentities($_POST['pass2']);
   
   switch($pass2)
   {
      case "submittags";
      {
         $selAQ = "SELECT accid FROM Account WHERE userID='$username'";
         $selAR = mysqli_query($conn, $selAQ);
         $selARow = mysqli_fetch_assoc($selAR);
         $accid = $selARow['accid'];
         
         if($tag == "Green")
         {
            $category=htmlentities($_POST['gCategory']);
            $insCQ = "insert into Clients (First_Name, Middle_Name, Last_Name, Suffix_Name, DOB, Phone_Number) values ('$firstname', '$mi', '$lastname', '$suffix', '$dob', '$phone')";
            insertData($insCQ);
            $selCQ = "SELECT Client_ID from Clients WHERE First_Name='$firstname' AND Last_Name='$lastname' AND DOB='$dob' ORDER BY Client_ID DESC LIMIT 1";
            $selCR = mysqli_query($conn,$selCQ);
            $selRow = mysqli_fetch_assoc($selCR);
            $clid = $selRow['Client_ID'];
            $insRQ = "INSERT INTO Requests (Client_ID,Price, Category, Gift_Description) VALUES ('$clid','$price', '$category','$giftdesc')";
            insertData($insRQ);
            
            $selRQ = "SELECT reqid FROM Requests WHERE Client_ID='$clid'";
            $selRR = mysqli_query($conn,$selRQ);
            $selRRow = mysqli_fetch_assoc($selRR);
            $reqid = $selRRow['reqid'];
            
            $insTQ = "INSERT INTO Tag (accid,Request_ID,Tag) VALUES ('$accid','$reqid','$tag');";
            insertData($insTQ);
            
            $agQ = "SELECT agtagnum FROM Tag WHERE accid='".$accid."' AND Tag = '".$tag."' ORDER BY agtagnum DESC LIMIT 1";
            $agR = mysqli_query($conn,$agQ);
            $agRow = mysqli_fetch_assoc($agR);
            $agnum = $agRow['agtagnum'];
            $agnum ++;
            
            $updAQ = "UPDATE Tag SET agtagnum = ".$agnum." WHERE Request_ID='$reqid'";
            insertData($updAQ);
            
            $selTQ = "SELECT Tag_ID FROM Tag WHERE Request_ID='$reqid'";
            $selRT = mysqli_query($conn,$selTQ);
            $selTRow = mysqli_fetch_assoc($selRT);
            $updTQ = "UPDATE Requests SET Tag_ID='".$selTRow['Tag_ID']."' WHERE Request_ID='$reqid'";
            insertData($updTQ);
         }
         else
         {
            $category=htmlentities($_POST['rCategory']);
            $insRQ = "INSERT INTO Requests (Category, Gift_Description) VALUES ('$category', '$giftdesc')";
            insertData($insRQ);
            
            $selRQ = "SELECT reqid FROM Requests WHERE Category='$category' AND Gift_Description='$giftdesc'";
            $selRR = mysqli_query($conn,$selRQ);
            $selRRow = mysqli_fetch_assoc($selRR);
            $reqid = $selRRow['reqid'];
            
            $insTQ = "INSERT INTO Tag (accid,Request_ID,Tag) VALUES ('$accid','$reqid','$tag');";
            insertData($insTQ);
            
            $agQ = "SELECT agtagnum FROM Tag WHERE accid='".$accid."' AND Tag = '".$tag."' ORDER BY agtagnum DESC LIMIT 1";
            $agR = mysqli_query($conn,$agQ);
            $agRow = mysqli_fetch_assoc($agR);
            $agnum = $agRow['agtagnum'];
            $agnum ++;
            
            $updAQ = "UPDATE Tag SET agtagnum = '".$agnum."' WHERE Request_ID='".$reqid."'";
            insertData($updAQ);
            
            $selTQ = "SELECT Tag_ID FROM Tag WHERE Request_ID='$reqid'";
            $selRT = mysqli_query($conn,$selTQ);
            $selTRow = mysqli_fetch_assoc($selRT);
            $updTQ = "UPDATE Requests SET Tag_ID='".$selTRow['Tag_ID']."' WHERE Request_ID='$reqid' ";
            insertData($updTQ);
         }        
                      
         
         
         print "<b>Data Inserted: </b><br>";
         print "<b>Name: &nbsp; </b>".$firstname." ".$mi.". ".$lastname." ".$suffix."<br>";
         print "<b>Phone: &nbsp; </b>".$phone."<br>";
         print "<b>Date of Birth: &nbsp;</b>".$dob."<br>";
         print "<br>";
         print "<b>Tag: &nbsp; </b>".$tag."<br>";
         print "<b>Price: &nbsp; </b>".$price."<br>";
         print "<b>Category: &nbsp;</b>".$category."<br>";
         print "<b>Description: &nbsp;</b>".$giftdesc."<br>";
         break;
      }
      case "resetagpw";
      {
         $username = htmlentities($_POST['rauser']);
         $password = htmlentities($_POST['rpassword']);
         $salt = randomString(2);
         $cryptpw = crypt($password, $salt);
         //print "un: ".$username."<br>pw: ".$password."<br>salt:  ".$salt."<br>crypt:  ".$cryptpw."<br>";
         
         //Dump it in the database:
         $query = "UPDATE Account SET password='".$cryptpw."' where userID='".$username."'";
         //print $query."<br>";
         insertData($query);
         
         print "<b>Password updated for:</b>&nbsp;".$username."<br>";
      }
   }
}

include "./template/footer.php";
?>