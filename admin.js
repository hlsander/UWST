function tagSelect()
{
   selectTags = document.getElementById("tag");
   
   redTags = document.getElementById("redtags");
   greenTags = document.getElementById("greentags");
   tredTags = document.getElementById("tredtags");
   tgreenTags = document.getElementById("tgreentags");
   
   if(selectTags.options[2].selected)
   {
      redTags.className = "visibleContent";
      tredTags.className = "visibleContent";
      document.getElementById("fn").removeAttribute("required");
      document.getElementById("ln").removeAttribute("required");
      document.getElementById("dm").removeAttribute("required");
      document.getElementById("dd").removeAttribute("required");
      document.getElementById("dy").removeAttribute("required");
      document.getElementById("gc").removeAttribute("required");
      document.getElementById("rc").setAttribute("required","required");
   }
   else
   {
      redTags.className = "hiddenContent";
      tredTags.className = "hiddenContent";
      document.getElementById("fn").setAttribute("required","required");
      document.getElementById("ln").setAttribute("required","required");
      document.getElementById("dm").setAttribute("required","required");
      document.getElementById("dd").setAttribute("required","required");
      document.getElementById("dy").setAttribute("required","required");
      document.getElementById("gc").setAttribute("required","required");
      document.getElementById("rc").removeAttribute("required");
   }
   
   if(selectTags[1].selected)
   {
      greenTags.className = "visibleContent";
      tgreenTags.className = "visibleContent";
      document.getElementById("fn").setAttribute("required","required");
      document.getElementById("ln").setAttribute("required","required");
      document.getElementById("dm").setAttribute("required","required");
      document.getElementById("dd").setAttribute("required","required");
      document.getElementById("dy").setAttribute("required","required");
      document.getElementById("gc").setAttribute("required","required");
      document.getElementById("rc").removeAttribute("required");
   }
   else
   {
      greenTags.className = "hiddenContent";
      tgreenTags.className = "hiddenContent";
      document.getElementById("fn").removeAttribute("required");
      document.getElementById("ln").removeAttribute("required");
      document.getElementById("dm").removeAttribute("required");
      document.getElementById("dd").removeAttribute("required");
      document.getElementById("dy").removeAttribute("required");
      document.getElementById("gc").removeAttribute("required");
      document.getElementById("rc").setAttribute("required","required");
   }
}

function check4match()
{
   var getPass = document.getElementById("pass").value;
   var getConf = document.getElementById("conf").value;
   
   setPass = document.getElementById("pass");
   setConf = document.getElementById("conf");
   
   //alert("Pass:" + getPass + "Conf" + getConf);
   
   if(getPass == getConf)
   {
      setPass.className = "passmatch";
      setConf.className = "passmatch";
   }
   else
   {
      setPass.className = "passmismatch";
      setConf.className = "passmismatch";
   }
}

function getAgencyName()
{
   var getUsername = document.getElementById("ruser").value;
   var reqURL = "getagency.php?un="+getUsername;
   
   //alert("url:  " + reqURL);
   
   getRequest(reqURL,showOutput,showError);
   return false;
}

function getCityState()
{
   var getZip = document.getElementById("zip").value;
   var reqURL = "getstate.php?zip="+getZip;
   
   //alert("url:  " + reqURL);
   
   getRequest(reqURL,showState,showSError);
   return false;
}

function getAgencyCode()
{
   var getAg = document.getElementById("ragency").value;
   var reqURL = "getaginfo.php?ag="+getAg;
   
   //alert("url:  " + reqURL);
   
   getRequest(reqURL,showAgs,showAError);
   return false;
}

function showAgs(responseText)
{
   var agInfo = responseText.split(',');
   
   var agName = document.getElementById("ragency");
   var agCode = document.getElementById("ragcode");
   agName.value = agInfo[1];
   agCode.value = agInfo[0];
}

function showAError()
{
   var container = document.getElementById("racode");
   container.value = "NUL";   
}

function showState(responseText)
{
   //alert("response:  "+responseText);
   var container = document.getElementById("state");
   container.value = responseText;
}


function showOutput(responseText)
{
   //alert("response:  "+responseText);
   var container = document.getElementById("ragency");
   container.value = responseText;
}

function showError()
{
   var container = document.getElementById("ragency");
   container.value = "ERROR:  Failed to retreive data.";   
}

function oldPasswordChk()
{
   var getPasswd = document.getElementById("oldpass").value;
   var getUsername = document.getElementById("ouser").value;
   var reqURL = "chkold.php?un="+getUsername+"&pw="+getPasswd;
   
   getRequest(reqURL,showPassOut,showPassError);
   return false;
}

function showPassOut(responseText)
{
   //alert("response:  "+responseText);
   var container = document.getElementById("oldstatus");
   container.innerHTML ="<span>"+ responseText + "</span>";
}

function showPassError()
{
   var container = document.getElementById("oldstatus");
   container.innerHTML = "<span>ERROR:  Failed to retreive data.</span>";   
}

