<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Instructions</title>
<style>
#ex2_container { text-align:left; font-size:120%;}
</style>
</head>
 
<body>

<?php

// form parametres
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

$UID =  "";
$validrequest = 0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
   $validrequest = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $validrequest = 1;
   if (!empty($_POST["UID"])) {
      $UID = test_input($_POST["UID"]);
   } else {
      $validrequest = 0;
   }
}

?>

<div id="ex2_container">
<br><b>INSTRUCTIONS (PLEASE READ CAREFULLY)</b><br><br>
Hare are detailed instructions. <br><br>

<form name="frm" action="practice.php" method="post" onsubmit="return validateForm()">
  <input type="text" name="UID" hidden>
  <input type="text" name="firsttrial" hidden><input type="submit" value="Continue"/>
</form>

</div>
</body>

<script>

function validateForm() {
    var u_id = "<?php global  $UID; echo  $UID; ?>";
    document.forms["frm"]["UID"].value = u_id;
    document.forms["frm"]["firsttrial"].value = "true";
    return true;  
}

</script>
</html>
