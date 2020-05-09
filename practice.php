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
$randTrialNo = 0;
$UID =  "";
$ip=$_SERVER['REMOTE_ADDR'];
$date = date('d/F/Y h:i:s'); // date of the visit that will be formated this way: 29/May/2011 2512:20:03
$browser = $_SERVER['HTTP_USER_AGENT'];
$browser = str_replace(' ', '_', $browser);
$validrequest = 0;
$trialno = 0;

$practice_dir = "practice"; 
$practice_names = scandir("webfile/stimuli/" . $practice_dir);
$num_practice = count($practice_names) - 2;

if (!is_writable($dir)) {
    echo 'The directory is not writable ' . $dir . '<br>';
}

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
  
   if( !empty($_POST["firsttrial"]) ) {
      $trialno = 0;

      $first = test_input($_POST["firsttrial"]);

      // generate a random sequence of trials for this user

      $m = array();
      for ($x = 0; $x < $num_practice; $x++) {
        $m[] = $x;
      }

      // permute randomly
      for ($x = 0; $x < count($m); $x++) {
        $pickone = rand(0, count($m)-1);
        if ($pickone <> $x) { 
    		$temp = $m[$x];
        	$m[$x] = $m[$pickone];
        	$m[$pickone] = $temp;
        }
      } 

      // save to file

      $f = fopen($dir . "/" . $UID . "practice_sequence.txt", "a") or die("Unable to open file!" . $UID . "sequence");
      for ($x = 0; $x < count($m); $x++) {
         fwrite($f, $m[$x] . "\n");
      }
      fclose($f);
      $randomisedTrial = $m[0];

   }
   else {
        $s = $dir . "/" . $UID . ".txt";
        $f = fopen($s, "a") or die("101 Unable to open file!" . $s);

        $trialid = test_input($_POST["trialID"]);
        $time = test_input($_POST["time"]);
        $stimulusname = test_input($_POST["name"]);
        $resp = test_input($_POST["resp"]);
 
        fwrite($f, $ip . " ". $date . " " . $browser . " " . $UID . " " . $trialno . " " .  $stimulusname . " " . $time . " " . $resp . "\n");
        fclose($f);
        advanceTrial();
   }
}

$stimulusfile = $practice_dir . "/" . $practice_names[$randomisedTrial + 2 ];

if ($validrequest == 1) {
  if ($trialno < $num_practice) {
    $txt = $trialno+1 . " of " . $num_practice;
    $txt = "Practice " . $txt;
    echo "<p  align='left'>Let&#39;s practice!<br><br>";
  } 

  echo "<h2>$txt</h2>\n";
} else {
  echo "<h2>Err: Invalid Request</h2>\n";
}

function advanceTrial() {
    global $trialno, $randomisedTrial, $UID, $dir;
    $trialno = $trialno + 1;
    $randomisedTrial = $trialno;

    // is this a practice or a real trial?
    $s = $dir . "/" . $UID . "practice_sequence.txt";
    $f = fopen($s, "r") or die("102: Unable to open file! " . $s);
    for ($x = 0; $x <= $trialno; $x++) {
           $randomisedTrial = intval(fgets($f));
    }
    fclose($f);
}

?>

<script>

var trialno = <?php global $trialno; echo $trialno; ?>;
var rmn = <?php global $randomisedTrial; echo $randomisedTrial; ?>;
var trialfile = "<?php global $stimulusfile;  echo  $stimulusfile; ?>";
var u_id = "<?php global  $UID; echo  $UID; ?>";
var savedtime = "";
var num_practice = <?php global $num_practice;  echo $num_practice; ?>;
 

function loadEventHandler() {
   if (trialno >= num_practice-1 ) {
     document.forms["frm"].action = "finished_practice.php";
   }

   document.getElementById("trialimage").src = "webfile/stimuli/" + trialfile; 

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

       if ( document.forms["frm"]["resp"].value  == null || document.forms["frm"]["resp"].value  == "" ) {
         alert("Please answer the question.");
         return false;
       }

       document.forms["frm"]["UID"].value = u_id;
       document.forms["frm"]["trialno"].value = trialno;
       document.forms["frm"]["trialID"].value = rmn;
       document.forms["frm"]["time"].value = savedtime;
       document.forms["frm"]["name"].value = trialfile;
       return true;
}

</script>

<div id="ex1_container">
  Which ice-cream do you want?<br><br> 
  <img src='' width='700' height='400' id = 'trialimage'>
  <br><form name='frm' action='practice.php' method='post' onsubmit='return submitForm()'>
   <fieldset style='border:0'>
   <input type='radio' name='resp' id='r1' value='1' />&nbsp; 1</>
   <input type='radio' name='resp' id='r2' value='2' />2</>
   <input type='radio' name='resp' id='r3' value='3' />3</>
   <input type='radio' name='resp' id='r4' value='4' />4</>
   <input type='radio' name='resp' id='r5' value='5' />5 &nbsp; </><br><br>
   <input type='text' name='trialno' hidden>
   <input type='text' name='UID' hidden>
   <input type='text' name='name' hidden>
   <input type='text' name='time' hidden>
   <input type='text' name='trialID' hidden>
   <input type='submit' id = 'sub' value='Submit'/>
  </fieldset>
 </form>
</div>

</body>
</html> 
