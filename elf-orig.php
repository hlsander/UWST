<?php
$PageTitle="Sharing Tree - United Way";

include "./template/header.php";
include "./formvars.php";
include "./admfunctions.php";

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

if (array_key_exists('chk', $_POST))
{
   if(isset($_POST['chktag']))
   {
      foreach($_POST['chktag'] as $ct)
      {
         $updQ = "UPDATE Tag set elftag='1' WHERE Tag_ID=$ct";
         $updR = mysqli_query($conn, $updQ);
      }
   }
   $clQuery="Select Tag_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, Agency_Name from AllTagInfo where elftag='1'";
   $clResult = mysqli_query($conn, $clQuery);

   if(mysqli_num_rows($clResult) == 0)
   {
      print "There are no tags to display at this time <br>";
   }
   else
   {
   ?>
   <div style="margin-left: 800px;">
    <a href="http://<?echo $host.$uri;?>/index.php"><button type="button" class="btn">Log Off</button></a>
   </div>
   <h3> Thank you so much for your generosity! </h3>
   <div class="scrollContainer">
   <div class="scrollArea">
   <table width="100%" class="results">
   <thead class="results">
   <tr class="results">
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
</div>
<?
}
else
{
   $clQuery="Select Tag_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, Agency_Name from AllTagInfo where elftag='0'";
   $clResult = mysqli_query($conn, $clQuery);

   if(mysqli_num_rows($clResult) == 0)
   {
      print "There are no tags to display at this time <br>";
   }
   else
   {
   ?>
   <div style="margin-left: 800px;">
    <a href="http://<?echo $host.$uri;?>/index.php"><button type="button" class="btn">Log Off</button></a>
   </div>
   <form name="ENTRY" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
   <input type="hidden" name="chk" value="1">
   <div class="scrollContainer">
   <div class="scrollArea">
   <table width="100%" class="results">
   <thead class="results">
   <tr class="results">
    <th class="results"><div class="cid">Client ID</div></th> 
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
      <input type="checkbox" name="chktag[]" value="<?=$clRow['Tag_ID']?>">
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
</div>
<input type="submit" name="Submit Form" class="btn" value="Submit Choices" />
</FORM>
<?
}
      
include "./template/footer.php";
?>