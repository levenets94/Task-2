<?php

define('ADRESS', 'https://unknown18.od2.vtiger.com');

function get_challenge(){
    $url = ADRESS.'/webservice.php?operation=getchallenge&username=ivanlevenets94@gmail.com';

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'CContent-type: application/x-www-form-urlencoded',
        )

    )));
    return $result;

}

$challenge = get_challenge();

function login(){
    global $challenge;
    $json = json_decode($challenge);

    $url = ADRESS.'/webservice.php';

    $params = array(
        'operation' => 'login',
        'username' => 'ivanlevenets94@gmail.com',
        'accessKey'=>md5($json->result->token.'h6psGhlTAoRVH6J'),
    );
    $result = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($params)
        )
    )));

    return $result;
//var_dump(json_decode($result));
//echo $result;


}

$login = login();

function list_type_operations(){
    global $login;
    $json=json_decode($login);
    $url = ADRESS.'/webservice.php?operation=listtypes&sessionName='.$json->result->sessionName;

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'CContent-type: application/x-www-form-urlencoded',
        )

    )));
    return $result;

}

function describe(){
    global $login;
    $json=json_decode($login);
    $url = ADRESS.'/webservice.php?operation=describe&sessionName='.$json->result->sessionName.'&elementType=Contacts';

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'CContent-type: application/x-www-form-urlencoded',
        )

    )));
    return $result;

}

function query_operation(){
    global $login;

    $json=json_decode($login);
    $query = "SELECT firstname FROM Leads ";
    $url = ADRESS.'/webservice.php?operation=query&sessionName='.$json->result->sessionName.'&query='.$query;

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'CContent-type: application/x-www-form-urlencoded',
        )

    )));
    return $result;

}

function retrieve(){
    global $login, $challenge;
    $json=json_decode($login);
    $url = ADRESS.'/webservice.php?operation=retrieve&session_name='.$json->result->sessionName.'&id='.'Contacts';



    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'CContent-type: application/x-www-form-urlencoded',

        )

    )));

    return $result;

}




$query_json=json_decode(query_operation());
$retrieve_json=json_decode(retrieve());


$array = array(
    'challenge'=>json_decode($challenge),
    'login'=>json_decode($login),
    'list_type'=>json_decode(list_type_operations()),
    'describe'=>json_decode(describe()),
    'query'=>json_decode(query_operation()),
    'retrieve'=>json_decode(retrieve())


);


foreach($array as $key=>$value){

    echo "<pre>";
    echo strtoupper($key).':';
    echo '<br>';
    print_r($value);

    echo "</pre>";
}







?>