<?php

/*
  <%formstart>
  <%{text = "(text)"}{ inputtype = "(radio,checkbox,text)"}{ name= "(anything)"}{ value="(anything)"}>
  <%formfinish>
 */
$forminstances = array();
$forminfo = array();
$pagesource = "
 <%formstart>
  <%{text = \"RadioStation\"}{inputtype = \"text\"}{ name= \"RadioStation\"}{ value=\"anything\"}>
  <%{text = \"Frequenz\"}{ inputtype = \"text\"}{ name= \"Frequenz\"}{ value=\"anything2\"}>
  <%{text = \"FFH\"}{ inputtype = \"radio\"}{ name= \"FFH\"}{ value=\"anything3\"}>
  <%{text = \"EinsPlus\"}{ inputtype = \"radio\"}{ name= \"EinsPlus\"}{ value=\"anything3\"}>
  <%formfinish>
";

function currentPage() {
    $pageURL = 'http';

    if (key_exists("HTTPS", $_SERVER))
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/*
function pageRead(&$markup) {
    global $forminstances;
    $regex = "/<%formstart\b[^>]*>.*?<%formfinish>/is";
    preg_match_all($regex, $markup, $forminstances);
     foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($forminstances)) as $k => $v) {
        $forminstances[$k] = $v;
    }
   // print_r(formwrangler($markup));
//code here to find out where each bit of forminstances is in the html to replace it.
    //print_r(formwrangler($forminstances[0]));
     if(strval($forminstances[0])==strval($markup)){print_r("iwin");}
    foreach ($forminstances as $key => &$text) {
      //  print_r(formwrangler($forminstances[$key]));
     //  str_replace($forminstances[$key], formwrangler($forminstances[$key]), $markup);
       print_r(formwrangler($forminstances[$key]));
       print_r(formwrangler($markup));
	//       if(strval($forminstances[$key])==strval($markup)){print_r("iwin");}
    //print_r($forminstances[$key]);
    }
    //deal with returned html here. Go through html and replace form mockup with client-ey stuff.
  // print_r($markup);
}
*/

//formwrangler converts form from pseudotagging to HTML.
function formwrangler(&$form) {
    global $forminfo;
    $form = str_replace(" ", "", strval($form)); //remove whitespace from input to make input uniform
    $forminfo = array(); //clear arrays type and value
    $htmlform = ""; //setting htmlform string to empty
    $form = substr($form, strlen('<%formstart>'), strlen($form) - strlen('<%formfinish>')); //Remove formstart and formfinish for processing
    $htmlform.= "<FORM ACTION = \"/amroche/formhandler/tester.php \" METHOD = \"POST\" CLASS=\"FORM\">"; //append form beginning for HTML form
    preg_match_all("/(?<=\{).*?(?=})/is", $form, $forminfo); //match form values against regular expression extracting from between{} and dumping to array
    foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($forminfo)) as $k => $v) {
        $forminfo[$k] = $v; //dropping 2d to 1d array.
    }
    
    foreach ($forminfo as $key => &$info) {
        $exploder = explode("=", $info); //exploding each array item on an equals sign.
        if($exploder[0]=="text" && ($exploder[0]."=".$exploder[1] == $forminfo[0])){ //checking for possible values of exploded text.
        $htmlform.= substr($exploder[1],1,strlen($exploder[1])-2)."<";    //if first, add text then <
        }
        if($exploder[0]=="text" && ($exploder[0]."=".$exploder[1] != $forminfo[0])){
        $htmlform.="><br>".substr($exploder[1],1,strlen($exploder[1])-2)."<";    //else add line break, then text, then <
        }
        
        if($exploder[0]=="inputtype"){
         $htmlform.="input type=".$exploder[1];
        }
        
        if($exploder[0]=="name"){
            $htmlform.=" name=".$exploder[1];
        }
        
        if($exploder[0]=="value"){
            $htmlform.=" value=".$exploder[1];
        }
        
        if($key == (sizeof($forminfo)-1)){ //needs to be at end of if statements.
           $htmlform.="/>\n<br><input type=\"submit\" value=\"Submit\"/></form>"; //at end of form close form off and return.
        }
        
    }   
    return $htmlform;
}

//$filetoread = fopen(currentPage(),'r');
//$pagesource = file_get_contents($filetoread);
//print_r(pageRead($pagesource));
//formwrangler($pagesource); //works perfectly well.
//$pagesource should now be the content we want to push to the website.
//echo("<h1>" + $pagesource + "</h1>");
  

?>