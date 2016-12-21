<?php
//=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//  Written by Heather L. Sanders 
//  The Dow Chemical Company
//  hlsanders@dow.com
//
//  Login page
//=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

//=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//
// Revision History:
//
//
//=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

$PageTitle="Sharing Tree - United Way";
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
include "./template/header.php";
include "config.php";

if(isset($_POST['username'])) { $username = $_POST['username']; }
if(isset($_POST['password'])) { $password = $_POST['password']; }


$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);

//Authenticate User
if ($username != "" && $password != "")
{
   $_SESSION['username'] = $username;
   //Check to see if user is authorized to change temporary passwords
   $chkuser = "select * from AccountInfo where userID='$username'";
   $chkresult = mysqli_query($conn, $chkuser) or die ("ERROR IN1010:  Unable to check username ".mysqli_error($conn));
   $chkrow = mysqli_fetch_assoc($chkresult);
   
   $dbpass = $chkrow['password'];
   $salt = substr($dbpass,0,2);
   $crypt = crypt($password, $salt);
   //print "sa:  $salt  <br>cr:  $crypt <br>db: $dbpass<br>";

   if(mysqli_num_rows($chkresult) == 0)
   {
      $loginerror = "<b><font color=\"red\">Username or password incorrect, please try again.</font></b>";
   }
   elseif($crypt != $dbpass)
   {
      $loginerror = "<font color=\"red\">Username or password incorrect, please try again.</font></b>";
   }
   elseif($chkrow['admin'] == 1)
   {
      header("Location: http://$host$uri/admin.php");
   }
   elseif($chkrow['agencyadm'] == 1)
   {
      header("Location: http://$host$uri/agencyadm.php");  
   }
   elseif($chkrow['elf'] == 1)
   {
      header("Location: http://$host$uri/elf.php");
   }
   elseif($chkrow['volunteer'] == 1)
   {
      header("Location: http://$host$uri/volunteer.php");
   }
   else
   {
      $loginerror = "<font color=\"red\">ERROR IN1069:  Account error.</font></b>";
   }
}   
?>

<form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
   <div align="center">
       <p>
        <input type="hidden" name="Action" value="edit">
        <input type="hidden" name="EditId" value="<?=$id?>">
           <table>
              <tr><td>
                <form name="loginForm" action="admin.php" method="POST">
                  <h3> Please login with your username and password</h3><br>
                   <? if ($loginerror != "") { print "$loginerror <br>"; } ?>
                          <b>Username:</b>&nbsp;&nbsp;<input type="text" name="username" size="15"><br><br>
                          <b>Password:</b>&nbsp;&nbsp;&nbsp;<input type="password" name="password" size="15"><br><br>
                          <input type="submit" class="btn" name="submit" value="Login">
                        </form>
                    </td></tr>
                </table>
            </p>
    </div>
</form>


<?
include "./template/footer.php";
?>
