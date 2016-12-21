<?php
$PageTitle="Sharing Tree - United Way";

include "./template/header.php";
include "./formvars.php";
include "./admfunctions.php";

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

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);
$agency = getAgencyName($username);

?>
 <br>
 <hr>
 <div style="width: 100%; overflow: hidden;">
 <div style="width: 300px; float: left;">
 <FORM name="doForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="chk" value="1">
     &nbsp;&nbsp; What Would You Like to Do?<br><br>

   &nbsp;&nbsp;<SELECT name="job" onChange="doForm.submit()">
    <OPTION SELECTED value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;Select an Option&gt; </OPTION>
      <OPTION value="resetpwd">Reset My Password</OPTION>
      <OPTION value="checkin">Check In Gifts</OPTION>
      <OPTION value="viewtags">View Checked in Tags</OPTION>
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
      case "resetpwd";
      {         
 ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="myPassForm">
<input type="hidden" name="pass2" value="resetagpw">
<table class="editor">
<tr class="editor">
 <td class="editor"><div class="ed2">Username:</td></div>
 <td class="editor"><div class="ed3"><input type="text" name="rauser" id="ouser" value="<?=$username?>" readonly="true"/></div></td>
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
      case "checkin";
      {
         $clQuery="Select AgTag, Tag_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, Agency_Name from AllTagInfo";
     
         $clQuery=$clQuery." where Agency_Name = '".$agency."' AND checkedin='0' ORDER BY Tag_ID ASC";
         //print "clQuery:  $clQuery<br>";
         
         $clResult = mysqli_query($conn, $clQuery);

         if(mysqli_num_rows($clResult) == 0)
         {
            print "There are no tags to display at this time <br>";
         }
         else
         {
?>
  
   <form name="ENTRY" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="chk" value="1">
   <input type="hidden" name="job" value="seetags">
   <div class="scrollContainer">
   <div class="scrollArea">
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
   <tr class="results" onclick="window.open('http://<?=$host?><?=$uri?>/vedit.php?content=<?=$bpass?>','_blank','location=yes,height=850,width=620,scrollbars=yes,status=yes');">
    <td class="results">
    <div class="cid">
      <?=$clRow['AgTag']?>
    </div>  
    </td>
    <td class="results">
    <div class="an">
      <?=$clRow['Agency_Name']?>
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
   
<?
               
            } 
         }
            
?>
</tbody>
</FORM>
</table>
</div>
</div>
<?         
         break;
      }
      case "viewtags";
      {
         $clQuery="Select AgTag, Tag_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, Agency_Name from AllTagInfo";
     
         $clQuery=$clQuery." where Agency_Name = '".$agency."' AND checkedin='1' ORDER BY Tag_ID ASC";
         //print "clQuery:  $clQuery<br>";
         
         $clResult = mysqli_query($conn, $clQuery);

         if(mysqli_num_rows($clResult) == 0)
         {
            print "There are no tags to display at this time <br>";
         }
         else
         {
?>
  
   <form name="ENTRY" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="chk" value="1">
   <input type="hidden" name="job" value="seetags">
   <div class="scrollContainer">
   <div class="scrollArea">
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
?>

   <tbody class="results">
   <div class="edit">
   <div class="tooltip">
   <tr class="results">
    <td class="results">
    <div class="cid">
      <?=$clRow['AgTag']?>
    </div>  
    </td>
    <td class="results">
    <div class="an">
      <?=$clRow['Agency_Name']?>
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
   
<?
               
            } 
         }
            
?>
</tbody>

</table>
</div>
<p><span id="pwdstatus"></span></p>
</div>
<?         
         break;
      }               
   }
}