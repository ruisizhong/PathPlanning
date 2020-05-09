<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Thank you!</title>
<style>
#ex2_container { text-align:center; font-size:120%;}
</style>
</head>

<?php
// form parametres
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

$dir = "data";

$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
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

    if (!empty($_POST["trialno"])) {
      $trialno = test_input($_POST["trialno"]);
    }

    if (!empty($_POST["time"])) {
      $time = test_input($_POST["time"]);
    }
    
    if (!empty($_POST["name"])) {
      $stimulus = test_input($_POST["name"]);
    }

    if (!empty($_POST["resp"])) {
      $resp = test_input($_POST["resp"]);
    }

    if ( $validrequest == 0 ) {
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");
       fwrite($f, $ip . " ". $date . " " . $browser . " Malformed POST request to questions.php with bad uid\n");
       fclose($f);
    } else {  
       
       $s = $dir . "/" . $UID . ".txt";
       $f = fopen($s, "a") or die("Unable to open file!");

       if (!empty($stimulus)) {
          fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $trialno . " " .  $stimulus . " " . $time . " " . $resp . "\n");
       } else {
          echo "<br> Err: invalid parameters received <br>"; 
       }
       fclose($f);
     }
}

?>
 
<body onload="loadEventHandler()">
<div id="ex2_container">
<br>Thanks! We are almost done.<br><br>
   Please answer the following questions:<br><br>
   <form name='frm' action='theend.php' method='post' onsubmit='return validateForm()'>
   How did you make your decisions?<br>
   <textarea name='decision' rows='5' cols='70'></textarea><br><br><br>
   <input type='text' name='UID' hidden><br>
   <input type='submit' value='Submit'/>
   </form>
</div>

<script>
var uid =  "<?php global $UID; echo $UID; ?>";

function loadEventHandler() {
   var valid =  <?php global $validrequest; echo $validrequest; ?>;
   if (!valid) {
      document.getElementById("ex2_container").innerHTML = "Bad Request";
   }
}


function validateForm() {
      document.forms["frm"]["UID"].value = uid;
      var x = document.forms["frm"]["decision"].value;
      if (x == null || x == "" || x == 0) {
          alert("Please answer the questions to proceed." );
          return false;
      }
     
      return true;
}

</script>
</body>
</html>
