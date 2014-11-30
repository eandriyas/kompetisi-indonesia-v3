<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//memanggil file base untuk melakukan penurunan
	require_once 'application/controllers/base/base.php';
	require_once 'application/libraries/oauth/library/OAuthStore.php';
	require_once 'application/libraries/oauth/library/OAuthRequester.php';
class telkom extends base{
	
	public function send_email($email){
	//ambil user id	
	$user_id = $this->session->userdata('id_user');
	//variable server	
	$consumer_k = "bestapp266";
	$consumer_s = "2AXO4";
	$oauth_h = "http://sandbox.appprime.net";
	$req_token_url = $oauth_h."/TemanDev/rest/RequestToken/";
	$req_access_token = $oauth_h."/TemanDev/rest/AccessToken/";
	//array input ke table oauth_consumer_registry
	$server = array(
	'consumer_key' => $consumer_k,
	'consumer_secret' => $consumer_s,
	'server_uri' => $oauth_h,
	'authorize_uri' => '',
	'request_token_uri' => $req_token_url,
	'access_token_uri' => $req_access_token
	);
	// //koneksi database
	// $hostname = 'localhost';
	// $user = 'root';
	// $password = 'list';
	// $dbname = 'KI_db';
	// $conn = new MySQLi($hostname, $user, $password, $dbname);
	//konek ke database
	// $store = OAuthStore::instance('MySQLi', array('conn' => $conn));
	$store = OAuthStore::instance('Session', $server);
	//simpan data server ke database
	 // $consumer_key = $store->updateServer($server, $user_id);


	
        //  STEP 1:  If we do not have an OAuth token yet, go get one
        $getAuthTokenParams = null;
  //       // get a request token
        echo 'fetch request token..';
        $tokenResultParams = OAuthRequester::requestRequestToken($consumer_k, 0, $getAuthTokenParams);
		echo 'request token = '.$tokenResultParams["token"];
        echo '';
        // //  STEP 2:  Get an access token
        try {
            OAuthRequester::requestAccessToken($consumer_k, $tokenResultParams["token"], 0, 'POST');
        }
        catch (OAuthException2 $e)
        {
            var_dump($e);
            return;
        }        
 
//         // make the docs request.
        $urlAPI = $oauth_h.'/TemanDev/rest/sendEmail/';
        $opt = array(CURLOPT_HTTPHEADER=>array('Content-Type: application/json'));
        $body = '{"sendEmail":{"to":"'.$email.'","subject":"andriyas","content":"Tes send email"}}';        
        $request = new OAuthRequester($urlAPI,'POST',$tokenResultParams,$body);
        echo 'execute api.. ';
        $result = $request->doRequest(0,$opt);
        if ($result['code'] == 200) {
                echo $result['body'];
        }
        else {
                echo 'Error: '.$result['code'];
        }

	}
	public function send_sms_ke_aku(){
		$no = "085271443804";
		$this->send_sms($no);
		
	}
}