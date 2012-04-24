<?php 

/*
 * 	class for  Apple Push Notification Service 
 *  参考：http://developer.apple.com/library/ios/#documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Introduction/Introduction.html#//apple_ref/doc/uid/TP40008194-CH1-SW1
 *  
*/
class APN {


 	private $apnsHost = 'gateway.sandbox.push.apple.com'; 

	private $apnsPort = 2195;
	private $apnsCert = 'test.pem';//文件的生成方式，请参考http://developer.apple.com/library/ios/#documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/ProvisioningDevelopment/ProvisioningDevelopment.html#//apple_ref/doc/uid/TP40008194-CH104-SW1

	private $errno = 0;
	private $errmsg = 'ok';



	public function __construct() {
	}


	public function error_handler($errno ,$errmsg ,$file,$line){
		$this->errno = $errno;
		$this->errmsg = $errmsg;
		//echo 'ERROR:['.$errno.']'.$errmsg." file : $file  line: $line \n";
		die();
	}
	

	public function push($token , $message= '', $badge =  1 ){

			set_error_handler(array(&$this,'error_handler'));

			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $this->apnsCert);
			$apns = stream_socket_client('ssl://' . $this->apnsHost . ':' . $this->apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
			if($apns){
				$payload = array();
				$payload['aps'] = array('alert' => $message, 'badge' => $badge, 'sound' => 'default');
				$payload = json_encode($payload);
				$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $token)) . chr(0) . chr(strlen($payload)) . $payload;
				fwrite($apns, $apnsMessage);

			}else{       
		        //echo "Connection Failed";
		       
			}
			fclose($apns);
			
			return  (0 == $this->errno );
	}

	public function error_number() {
		return $this->errno;
	}


	public function error_message(){
		return $this->errmsg;
	}



}