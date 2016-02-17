<?php

define("ADRESS", "https://unknown18.od2.vtiger.com");

function get_challenge(){
    $url = ADRESS.'/webservice.php?operation=getchallenge&username=ivanlevenets94@gmail.com';

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'Content-type: application/x-www-form-urlencoded',
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
    $result=false;
    if($result = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($params)
        )
    )))){
        echo "Succesful Login!!";
    }
    else {
        echo "Something going wrong";
    }

    return $result;



}

$login = login();

function list_type_operations(){
    global $login;
    $json=json_decode($login);
    $url = ADRESS.'/webservice.php?operation=listtypes&sessionName='.$json->result->sessionName;

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'Content-type: application/x-www-form-urlencoded',
        )

    )));
    return $result;

}

function describe(){
    global $login;
    $json=json_decode($login);
    $url = ADRESS.'/webservice.php?operation=describe&sessionName='.$json->result->sessionName.'&elementType=Opportunities';


    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'Content-type: application/x-www-form-urlencoded',
        )

    )));
    return $result;

}

function query_operation($query1=""){
    global $login;
    $query=$query1;
    $json=json_decode($login);

    $url = ADRESS.'/webservice.php?operation=query&sessionName='.$json->result->sessionName.'&query='.$query;

    $result = file_get_contents($url, false, stream_context_create(array(
        'http'=>array(
            'method'=>'GET',
            'header'=>'Content-type: application/x-www-form-urlencoded',
        )

    )));

    return $result;

}

function retrieve(){
    global $login, $challenge;
    $json=json_decode($login);
   // $url = '\"'.ADRESS.'/webservice.php?operation=retrieve&session_name='.$json->result->sessionName.'&id='.$json->result->userId.'\"';
    $uri = ADRESS."/webservice.php?operation=retrieve&sessionName=".$json->result->sessionName."&id=".$json->result->userId;


    $result = file_get_contents($uri, false, stream_context_create(array(
        "http"=>array(
            "method"=>'GET',
//            "header"=>"Accept: */* \r\n".
//                "Accept-Encoding: gzip, deflate \r\n".
//                "Connection: keep-alive \r\n",

        )

    )));
    echo "<br><br><br>".$uri."<br><br><br>";

    return $result;

}


$query_operations = array(
    "Leads"=>str_replace(' ', '%20', "SELECT firstname, lastname, company, email FROM Leads;"),
    "Contacts"=>str_replace(' ', '%20', "SELECT firstname, lastname, email FROM Contacts;"),
    "Users"=>str_replace(' ', '%20', 'SELECT user_name, email1, first_name, last_name FROM Users;'),
    "Accounts"=>str_replace(' ', '%20', 'SELECT accountname, email1, website, phone FROM Accounts;'),
    "Tasks"=>str_replace(' ', '%20', "SELECT subject, date_start, time_start, due_date, taskstatus, taskpriority FROM Calendar;"),
    "Opportunities"=>str_replace(' ', '%20', "SELECT potentialname, amount, closingdate, sales_stage FROM Potentials;"),

);

foreach($query_operations as $key=>$value){

    echo "<pre>";
    echo '<b>'.strtoupper($key).':'.'</b>';
    echo '<br>';
    $query_result = json_decode(query_operation($value))->result;
    foreach($query_result as $result){
        foreach($result as $key=>$value){
            echo $key.": ".$value.'<br>';
        }
        echo "<br>";
    }
    echo "</pre>";
}







?>
