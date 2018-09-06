<?php


session_start();
$_SESSION = array();
/*session_unset();
session_destroy();
unset( $_SESSION['cur_page'] );
*/


?>
  
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
    
        <form action="./langRepo.php" method="GET" >
            <div>
                <label for="language"> Programming language:
                    <span class="warning">*(Max 10 letters.)</span> 
                </label>
                <input type="text" id="language" name="lang" pattern="[A-Za-z\+\#\s]+" maxlength="10" minlength="1"  required />
                
                <input type="submit" value="Send">
            </div>     
        </form>
    
    
    
    </body>
</html>

<?php
// var_dump ( $_SESSION ) ;
?>
   
        

