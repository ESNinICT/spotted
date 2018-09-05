<?php

session_start();

?>
  
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
    
        <form action="./curl.php" method="GET" >
            <div>
                <label for="language"> Programming language:
                    <span class="warning">*(Max 10 letters.)</span> 
                </label>
                <input type="text" id="language" name="lang" pattern="[A-Za-z\s]+" maxlength="10" minlength="1"  required />
            </div>     
        </form>
    
    
    
    </body>
</html>
   
        

