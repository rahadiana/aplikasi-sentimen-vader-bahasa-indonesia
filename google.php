<?php

// hasil dari packet capture
// tutorial cara capture request url di android
// https://youtu.be/4l6ejCu3b9M

function DiTranslate($AmbilKalimat,$SourceLang,$TransLang){
 
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://translate.google.com/translate_a/t?client=at&sc=1&v=2.0&sl=$SourceLang&tl=$TransLang&hl=nl&ie=UTF-8&oe=UTF-8&text=".urlencode($AmbilKalimat),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Accept: */*",
      "Accept-Encoding: gzip, deflate",
      "cache-control: no-cache,no-cache",
      "connection: Keep-Alive",
      "content-length: 0",
      "host: translate.google.com",
      "user-agent: AndroidTranslate/2.5.3 2.5.3 (gzip)"
    ),
  ));
  $response = curl_exec($curl);
  
 return $response;
}


