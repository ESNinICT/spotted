<?php

require './db.php';

session_start();

$arr_repo = array();



isset( $_SESSION['cur_page'] )  ? $_SESSION['cur_page']++ :   $_SESSION['cur_page'] = 1 ;
// var_dump ( $_SESSION['cur_page'] ); 

/* Database Singleton pattern instantiation  */
$db = Database::getInstance() ; 

$timeFrom2MonthsAgo = time() - ( 60 *  24 * 60 * 60) ;
$dateFrom2MonthsAgo = (date("Y-m-d", $timeFrom2MonthsAgo )) ;

// var_dump($dateFrom2MonthsAgo  );

/* text file to load languages availables to search   */
$lang_availables = file('progr_lang.txt', FILE_IGNORE_NEW_LINES );

/*  route to repeat the query and return new results  */
$route = $_SERVER["REQUEST_URI"] ;


/* function receives a VALID string @language  and return RESPONSE JSON object*/ 
function ret_array_language( $language ){

//step1
$curlsSession = curl_init(); 
curl_setopt($curlsSession,CURLOPT_HTTPHEADER, ["step2 application/vnd.github.v3.html+json"] ) ;
// curl_setopt($curlsSession,CURLOPT_HTTPHEADER, ["application/vnd.github.mercy-preview+json"] );
// curl_setopt($curlsSession,CURLOPT_HTTPHEADER, ["application/vnd.github.v3.text-match+json"] ) ; 
curl_setopt($curlsSession,CURLOPT_URL,  "https://api.github.com/search/repositories?q=is:public+language:" . $language . "+sort:updated&page=" . $_SESSION['cur_page']);
curl_setopt($curlsSession,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0');
// curl_setopt($curlsSession, CURLOPT_USERPWD, "aniamembui:gh18#holehole");
// curl_setopt($curlsSession, CURLOPT_NOBODY, true);
curl_setopt($curlsSession, CURLOPT_HEADER, 1) ;
curl_setopt($curlsSession,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curlsSession,CURLOPT_HEADER, false); 
//step3
$result=curl_exec($curlsSession);
//step4
curl_close($curlsSession);
//step5
//echo $result;
$data = json_decode($result );

return  $data ;

}

/* TRIM and convert to lower the string , to compare against the LANGUAGE list */
$input_lang_field = trim( $_GET['lang'] ) ;
$input_lang_field = strtolower( $input_lang_field );


if ( isset($input_lang_field) === true && $input_lang_field === ""  ){
             echo "THe field is empty .";   
             exit();
         }

if (!in_array( $input_lang_field , $lang_availables ) ){
    echo "That language don't exist on our database" ;
    header( "refresh:3; url=search_lang.php" );
    exit();
    }         
         

$arr_repo =  ret_array_language( $input_lang_field  ) ;    




    
if( $arr_repo->total_count !== 0 ){    
   $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="imagetoolbar" content="false">
    <meta name="MSSmartTagsPreventParsing" content="true">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        table,td {
		border: 1px solid #333;
	    }

	    thead,
	    tfoot {
		background-color: #333;
		color: #fff;
	    }
    
    
    
    
    </style>
    
  </head>
  <body>

    <table>
      <caption style="font-size:26px;"> <?= strtoupper( $input_lang_field ) ?> repositories <br><br> </caption>
      <thead>
	<tr>
	  <th scope="col">Name</th>
	  <th scope="col">Full Name</th>
	  <th scope="col">Creation Date</th>
<!-- 	  <th scope="col">Repository</th> -->
	  <th scope="col">Stars</th>
	  <th scope="col">Watchers</th>
	  <th scope="col">Forks</th>
	  <th scope="col">Last Commit</th>
	  <th scope="col">Description</th>
	</tr>
      </thead>



<?php
   	
   	
   	 
     foreach( $arr_repo->items as $it ){
       
      /* Verify last "pushed" time (   ["pushed_at"] prop ), if it's less  than 60 days old, AND ..... */
        if( strtotime( $it->pushed_at) >  $timeFrom2MonthsAgo   &&
      /*..... IF USER NAME is more than 5 chars long; PRINT and LOAD in DD>BB. IF not , just skip      */
          strlen( $it->name ) > 5 
          )
     	{      
                     $name =  $it->name               ;
                     $full_name = $it->full_name      ; 
                     $created_at =  $it->created_at   ;
//                      $repo_name = null ; 
                     $stars_count = $it->stargazers_count ;
                     $watchers = $it->watchers_count  ;
                     $forks = $it->forks_count        ;
                     $description =  $it->description ;
	             $last_commit =  $it->pushed_at   ; 
            
            echo "<tr><td>" .$name . "</td><td>" . $full_name . "</td><td>" . $created_at  . "</td><td>" . $stars_count . "</td><td>" .  $watchers. "</td><td>" .  $forks . "</td><td>" . $last_commit . "</td><td>" . $description . "</td></tr>" ;  
            $q = "insert into spotted ( name     , full_name     , created_at    , last_commit    , stars          , watchers   , forks    , description ) 
                                            values ( '{ $name }', '{ $full_name }','{ $created_at }','{ $last_commit }', { $stars_count },{ $watchers}, { $forks}, { $description } ) " ;
	    
	    $q = $db->escape( $q) ;
	    $db->query( $q ) ;
            
            /*
                  echo $it->name . " || " . $it->full_name . " || " . $it->created_at . " || " . 
	              $it->name . " || " . $it->stargazers_count . " || ". $it->watchers_count . " || ". $it->forks_count . " || ". $it->description . 
	              " || Pushed at :". $it->pushed_at ."<br>" ;
	   */
        }
      
        
    }
    
?>

       </tbody>
     </table>

<?php    
    
    
    echo "<a href=\"" . $current_url ."\" >Next Results </a><br><br>
	  <a href=\"./search_lang.php \"><strong>NEW SEARCH</strong></a>" ;
}
    else{    
      echo $str_language . " : this language  exist but there is not repository in the  database" ;
      header( "refresh:3; url=search_lang.php" );
      exit();
}

/*
echo "<br>--------------------------------------------------------------------------------<br>" ;
var_dump( $arr_repo ) ;
echo "<br>--------------------------------------------------------------------------------<br>" ;

*/
  
// var_dump( $arr_repo->items );

/*
$ahora = time() ;


echo "<br><br>La fecha hace 2 meses fue " . date('m/d/Y', $a2meses )  . " y ahora es " . date('m/d/Y', $ahora )  ;
echo "<br>"  . $dateFrom2MonthsAgo ; 

*/

?>

   </body>
 </html>