function getRequest(url, success, error) 
{
   var req = false;
   try
   {
        // most browsers
        req = new XMLHttpRequest();
   } 
   catch (e)
   {
      // IE
      try
      {
         req = new ActiveXObject("Msxml2.XMLHTTP");
      } 
      catch(e) 
      {
         // try an older version
         try
         {
            req = new ActiveXObject("Microsoft.XMLHTTP");
         } 
         catch(e) 
         {
            return false;
         }
      }
   }
   if (!req) return false;
   if (typeof success != 'function') success = function () {};
   if (typeof error!= 'function') error = function () {};
   
   req.onreadystatechange = function()
   {
      if(req.readyState == 4) 
      {
         return req.status === 200 ? 
         success(req.responseText) : error(req.status);
      }
   }
   
      req.open("GET", url, true);
      req.send(null);
      return req;
}

function passwordChanged() 
{
   var strength = document.getElementById("strength");
   var strongRegex = new RegExp("^(?=.{8,})((?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W)).*$", "g");
   var mediumRegex = new RegExp("^(?=.{8,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
   var enoughRegex = new RegExp("(?=.{7,}).*", "g");
   var pwd = document.getElementById("pass");
 
   if (pwd.value.length==0)  
   {
      strength.innerHTML = 'Type Password';
   } 
   else if (false == enoughRegex.test(pwd.value)) 
   {
      strength.innerHTML = 'More Characters';
   } 
   else if (strongRegex.test(pwd.value)) 
   {
      strength.innerHTML = '<span style="color:green">Strong!</span>';
   } 
   else if (mediumRegex.test(pwd.value)) 
   {
      strength.innerHTML = '<span style="color:orange">Medium!</span>';
   } 
   else 
   {
      strength.innerHTML = '<span style="color:red">Weak!</span>';
   }
   //var strValue = strength.innerHTML;
   //alert("strength: " + strValue);
}

function submitPassword()
{
   var passStrength = document.getElementById("strength").innerHTML;
   var passMatch = document.getElementById("conf").className;
   var pwdStatus = document.getElementById("pwdstatus");
   
   //alert("passMatch:  ="+passMatch+"=");
   
   if((passStrength.indexOf("Weak") != -1) || (passStrength == 'More Characters') || (passStrength == 'Type Password')|| (passStrength == 'Password Strength'))
   {
      pwdStatus.innerHTML = '<span style="color:red">Password requirements not met.  Passwords must be at least 8 characters long, and must contain numbers and letters.</span>';
   }
   else if(passMatch == 'passmismatch')
   {
      pwdStatus.innerHTML = '<span style="color:red">Passwords do not match.  Please retype your passwords.</span>';
   }
   else
   {
      document.passForm.submit();
   }
}

function submitMyPassword()
{
   var passStrength = document.getElementById("strength").innerHTML;
   var passMatch = document.getElementById("conf").className;
   var pwdStatus = document.getElementById("pwdstatus");
   
   //alert("passMatch:  ="+passMatch+"=");
   
   if((passStrength.indexOf("Weak") != -1) || (passStrength == 'More Characters') || (passStrength == 'Type Password')|| (passStrength == 'Password Strength'))
   {
      pwdStatus.innerHTML = '<span style="color:red">Password requirements not met.  Passwords must be at least 8 characters long, and must contain numbers and letters.</span>';
   }
   else if(passMatch == 'passmismatch')
   {
      pwdStatus.innerHTML = '<span style="color:red">Passwords do not match.  Please retype your passwords.</span>';
   }
   else if(document.getElementById("oldstatus").innerHTML == "<span>INCORRECT</span>")
   {
      pwdStatus.innerHTML = '<span style="color:red">You provided an incorrect password.  Please retype your original password and try again..</span>';
   }
   else
   {
      document.myPassForm.submit();
   }
}

function submitNewUser()
{
   var passStrength = document.getElementById("strength").innerHTML;
   var passMatch = document.getElementById("conf").className;
   var pwdStatus = document.getElementById("pwdstatus");
   
   //alert("passMatch:  ="+passMatch+"=");
   
   if((passStrength.indexOf("Weak") != -1) || (passStrength == 'More Characters') || (passStrength == 'Type Password')|| (passStrength == 'Password Strength'))
   {
      pwdStatus.innerHTML = '<span style="color:red">Password requirements not met.  Passwords must be at least 8 characters long, and must contain numbers and letters.</span>';
   }
   else if(passMatch == 'passmismatch')
   {
      pwdStatus.innerHTML = '<span style="color:red">Passwords do not match.  Please retype your passwords.</span>';
   }
   else
   {
      document.nuserForm.submit();
   }
}

function submitMyTags()
{
   var selChk = document.getElementById("tag").value;
   
   if(selChk == "Select Tag Type")
   {
      document.getElementById.innerHTML = '<span style="color:red">Please select an appropriate tag color.</span>';
   }
   else
   {
      document.ENTRY.submit();
   }
}

function showCharsLeft(limitField,limitCount,limitNum)
{
   if (limitField.value.length > limitNum)
   {
      limitField.value = limitField.value.substring(0, limitNum);
   }
   else
   {
      limitCount.value = limitNum - limitField.value.length;
   }
}

