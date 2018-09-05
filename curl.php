<?php

$arr_repo = array();

function ret_array_language( $language ){
//step1
$curlsSession = curl_init(); 
//step2 application/vnd.github.v3.html+json

curl_setopt($curlsSession,CURLOPT_HTTPHEADER, ["application/vnd.github.mercy-preview+json"] );
curl_setopt($curlsSession,CURLOPT_URL,"https://api.github.com/search/repositories?q=is:public+language:" . $language . "+sort:updated+page=&per_page=20");
curl_setopt($curlsSession,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0');
curl_setopt($curlsSession, CURLOPT_USERPWD, "aniamembui:gh18#holehole");
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


function check_str( $input_lang_field ){

   
    if(isset($input_lang_field) === true && $input_lang_field === "") {
        return false ;
    }

    else {
    
        return true ;
     }

}


$str_language = trim( $_GET["lang"] ); 

// var_dump (ret_array_language( $str_language ) );
if( check_str( $str_language )) {

    $arr_repo =  ret_array_language( $str_language  ) ;

    //return $arr_repo ;

} 
else{
      echo 'please verify if is empry or more large than 12 chars';
}


// var_dump( $arr_repo );

