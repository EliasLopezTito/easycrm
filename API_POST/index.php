<?php

$url = "http://localhost:8000/api/cliente/reingreso/create";

$token = "ZupWuQUrw2vYcH8fzCczPHc5QlTxsK7dB9IhPW42fPRC99i0yIV3iBBtDNGz9T5ECMzN2vCnWSzVKHXTo0Ee3qquxVj52MpbhRLO";

$fields = [
    'nombres' => 'PHP0a1',
    'apellidos' => 'API',
    'dni' => '74215587',
    'celular' => '944158866',
    'email' => 'rdvalloayza@gmail.com',
    'fecha_nacimiento' => date("Y-m-d H:i:s"),
    'provincia_id' => '1',
    'distrito_id' => '1',
    'modalidad_id' => '1',
    'carrera_id' => '1',
    'fuente_id' => '1',
    'enterado_id' => '1'
];

$fields_string = http_build_query($fields);

$response = api_easy_crm($url, $token, $fields_string);
$resArr = array();
$resArr = json_decode($response);
echo "<pre><b>"; print_r($resArr); echo "<b></pre>";

function api_easy_crm($url, $token, $fields_string) {

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => array("Authorization: Bearer ".$token),
        CURLOPT_POSTFIELDS => $fields_string
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}



?>
