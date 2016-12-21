<?php
//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//  Holder script for form variables.
//=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
if(null !== (htmlentities($_POST['Category']))) {$category=htmlentities($_POST['Category']);}
if(null !== (htmlentities($_POST['Tag']))) {$tag=htmlentities($_POST['Tag']);}
if(null !== (htmlentities($_POST['Gift_Description']))) {$giftdesc=htmlentities($_POST['Gift_Description']);}
if(null !== (htmlentities($_POST['Price']))) {$price=htmlentities($_POST['Price']);}
if(null !== (htmlentities($_POST['First_Name']))) {$firstname=htmlentities($_POST['First_Name']);}
if(null !== (htmlentities($_POST['Middle_Initial']))) {$mi=htmlentities($_POST['Middle_Initial']);}
if(null !== (htmlentities($_POST['Last_Name']))) {$lastname=htmlentities($_POST['Last_Name']);}
if(null !== (htmlentities($_POST['DOB_M']))) {$dobm=htmlentities($_POST['DOB_M']);}
$dobm=chop($dobm);
if(null !== (htmlentities($_POST['DOB_D']))) {$dobd=htmlentities($_POST['DOB_D']);}
$dobd=chop($dobd);
if(null !== (htmlentities($_POST['DOB_Y']))) {$doby=htmlentities($_POST['DOB_Y']);}
$doby=chop($doby);
if(null !== (htmlentities($_POST['Phone_Number']))) {$phone=htmlentities($_POST['Phone_Number']);}
if(null !== (htmlentities($_POST['Suffix_Name']))) {$suffix=htmlentities($_POST['Suffix_Name']);}
if(null !== (htmlentities($_POST['DOB']))) {$DOB=htmlentities($_POST['DOB']);}
$dob = $dobm . "/" . $dobd . "/" . $doby;
if(null !== (htmlentities($_POST['Gift_Name']))) {$giftname=htmlentities($_POST['Gift_Name']);}
if(null !== (htmlentities($_POST['Gift_Name_2']))) {$giftname2=htmlentities($_POST['Gift_Name_2']);}
if(null !== (htmlentities($_POST['Gift_2_Price']))) {$price2=htmlentities($_POST['Gift_2_Price']);}
if(null !== (htmlentities($_POST['Pant_Size']))) {$pantsize=htmlentities($_POST['Pant_Size']);}
if(null !== (htmlentities($_POST['Pant_Text']))) {$panttext=htmlentities($_POST['Pant_Text']);}
if(null !== (htmlentities($_POST['Shoes_Size']))) {$shoesize=htmlentities($_POST['Shoes_Size']);}
if(null !== (htmlentities($_POST['Shoes_Text']))) {$shoetext=htmlentities($_POST['Shoes_Text']);}
if(null !== (htmlentities($_POST['Shirt_Size']))) {$shirtsize=htmlentities($_POST['Shirt_Size']);}
if(null !== (htmlentities($_POST['Shirt_Text']))) {$shirttext=htmlentities($_POST['Shirt_Text']);}
if(null !== (htmlentities($_POST['Address']))) {$address=htmlentities($_POST['Address']);}
if(null !== (htmlentities($_POST['City']))) {$city=htmlentities($_POST['City']);}
if(null !== (htmlentities($_POST['State']))) {$state=htmlentities($_POST['State']);}
if(null !== (htmlentities($_POST['Zip_Code']))) {$zip=htmlentities($_POST['Zip_Code']);}
if(null !== (htmlentities($_POST['Gift_Price']))) {$gprice=htmlentities($_POST['Gift_Price']);}
if(null !== (htmlentities($_POST['Gender']))) {$gender=htmlentities($_POST['Gender']);}
if(null !== (htmlentities($_POST['Gift_Value']))) {$gvalue=htmlentities($_POST['Gift_Value']);}
?>