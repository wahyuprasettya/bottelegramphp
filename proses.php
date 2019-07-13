<?php


if (isset($_POST['proses'])) {
    $token ="890876027:AAEk1U7CKWIbckA6B0sGkD7wqFycsVOSfiU";
    $user_id= 621842062;
    $pesan= $_POST ['pesan'];
 
    $data_url=[
    
        'chat_id'=> $user_id,
        'text' => $pesan
    
    ];
    
    
    $get_request_url= 'https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($data_url);
    
    $result = file_get_contents($get_request_url);
    
    if ($result) {
        echo"sukses mengirim  pesan!!!";
    }else {
        echo"gagal mengirim pesan!!!";
    }
    
}


?>