<!DOCTYPE html>
<html>
<head>
<title>
Experiment</title>
<style>
table, tr, th, td {
    border:1px solid black;
    border-collapse: collapse;
    width:absolute;
    height:absolute;
}
h1 {
    text-align: center;
}

h2{
    text-align: center;
}

#ex1_container { align:center; text-align: center;}

</style>
</head>
<body onload="loadEventHandler()">

<?php

// form parametres
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

// define variables and set to empty values
$dir = "data";
$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validrequest = 0;

if (!is_writable($dir)) {
    echo 'The directory is not writable ' . $dir . '<br>';
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $UID = "dodgyuser";
        $s = $dir . "/" . $UID . ".txt";
        $f = fopen($s, "a") or die("Unable to open file!" . $s);
	fwrite($f, $ip . " ". $date . " " . $browser . " GET request to finished_practice.php \n");
        fclose($f);
        echo "Err: Get request received.<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $validrequest = 1;
   if (!empty($_POST["UID"])) {  
      $UID = test_input($_POST["UID"]);
      if ( !strcmp($UID, "dodgyuser") ) {
        $validrequest = 0;
      } 
   } else {
      $validrequest = 0;
      $UID = "dodgyuser"; 
   }

   $trialno = test_input($_POST["trialno"]);
   $trialid = test_input($_POST["trialID"]);
   $time = test_input($_POST["time"]);
   $stimulusname = test_input($_POST["name"]);
   $resp = test_input($_POST["resp"]);

   $s = $dir . "/" . $UID . ".txt";
   $f = fopen($s, "a") or die("101 Unable to open file!" . $s);
   fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $trialno . " " .  $stimulusname . " " . $time . " " . $resp . "\n");
   fclose($f);

}

if ($validrequest == 1) {
  echo "<p  align='left'>Congratulations! You have finished the practice. We will now move on to the actual study. ";
} else {
  echo "<h2>Err: Invalid Request</h2>\n";
}


?>

<script>

var mn = <?php global $trialno; echo $trialno; ?>;
var rmn = <?php global $trialid; echo $trialid; ?>;
var mf = "<?php global $stimulusname;  echo  $stimulusname; ?>";
var u_id = "<?php global  $UID; echo  $UID; ?>";
var savedtime = "";

function generate_table() {
   var f = "<br><form name='frm' action='test.php'";

   f = f + " method='post' onsubmit='return submitForm()'>" +
        "<fieldset style='border:0'>" +
            "<input type='text' name='firsttrial' hidden>" + 
            "<input type='text' name='UID' hidden>" +
            "<input type='submit' id = 'sub' value='Submit'/>" +
        "</fieldset>" +
      "</form>";
   return f;
}


function loadEventHandler() {
   document.getElementById("ex1_container").innerHTML = generate_table(); 

   var now= new Date(),
   h= now.getHours(),
   m= now.getMinutes(),
   s= now.getSeconds();
   ms = now.getMilliseconds();

   var times = "t(" + h + "," + m + "," + s + "," + ms + ");";
   savedtime += times;
}


function submitForm() {
       var now= new Date(),
       h= now.getHours(),
       m= now.getMinutes(),
       s= now.getSeconds();
       ms = now.getMilliseconds();

       times = "t(" + h + "," + m + "," + s + "," + ms + ");";
       savedtime += times;

       document.forms["frm"]["UID"].value = u_id;
       document.forms["frm"]["firsttrial"].value = "true";
       return true;
}

</script>

<div id="ex1_container">
</div>

</body>
</html> 
