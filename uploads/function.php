<?php

// <!-- FUNCTIONS FOR LANDING START -->
// <!-- FUNCTIONS FOR LANDING END -->

// <!-- FUNCTIONS FOR API.PHP START -->
function translate($q, $sl, $tl){
    $res= file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($q), $_SERVER['DOCUMENT_ROOT']."/transes.html");
    $res=json_decode($res);
    return $res[0][0][0];
}

function logFile($request, $filename) {
    $fOpen = fopen($filename, 'a');
    fwrite($fOpen, urldecode(http_build_query($request)) . "\r\n");
    fclose($fOpen);
}
// <!-- FUNCTIONS FOR API.PHP END -->

// <!-- FUNCTIONS FOR SUCCESS.PHP START -->
// <!-- FUNCTIONS FOR SUCCESS.PHP END -->

?>