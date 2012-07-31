<?php

/* -> Survey tool, written in PHP, which doesn't require a database, but writes to XML or JSON file.
  [assume priveleges, but dump php somewhere and js somewhere, and it works. HTML has to be stored within there]
  - dump package somewhere on site, put in basic tags in to page which will generate a form.
  - form, when submitted, writes to ~JSon or XML file.

  tag that generates a form, another tag which generate different attributes generate labels. Allows to generate a whole form using pseudotags.

  ------------------------------------------------------------------------
  Pseudotags in HTML -> ???

  POST to PHP
  strip all inputs of whitespace and tags.
  add all inputs to an array using a while (probably forEach in this case) loop.
  call array, depending on input type add it with a different tag and append to an XML file on success.

  ------------------------------------------------------------------------
  PHP structure
  receive things with a post argument
  for each posted survey result, write to an array (append tags here upon write to array?)

  File writing;
  Open file (if not, create file and add relevant XML tags using a try/catch block)
  -> open whole file, add to string, remove last tags then re-add upon write.
  write to file based on submitted tags.


  For pseudotag replacement;
  scan html on load, find pseudo tags and replace them with html content, then re-load.
  -----------------------------------------------------------------------
 */
$xmlsurveyfile = "responses/surveyanswer.xml"; 
//directory you want your XML file to be placed. Make sure dir exists.
$jsonsurveyfile = "responses/surveyanswer.json";
//directory you want your XML file to be placed. Make sure dir exists.
$modeselect = "2"; 
//0 -> JSON, 1->XML, 2-> JSON $$ XML, 3< -> Neither.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    global $modeselect;
    if ($modeselect == "0" || $modeselect == "2") {
        push_to_JSON();
    }
    if ($modeselect == "1" || $modeselect == "2")
        push_to_XML();
    $url = 'tester.php';
    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$url.'">';  
}

function sanitiseIn(&$value) {
    $value = trim($value);
    $value = strip_tags($value);
}

function push_to_XML() {
    try {	
        global $xmlsurveyfile;
        if (!file_exists($xmlsurveyfile)) {
            $fileHandle = fopen($xmlsurveyfile, 'r');
            file_put_contents($xmlsurveyfile, "<root></root>", LOCK_EX);
            fclose($fileHandle); 
            //if file doesn't exist, create file and write to file and save. If file already exists, it will at least contain this.
        }
        fopen($xmlsurveyfile, 'c+');
        $stringtowrite = file_get_contents($xmlsurveyfile);
    } catch (Exception $e) {}
    $stringtowrite = substr($stringtowrite, 0, strlen($stringtowrite) - 7); //14 is length of </root>, which we remove each time we open the file.
    $stringtowrite.="<form>";
    foreach ($_POST as $key => $value) {
        sanitiseIn($value);
        $stringtowrite .= "<" . $key . ">" . $value . "</" . "$key" . ">";
    }
    $stringtowrite.="</form></root>";
    file_put_contents($xmlsurveyfile, $stringtowrite, LOCK_EX);
}

function push_to_JSON() {
    try { //same constucts as push_to_XML file, just different execution owing to JSON syntax differing.
        global $jsonsurveyfile;
        if (!file_exists($jsonsurveyfile)) {
            $fileHandle = fopen($jsonsurveyfile, 'r');
            file_put_contents($jsonsurveyfile, "{}", LOCK_EX);
            fclose($fileHandle);
            $stringtowrite = file_get_contents($jsonsurveyfile);
            $stringtowrite = substr($stringtowrite, 0, strlen($stringtowrite) - 1); //1 is length of }
            $stringtowrite.="\"form\":{";
        } else {
            $stringtowrite = file_get_contents($jsonsurveyfile);
            $stringtowrite = substr($stringtowrite, 0, strlen($stringtowrite) - 1); //1 is length of }
            $stringtowrite.=",\"form\":{";
        }
        fopen($jsonsurveyfile, 'c+');
    } catch (Exception $e) {
        //print_r('Caught exception: ' . $e->getMessage() . "\n");
    }

    foreach ($_POST as $key => $value) {
        sanitiseIn($value);
        //print_r("<br >" . $key . "<br>" . $value);
        $stringtowrite .= "\"" . $key . "\"" . ":" . "\"" . $value . "\"" . ",";
    }
    
    $stringtowrite = substr($stringtowrite, 0, strlen($stringtowrite) - 1);
    $stringtowrite.="}}";
    file_put_contents($jsonsurveyfile, $stringtowrite, LOCK_EX);
}

?>