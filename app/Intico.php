<?php


namespace NavegapComprame;


class Intico
{

    static function sendSMS($phone, $message){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,App::$INTICO_WSB);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"usuario=".App::$INTICO_USER."&password=".App::$INTICO_PASSWORD."&celular=".$phone."&mensaje=".$message."&senderId=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
    }
}