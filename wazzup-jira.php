<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);
define('APP', __DIR__);

$incomingData = file_get_contents("php://input");
$data = json_decode($incomingData, true);

$type=$data['messages'][0]['chatType'];
$contact=$data['messages'][0]['contact']['name'];
$text=$data['messages'][0]['text'];
$url = 'url';

if(($type=='telegram') and ($text!='Здравствуйте! Спасибо, что написали. Мы скоро ответим.'))
{
    $chatId=$data['messages'][0]['chatId'];
    $channelId=$data['messages'][0]['channelId'];
    $phone=$data['messages'][0]['contact']['username'];
    if($phone!=null)
    {
        sleep(3);
        $poisk_lida=poisktelegram($phone);
        if($poisk_lida['total']==0)
        { 
            $request=regtelegramm($url,$contact,$phone);
            if($request)
            {
            $text=$text." Cсылка на диалог: https://app.wazzup24.com/2385-7121/chat/telegram/".$chatId."/".$channelId;
            $dobavlenie=com($url,$request['key'],$text);
            }
        }
    }
    
}


if(($type=='whatsapp') and ($text!='Здравствуйте! Спасибо, что написали. Мы скоро ответим.'))
{
    $chatId=$data['messages'][0]['chatId'];
    $channelId=$data['messages'][0]['channelId'];
    sleep(3);
    $poisk_lida=poisk($chatId);
    if($poisk_lida['total']==0)
    { 
        $request=reg($url,$contact,$chatId);
        if($request)
        {
            $text=$text.
            "Cсылка на диалог: https://app.wazzup24.com/123/chat/whatsapp/".$chatId."/".$channelId;
            $dobavlenie=com($url,$request['key'],$text);
            $dobavlenie=com($url,$request['key'],$text);
        }
    }
}



function poisktelegram($link) 
{
$username = 'Imb-bot'; 
$password = 'ipomenyalparol'; 
$url = 'https://crm.im-business.com';
$jql = 'cf[15903] ~ "' . $link . '"';
$requestUrl = $url . '/rest/api/2/search?jql=' . urlencode($jql);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);
return $result;

}




function poisk($link) 
{
$username = 'Imb-bot'; 
$password = 'ipomenyalparol'; 
$url = 'https://crm.im-business.com';
$jql = 'cf[12800] ~ "' . $link . '"';
$requestUrl = $url . '/rest/api/2/search?jql=' . urlencode($jql);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);
return $result;

}


function com($link, $id_znachenie,$comment) 
{
$username = 'username'; 
$password = 'password'; 
$requestUrl = $link . '/rest/api/2/issue/' . $id_znachenie . '/comment';
$data = array('body' => $comment);
$jsonData = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);   
return $result; 
}



function regtelegramm($url, $contact, $chatId) 
{
$username = 'username'; 
$password = 'password'; 
$requestUrl = $url . '/rest/api/2/issue/';
 $data = array(
'fields' => array(
'project' => array('key' => 'CRM'),
'summary' => "Новый лид wuzzup ".$contact."",
'description' => "Новый лид ".$chatId."",
'issuetype' => array('id' => '10600'), 
'customfield_15903'=>$chatId
)
);
$jsonData = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);
return $result;
}



function reg($url, $contact, $chatId) 
{
$username = 'username'; 
$password = 'password'; 
$requestUrl = $url . '/rest/api/2/issue/';
 $data = array(
'fields' => array(
'project' => array('key' => 'CRM'),
'summary' => "Новый лид wuzzup ".$contact."",
'description' => "Новый лид ".$chatId."",
'issuetype' => array('id' => '10600'), 
'customfield_12800'=>$chatId
)
);
$jsonData = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);
return $result;
}

?>