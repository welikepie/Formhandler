<?php 
include 'replacer.php';
include 'surveycatcher.php';
?>
<html>
    <head>
    </head>
    <body>
  What is your email address?
 <?php 
 $test = "
  <%formstart>
  <%{text = \"Username;\"}{inputtype = \"text\"}{name= \"user\"}>
  <%{text = \"Password;\"}{inputtype = \"password\"}{name= \"pass\"}>
  <%formfinish>";
  echo(formwrangler($test));
  ?>
    </body>
</html>