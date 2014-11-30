<?php
if(!defined('BASEPATH') ) exit ('No direct sript allowed');
require_once 'application/libraries/oauth/library/OAuthStore.php';
require_once 'application/libraries/oauth/library/OAuthRequester.php';
//base class
class base extends CI_Controller {
	//constructor
	public function __construct(){
		parent::__construct();
		//auto load model
		$this->load->model('m_request');
		$this->load->model('m_kompetisi');
		$this->load->model('m_user');
		$this->load->model('m_moderator');
		$this->load->model('m_post');
		$this->load->model('m_super');
		$this->load->model('m_ads');		
	}
	public function defaultdisplay( $view_anak = '',$data = '' ) {
		$data['template_anak'] = $view_anak;
		$this->load->view('base/defaultbase', $data);
	}
	public function superdisplay( $view_anak = '',$data = ''  ){
		$data['template_anak'] = $view_anak;
		$this->load->view('base/superbase', $data);
		$this->load->view('super/footer');
	}
	public function footerdisplay(){		
		$this->load->view('base/defaultbase_footer');
	}
	//id encoder
	public function ki_id_enc($x){
		$enc = base64_encode(base64_encode($x));
		return str_replace('=', '', $enc);
	}
	//id decoder
	public function ki_id_dec($x){
		$dec = base64_decode(base64_decode($x));
		return str_replace('', '=', $dec);
	}
	//money converter
	public function ki_money_convert($x){
		$this->load->library('cart');
		if($x >= 1000000000) {
			$total = number_format($x / 1000000000,1);
			$x = $total;
			$y = 'Milyar';
		}else if($x >= 1000000 && $x <=1000000000) {
			$total = number_format($x / 1000000,1 );
			$x = $total;
			$y = 'Juta';
		} else if ($x >=1000 && $x <=1000000) {
			$total = number_format($x / 1000, 1);
			$x = $total;
			$y = 'Ribu';
		} else {
			$x = $x;
		}
		return 'Rp '.$this->cart->format_number($x).' '.$y;
	}
	
	public function send_email($email,$namakompetisi,$val_hadiah,$linkkompetisi){
	//ambil user id	
	// $user_id = $this->session->userdata('id_user');
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
        $arr = array("sendEmail"=>array(
        	'to'=>$email, 
        	'subject'=>'Pengumuman Pemenang dari KompetisiIndonesia.com', 
        	'content'=>"selamat anda memenangkan kompetisi ".$namakompetisi." dengan hadiah ".$val_hadiah." info selengkapnya : ".$linkkompetisi));

        // $body = '{"sendEmail":{"to":"'.$email.'","subject":"Pengumuman Pemenang","content":"Selamat anda memenangkan kompetisi : "'.$namakompetisi.'"dengan hadiah "'.$val_hadiah.'"<a href="'.$linkkompetisi.'">info selengkapnya</a>"}}'; 
        $j_arr = json_encode($arr);       
        $request = new OAuthRequester($urlAPI,'POST',$tokenResultParams,$j_arr);
        echo 'execute api.. ';
        $result = $request->doRequest(0,$opt);
        if ($result['code'] == 200) {
                echo $result['body'];
        }
        else {
                echo 'Error: '.$result['code'];
        }

	}
	//fungsi untuk kirim sms
	public function send_sms($nohp){
	//ambil user id	
	// $user_id = $this->session->userdata('id_user');
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
        $urlAPI = $oauth_h.'/TemanDev/rest/sendSmsBulk/';
        $opt = array(CURLOPT_HTTPHEADER=>array('Content-Type: application/json'));
        $arr = array("smsBulk"=>array(
        	'msisdn'=>$nohp, 
        	'message'=>'Pengumuman Pemenang dari KompetisiIndonesia.com'));

        // $body = {"":{"msisdn":"081320599744","message":"tes send sms"}};
        $j_arr = json_encode($arr);       
        $request = new OAuthRequester($urlAPI,'POST',$tokenResultParams,$j_arr);
        echo 'execute api.. ';
        $result = $request->doRequest(0,$opt);
        if ($result['code'] == 200) {
                echo $result['body'];
        }
        else {
                echo 'Error: '.$result['code'];
        }

	}

}