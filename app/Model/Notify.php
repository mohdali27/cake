<?php  
      
    class Notify extends AppModel  
    {  
        var $name = 'Notify'; 

         // **************** send notification to android Start 

       public function send_notification_android($registatoin_ids, $message) {
       
          /*$googlekey="AIzaSyDFeftRSEJ3106SRY23XMcmYN6xgrciOd4"; 
            define("GOOGLE_API_KEY2", "$googlekey");
            // Set POST variables
            $url = 'https://android.googleapis.com/gcm/send';
     
            $fields = array(
                'registration_ids' => $registatoin_ids,
                'data' => $message,
            );
     
            $headers = array(
                'Authorization: key=' . GOOGLE_API_KEY2,
                'Content-Type: application/json'
            );
            // Open connection
            $ch = curl_init();
     
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
     
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
     
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
     
            // Close connection
            curl_close($ch);

            return $result;*/
            
            

					// API access key from Google API's Console
					define( 'API_ACCESS_KEY', 'AIzaSyDFeftRSEJ3106SRY23XMcmYN6xgrciOd4' );


					// $registrationIds = array( $_GET['id'] );

					// prep the bundle
					$msg = array
					(
						'message' 	=> 'here is a message. message',
						'title'		=> 'This is a title. title',
						'subtitle'	=> 'This is a subtitle. subtitle',
						'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
						'vibrate'	=> 1,
						'sound'		=> 1,
						'largeIcon'	=> 'large_icon',
						'smallIcon'	=> 'small_icon'
					);

					$fields = array
					(
						'registration_ids' 	=> $registatoin_ids,
						'data'			=> $msg
					);
					 
					$headers = array
					(
						'Authorization: key=' . API_ACCESS_KEY,
						'Content-Type: application/json'
					);
					 
					$ch = curl_init();
					curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
					curl_setopt( $ch,CURLOPT_POST, true );
					curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
					curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
					curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
					$result = curl_exec($ch );
					curl_close( $ch );

					echo $result;
        } 
      
      
         function send_notification_iphone($deviceToken, $message){ 
            
            // Put your private key's passphrase here:
            $passphrase = 'Admin123#';
            // Put your alert message here:     
            $path=Mage::getBaseDir('lib').DS.'Iphonenotification'.DS.'FinalProduction.pem';
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $path);//'Mercode.pem'
            //stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            // Open a connection to the APNS server
            $fp = stream_socket_client(
                    'ssl://gateway.push.apple.com:2195', $err,
                    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp){
            Mage::log('Failed to connec--'.$err.'--'. $errstr,null,'iphone.log');
                //exit("Failed to connect: $err $errstr" . PHP_EOL);
            }else{

            Mage::log('pass-'.$err.'--'. $errstr,null,'iphone.log');}

            // Create the payload body
            $body['aps'] = array(
                    'alert' =>utf8_encode($message),
                    'sound' => 'default',
                    'badge' => '1'
            );

            // Encode the payload as JSON
            $payload = json_encode($body);

            // Build the binary notification
            $msg = @chr(0) . @pack('n', 32) . @pack('H*', $deviceToken) . @pack('n', strlen($payload)) . $payload;

            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            //print_r($result);
            if (!$result)
                Mage::log('send success not-'.$result,null,'iphone.log');
            else
                Mage::log('send success -'.$result,null,'iphone.log');

            // Close the connection to the server
            fclose($fp);
        }
        // **************** send notification to Iphone End 
    }

?>  