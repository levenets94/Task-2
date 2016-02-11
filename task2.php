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

//echo getChallenge();
//$json = json_decode(getChallenge());
//echo '<br>';
//var_dump($json->result->token);

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

/*Function below returns an error(AUTH_REQUIRED) all the time. Sorry*/

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


/*Function below returns an error(AUTH_REQUIRED) all the time. Sorry*/
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
    echo 'banana';
    return $result;

}

$some_value = describe();
$some_json = json_decode($some_value);

$list = list_type_operations();

$list = json_decode($list);


echo '<pre>';

print_r($list);
//print_r($some_json->result->fields);
echo '</pre>';


echo '<br>';

//var_dump($login);

//var_dump(describe());
var_dump(query_operation());




?>