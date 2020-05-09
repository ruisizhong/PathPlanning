<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Instructions</title>
<style>
#ex2_container { text-align:left; font-size:120%;}
#ex1_container { text-align:left; font-size:120%;}
</style>
</head>

<?php

$browser = $_SERVER['HTTP_USER_AGENT'];

$isiPad = 0; //iPad

if ( strpos($browser, "iPad") ) {
        $isiPad = 1;
}


?>
 
<body onload="loadEventHandler()">
<div id="ex2_container">
<br><br><br>
Welcome to our study!<br><br>
<font color='red'><b>IMPORTANT!</b> This study runs on a desktop/laptop.<br><br> 
The study will <b>NOT </b> run on a mobile device.</font><br><br>

<b>Experiment title </b> 
<br><br>Brief description, requirements and consent. <br><br>
The study is expected to take XXX minutes.<br><br>
Thanks for participating!
<br><br>

<p><font size='2'>
Informed Consent <br>
By answering the following questions, you are participating in a study performed by xxx. If you have questions
about this research, please contact XXX. Your participation in this research is voluntary. You may decline to answer any or all of the following questions. You may decline further participation, at any time, without adverse consequences. Your anonymity is assured; the researchers who have requested your participation will not receive
 any personal identifying information about you. By clicking 'I agree' you indicate your consent to participate in this study.
</font></p> <br>

</div>

<div id="ex1_container">
<form name="frm" action="instructions.php" method="post" onsubmit="return validateForm()">
  <input type="text" name="UID" hidden>
  <input type="submit" value="I agree"/>
</form>

</div>

<script>

var task;
var browserok = <?php global  $isiPad; echo  $isiPad; ?>;

function loadEventHandler() {
 
  if (browserok == 1) {
      document.getElementById("ex1_container").innerHTML = "<br><br><font color='red'>IPAD IS NOT SUPPORTED.</font>";
  }
}


function validateForm() {
    var UID = "S";                 // subject ID
    UID = UID+Math.floor((Math.random() * 100000000) + 1); 
    document.forms["frm"]["UID"].value = UID;
    return true;  
}

</script>
</body>
</html>
