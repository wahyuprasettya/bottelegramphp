<?php


$TOKEN      = "890876027:AAEk1U7CKWIbckA6B0sGkD7wqFycsVOSfiU";
$usernamebot= "@WahyuAdjie_bot"; 
$debug = false;
 
function request_url($method)
{
    global $TOKEN;
    return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}

function get_updates($offset) 
{
    $url = request_url("getUpdates")."?offset=".$offset;
        $resp = file_get_contents($url);
        $result = json_decode($resp, true);
        if ($result["ok"]==1)
            return $result["result"];
        return array();
}

function send_reply($chatid, $msgid, $text)
{
    global $debug;
    $data = array(
        'chat_id' => $chatid,
        'text'  => $text,
        'reply_to_message_id' => $msgid  
    );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options); 
    $result = file_get_contents(request_url('sendMessage'), false, $context);
    if ($debug) 
        print_r($result);
}
 
function create_response($text, $message)
{
    global $usernamebot;
    
    $hasil = '';  
    $fromid = $message["from"]["id"]; 
    $chatid = $message["chat"]["id"]; 
    $pesanid= $message['message_id']; 
    isset($message["from"]["username"])
        ? $chatuser = $message["from"]["username"]
        : $chatuser = '';
    isset($message["from"]["last_name"]) 
        ? $namakedua = $message["from"]["last_name"] 
        : $namakedua = '';   
    $namauser = $message["from"]["first_name"]. ' ' .$namakedua;
    $textur = preg_replace('/\s\s+/', ' ', $text); 
    $command = explode(' ',$textur,2); //
    switch ($command[0]) {
        
        case '/id':
        case '/id'.$usernamebot : 
            $hasil = "$namauser, ID kamu adalah $fromid";
            break;
        
        case '/time':
        case '/time'.$usernamebot :
            $hasil  = "$namauser, waktu lokal bot sekarang adalah :\n";
            $hasil .= date("d M Y")."\nPukul ".date("H:i:s");
            break;
        case '/hai':
            $hasil  = "hai..".$namauser;
            break;
        case '/waktusholat':
           
            $ApiKey="03ffa2acf504aad38e9151c7b5e043fb";
            $get_data='https://muslimsalat.com/jakarta.json?key='.$ApiKey;
            $result = file_get_contents($get_data);
            $data = json_decode($result);
            $hasil ="maghrib : ".$data->items[0]->maghrib "\n";
            $hasil ="isya    : ".$data->items[0]->maghrib "\n";
            $hasil ="subuh   : ".$data->items[0]->maghrib "\n";
            $hasil ="ashar   : ".$data->items[0]->maghrib "\n";
            $hasil ="dhuhur  : ".$data->items[0]->maghrib "\n";

            break;

        case 'variable':
            # code...
            break;
        
        default:
            $hasil = 'Terimakasih, pesan telah kami terima.';
            break;
    }
    return $hasil;
}
 

function process_message($message)
{
    $updateid = $message["update_id"];
    $message_data = $message["message"];
    if (isset($message_data["text"])) {
    $chatid = $message_data["chat"]["id"];
        $message_id = $message_data["message_id"];
        $text = $message_data["text"];
        $response = create_response($text, $message_data);
        if (!empty($response))
          send_reply($chatid, $message_id, $response);
    }
    return $updateid;
}
 

$entityBody = file_get_contents('php://input');
$pesanditerima = json_decode($entityBody, true);
process_message($pesanditerima);


    
?>




    