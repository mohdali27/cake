<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
		public function upload_image($content=NULL,$name=NULL){
		if($content){
			    $img_name = $name."-".time();
                $data = $content;
                $data = str_replace(' ', '+', $data);
                $data = base64_decode($data);
                file_put_contents(WWW_ROOT."img/".$img_name.".jpeg", $data);
                chmod(WWW_ROOT."img/".$img_name.".jpeg", 0777);
                return $_SERVER['HTTP_HOST']."/mmezi/app/webroot/img/".$img_name.".jpeg";
		}else{
			return false;
		}
	}

	 //Sending Push Notification for Android
   function send_android_push_notification($registatoin_ids, $message) {
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }

     function send_notification_iphone($deviceToken, $message){ 
            // Put your private key's passphrase here:
            $passphrase = 'Admin123#';
            // Put your alert message here:     
            $path=WWW_ROOT.'files/newdevcer.pem';
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
            //stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            // Open a connection to the APNS server
            $fp = stream_socket_client(
                    'ssl://gateway.push.apple.com:2195', $err,
                    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$fp){
                return $err.'--'.$errstr;
            }else{
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
                return $result;
            else
                return $result;
            // Close the connection to the server
            fclose($fp);
        }
    }
    
}
