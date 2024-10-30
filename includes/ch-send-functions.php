<?php
function channeller_sendaudio($chat_id, $caption, $photo,$logger,$audiotime,$audioperformer,$audiotitle)
{
	$options = get_option( 'tchannel_settings' );
    $token = $options['tchannel_text_token'];
	$current_path = str_replace('/wp-admin','',getcwd()); 
	$photo = str_replace(get_bloginfo('url') , '', $photo);
	$photo = $current_path.$photo;
	$seconds = '';
	if (!$audiotime == '') {
		$timer = explode(':',$videotime);
		$seconds = $timer[0]*60+$timer[1];
	}
	$chatter = get_site_option( $chat_id );

	$fields = array(
        'chat_id' => $chatter,
         // make sure you do NOT forget @ sign
         'audio' =>
          '@'            . $photo
          . ';filename=' . $photo,
        'title' => $audiotitle,
        'performer' => $audioperformer,
        'duration' => $seconds
    );
    

    $url = 'https://api.telegram.org/bot'.$token.'/sendAudio';

    //  open connection
    $ch = curl_init();
    //  set the url
    curl_setopt($ch, CURLOPT_URL, $url);
    //  number of POST vars
    curl_setopt($ch, CURLOPT_POST, count($fields));
    //  POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    //  To display result of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //  execute post
    $result = curl_exec($ch);
    //  close connection
    curl_close($ch);
	$result = json_decode ($result);
	if ($result->ok == 1) {
		$resulter = __( 'Sent', 'tchannel' );
	} else {
		$resulter = $result->description;
	}
	$options = get_option( 'tchannel_settings' );
    $logview = $options['tchannel_log'];
	if ($logview == 'yes'){
	channeller_log(__( 'Send Audio', 'tchannel' ), $resulter ,$chat_id,$logger);
	}
}
function channeller_sendvideo($chat_id, $caption, $photo,$logger,$videotime)
{
	$options = get_option( 'tchannel_settings' );
    $token = $options['tchannel_text_token'];
	$current_path = str_replace('/wp-admin','',getcwd()); 
	$photo = str_replace(get_bloginfo('url') , '', $photo);
	$photo = $current_path.$photo;
	$seconds = '';
	if (!$videotime == '') {
		$timer = explode(':',$videotime);
		$seconds = $timer[0]*60+$timer[1];
	}
	$chatter = get_site_option( $chat_id );
	$caption = truncate_channeller ($caption , 200);
	if ($caption == '') {
		$fields = array(
        'chat_id' => $chatter,
         // make sure you do NOT forget @ sign
         'video' =>
          '@'            . $photo
          . ';filename=' . $photo,
        'duration' => $seconds
    );
	} else {
		$fields = array(
        'chat_id' => $chatter,
         // make sure you do NOT forget @ sign
         'video' =>
          '@'            . $photo
          . ';filename=' . $photo,
        'caption' => $caption,
        'duration' => $seconds
    );
	}
    

    $url = 'https://api.telegram.org/bot'.$token.'/sendVideo';

    //  open connection
    $ch = curl_init();
    //  set the url
    curl_setopt($ch, CURLOPT_URL, $url);
    //  number of POST vars
    curl_setopt($ch, CURLOPT_POST, count($fields));
    //  POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    //  To display result of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //  execute post
    $result = curl_exec($ch);
    //  close connection
    curl_close($ch);
	$result = json_decode ($result);
	if ($result->ok == 1) {
		$resulter = __( 'Sent', 'tchannel' );
	} else {
		$resulter = $result->description;
	}
	$options = get_option( 'tchannel_settings' );
    $logview = $options['tchannel_log'];
	if ($logview == 'yes'){
	channeller_log(__( 'Send Video', 'tchannel' ), $resulter ,$chat_id,$logger);
	}
}
function channeller_sendphoto($chat_id, $caption, $photo,$logger,$keyboard)
{
	$options = get_option( 'tchannel_settings' );
    $token = $options['tchannel_text_token'];
	//$current_path = str_replace('/wp-admin','',getcwd()); 
	//$photo = str_replace(get_bloginfo('url') , '', $photo);
	//$photo = $current_path.$photo;

	$chatter = get_site_option( $chat_id );
	$caption = truncate_channeller ($caption , 200);


 
 		//$caption = urlencode($caption);

		if ($keyboard == 'no') {
$params=array(   'chat_id'=>$chatter,
    'photo'=>$photo,
    'caption'=>$caption,
    'parse_mode'=>'HTML',
);		
} else {
			$replyMarkup = array('inline_keyboard' => $keyboard);
			$encodedMarkup = json_encode($replyMarkup);
$params=array(
    'chat_id'=>$chatter,
    'photo'=>$photo,
    'caption'=>$caption,
    'parse_mode'=>'HTML',
'reply_markup'=>$encodedMarkup,
);
		}
$url="https://api.telegram.org/bot".$token;
$ch = curl_init($url. '/sendPhoto');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$update = curl_exec($ch);
curl_close($ch);
	$result = json_decode ($update);
	if ($result->ok == 1) {
		$resulter = __( 'Sent', 'tchannel' );
	} else {
		$resulter = $result->description;
	}
	$options = get_option( 'tchannel_settings' );
    $logview = $options['tchannel_log'];
	if ($logview == 'yes'){
	channeller_log(__( 'Send Photo', 'tchannel' ), $resulter ,$chat_id,$logger);
	}
}
function channeller_sendmessagebot ($user_id,$message,$logger,$keyboard) {
	$options = get_option( 'tchannel_settings' );
    $token = $options['tchannel_text_token'];
	$message = truncate_channeller ($message , 4096);
	if ($token) {
		//$message = urlencode($message);
		if ($keyboard == 'no') {
$params=array(
    'chat_id'=>$user_id,
    'text'=>$message,
	'parse_mode'=>'HTML',
);		} else {
			$replyMarkup = array('inline_keyboard' => $keyboard);
			$encodedMarkup = json_encode($replyMarkup);
$params=array(
    'chat_id'=>$user_id,
    'text'=>$message,
'parse_mode'=>'HTML',
'reply_markup'=>$encodedMarkup,
);
		}
$url="https://api.telegram.org/bot".$token;
$ch = curl_init($url. '/sendMessage');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$update = curl_exec($ch);
curl_close($ch);
	$result = json_decode($update);
	if ($result->ok == 1) {
		$resulter = __( 'Sent', 'tchannel' );
		$thisresult = $result->result;
		$chat = $thisresult->chat;
		$chat_id = $chat->id;
		update_site_option( $user_id, $chat_id );
	} elseif ($result->ok == 0) {
		$resulter = __( '[Error] Have You Added Bot to Channel as admin?', 'tchannel' );
	}
	$options = get_option( 'tchannel_settings' );
    $logview = $options['tchannel_log'];
	if ($logview == 'yes'){
	channeller_log(__( 'Send Text', 'tchannel' ), $resulter ,$user_id,$logger);
	}
	 

	//end send message
	}
}
?>