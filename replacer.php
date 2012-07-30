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
       if(strval($forminstances[$key])==strval($markup)){print_r("iwin");}
    //print_r($forminstances[$key]);
    }
    //deal with returned html here. Go through html and replace form mockup with client-ey stuff.
  // print_r($markup);
}

//formwrangler converts form from pseudotagging to HTML.
function formwrangler(&$form) {
    global $forminfo;
//clear arrays type and value
    $form = str_replace(" ", "", strval($form));
    // print_r($form);
    $forminfo = array();
    $htmlform = "";
    $form = substr($form, strlen('<%formstart>'), strlen($form) - strlen('<%formfinish>'));
    $htmlform.= "<FORM ACTION = \"/amroche/formhandler/tester.php \" METHOD = \"POST\" CLASS=\"FORM\">";
    preg_match_all("/(?<=\{).*?(?=})/is", $form, $forminfo);
    foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($forminfo)) as $k => $v) {
        $forminfo[$k] = $v;
    }
    
//print_r(sizeof($forminfo)+"sizeof");
    foreach ($forminfo as $key => &$info) {
       // print_r($key);   
        $exploder = explode("=", $info);
        //print_r($exploder[0]."=".$exploder[1]);
//print_r($forminfo[$key]);     
//   print_r($exploder);
        if($exploder[0]=="text" && ($exploder[0]."=".$exploder[1] == $forminfo[0])){
        $htmlform.= substr($exploder[1],1,strlen($exploder[1])-2)."<";    
        
//print_r(strval($htmlform));
        }
        if($exploder[0]=="text" && ($exploder[0]."=".$exploder[1] != $forminfo[0])){
        $htmlform.="><br>".substr($exploder[1],1,strlen($exploder[1])-2)."<";    
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
           $htmlform.="/>\n<br><input type=\"submit\" value=\"Submit\"/></form>";
        //  print_r("</form> LOL!");
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