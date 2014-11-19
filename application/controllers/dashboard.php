<?php
//lokasi untuk dashboard  member
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//memanggil file base untuk melakukan penurunan
require_once 'application/controllers/base/base.php';
class dashboard extends base {

	public function __construct() {
		parent::__construct();
		//jika tidak login
		if(empty($this->session->userdata('id_user'))) {
			redirect(site_url());
		}
	}

	public function index() {
		
		$id = $this->session->userdata('id_user'); //mengambil data id user
		$data['title'] = 'Kompetisi diikuti | ';
		//pagination set up
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;
		$config['num_link'] = 4;	
		$config['page_query_string'] = TRUE;
		$config['base_url'] = site_url().'/dashboard?ki='.$this->input->get('ki',TRUE);
		$config['total_rows'] = $this->m_kompetisi->count_diikuti_kompetisi($id); 
		$this->pagination->initialize($config); 

		if(isset($_GET['per_page'])) {
			if($_GET['per_page'] == '') { 
				$uri = 0;
			} else {
				$uri = $_GET['per_page'];
			}
		} else {
			$uri = 0;
		}

		if($config['total_rows'] < 20) {
			$data['page'] = 1;
		} else {
			$data['page'] = $this->pagination->create_links();
		}
		//end of pagination set up
		//header view
		$data['ikut'] = $this->m_kompetisi->count_diikuti_kompetisi($id);
		$data['tandai'] = $this->m_kompetisi->count_tandai_kompetisi($id);
		$data['kompetisiku'] = $this->m_kompetisi->count_kompetisiku($id);
		$data['total'] = $this->m_kompetisi->count_diikuti_kompetisi($id);
		$data['view'] = $this->m_kompetisi->show_kompetisi_gabung($id, $config['per_page'],$uri);
		$this->defaultdisplay('dashboard/home', $data);
		$this->footerdisplay();
	}

	public function ditandai(){
		$id = $this->session->userdata('id_user'); //mengambil data id user
		$data['title'] = 'Kompetisi diikuti | ';	
		//pagination set up
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;
		$config['num_link'] = 4;	
		$config['page_query_string'] = TRUE;
		$config['base_url'] = site_url().'/dashboard/ditandai?ki='.$this->input->get('ki',TRUE);
		$config['total_rows'] = $this->m_kompetisi->count_tandai_kompetisi($id); 
		
		$this->pagination->initialize($config); 

		if(isset($_GET['per_page'])) {
			if($_GET['per_page'] == '') { 
				$uri = 0;
			} else {
				$uri = $_GET['per_page'];
			}
		} else {
			$uri = 0;
		}

		if($config['total_rows'] < 20) {
			$data['page'] = 1;
		} else {
			$data['page'] = $this->pagination->create_links();
		}
		//end of pagination set up	
		//header view
		$data['ikut'] = $this->m_kompetisi->count_diikuti_kompetisi($id);
		$data['tandai'] = $this->m_kompetisi->count_tandai_kompetisi($id);
		$data['kompetisiku'] = $this->m_kompetisi->count_kompetisiku($id);
		$data['total'] = $this->m_kompetisi->count_tandai_kompetisi($id); 
		$data['view'] = $this->m_kompetisi->show_kompetisi_diikuti($id,$config['per_page'],$uri);
		$this->defaultdisplay('dashboard/ditandai', $data);
		$this->footerdisplay();
	}

	public function saya(){
		$id = $this->session->userdata('id_user'); //mengambil data id user
		$data['title'] = 'Kompetisi Upload Oleh Saya | ';	
		//pagination set up
		$this->load->library('pagination');
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;
		$config['num_link'] = 4;	
		$config['page_query_string'] = TRUE;
		$config['base_url'] = site_url().'/dashboard/saya?ki='.$this->input->get('ki',TRUE);
		$config['total_rows'] = $this->m_kompetisi->count_kompetisiku($id); 
		
		$this->pagination->initialize($config); 

		if(isset($_GET['per_page'])) {
			if($_GET['per_page'] == '') { 
				$uri = 0;
			} else {
				$uri = $_GET['per_page'];
			}
		} else {
			$uri = 0;
		}

		if($config['total_rows'] < 20) {
			$data['page'] = 1;
		} else {
			$data['page'] = $this->pagination->create_links();
		}
		//end of pagination set up
		$data['ikut'] = $this->m_kompetisi->count_diikuti_kompetisi($id);
		$data['tandai'] = $this->m_kompetisi->count_tandai_kompetisi($id);
		$data['kompetisiku'] = $this->m_kompetisi->count_kompetisiku($id);	
		$data['total'] = $this->m_kompetisi->count_kompetisiku($id); 
		$data['view'] = $this->m_kompetisi->get_competition_by_id_user($id,$config['per_page'],$uri);
		$this->defaultdisplay('dashboard/saya', $data);
		$this->footerdisplay();
	}

	public function pasang(){
		
		//jika by tidak sama dengan username, maka dicancel
		if( isset($_GET['by']) && $_GET['by'] == $this->session->userdata('username')) {			
			$data['kat'] = $this->m_kompetisi->show_kat();
			$data['main_kat'] = $this->m_kompetisi->show_main_kat_by_id();
			$data['title'] = 'Pasang | ';
			$id = $this->session->userdata('id_user');
			$data['ikut'] = $this->m_kompetisi->count_diikuti_kompetisi($id);
			$data['tandai'] = $this->m_kompetisi->count_tandai_kompetisi($id);
			$data['kompetisiku'] = $this->m_kompetisi->count_kompetisiku($id);
			$data['total'] = $this->m_kompetisi->count_diikuti_kompetisi($id);
			$this->defaultdisplay('dashboard/pasang', $data);
			$this->footerdisplay();
		//khusus untuk admin
		} else {
			redirect(site_url('super/super/dashboard')); //kembali ke dashboard
		}
	}

	public function edit(){
		//id kompetisi
		$data['title'] = "Edit Kompetisi | ";
		$dec = base64_decode(base64_decode($_GET['id']));
		$id = str_replace('', '=', $dec);
		$data['view'] = $this->m_kompetisi->get_competition_by_id_kompetisi($id);
		$id_main_kat = $data['view']['id_main_kat'];
		$data['main_kat'] = $this->m_kompetisi->show_main_kat_by_id();
		$data['sub_kat'] = $this->m_kompetisi->show_sub_kat_by_id($id_main_kat);
		$data['view'] = $this->m_kompetisi->get_competition_by_id_kompetisi($id);
		$this->defaultdisplay('dashboard/edit_kompetisi', $data);
		$this->footerdisplay();	
	}

	public function profile(){ //halaman untuk edit data user
		$data['title'] = "Edit Profile | ";
		$id = $this->session->userdata('id_user');
		$data['tandai'] = $this->m_kompetisi->count_tandai_kompetisi($id);
		$data['kompetisiku'] = $this->m_kompetisi->count_kompetisiku($id);
		$id = $this->session->userdata('id_user');
		$data['view'] = $this->m_user->show_user($id);
		$this->defaultdisplay('dashboard/profile', $data);
		$this->footerdisplay();

	}

	//ads management for member //static pages
	public function ads(){
		$data['title'] = "Ads | ";
		$data['type'] = $this->m_ads->showAllAdsType();//show all ads type
		$data['bank'] = $this->m_ads->showAllBank();//show all bank
		$this->defaultdisplay('dashboard/ads', $data);
		$this->footerdisplay();
	}
	//cek ketersediaan
	public function cek_ketersediaan(){
		$date = $_GET['tgl'];
		$type = $_GET['tipe'];
		$data['title'] = "processing... ";
		if($this->m_ads->cekKetersediaan($date,$type)){ //not availble
			echo '<div class="alert alert-danger">permintaan ads dengan tipe dan tanggal pilihan anda tidak tersedia</div>';
		} else { //available
			echo '<div class="alert alert-success">permintaan ads dengan tipe dan tanggal pilihan anda tersedia, silahkan klik proses</div>';
		}
	}
	//proses permintaan ads
	public function proc_ads(){
		$this->load->library('upload');//library upload
		$by = $this->input->session('id_user');//id user
		$judul = $_POST['inputJudul'];
		$pesan = $_POST['inputPesan'];
		$tipe = $_POST['inputTipe'];
		$tanggal = $_POST['inputTanggal'];
		$durasi = $_POST['inputDurasi'];
		$banner = $_FILES['inputBanner'];
		$bank = $_POST['inputBank'];
		$now = date('Y-m-d H:i:s');//date now
		//$enddate enddate adalah startdate + durasi
		//memasukan data ads
		//aturan upload
		//req_by | start_mysql | end_date | banner | ads_type | status (v)
		$bannername = $banner['name'];
		$bannername = str_replace(' ','_', $bannername);//new name for database and calling on images/ads/folder
		$config['upload_path'] = './images/ads/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		if($tipe == 1){ //jelajah view | banner = 600 x 150
			$config['max_size']  = '1000';//maks 1MB
			$config['max_width']  = '600';
			$config['max_height']  = '150';			
		} else if($tipe == 2){ //bottom fixed view | banner = 600 x 80
			$config['max_size']  = '1000';//maks 1MB
			$config['max_width']  = '600';
			$config['max_height']  = '80';
		} else if($tipe == 3){ //detail view 1 | banner = 500 x 200
			$config['max_size']  = '1000';//maks 1MB
			$config['max_width']  = '500';
			$config['max_height']  = '200';
		} else if($tipe == 4){ //detail view 2 | banner = 250 x250
			$config['max_size']  = '1000';//maks 1MB
			$config['max_width']  = '250';
			$config['max_height']  = '250';
		} else {
			echo 'no upload file';
		}
		//upload proces
		$this->upload->initialize($config);//initialize config
		if (!$this->upload->do_upload('inputBanner')){ //cant upload
			$data['title'] = 'Error Ads Request | ';//add request error for form
			$data['insertform'] = array('judul','pesan','tipe','tanggal','durasi','bank');
			$data['error'] = $this->upload->display_errors();//error
			$this->defaultdisplay('dashboard/ads', $data);
			$this->footerdisplay();
		}
		else{ //success upload
			//memasukan data ke database
			$adssparams = array(
				'req_by'=>$by,
				'req_date'=>$now,
				'end_date'=>'12',//tambahan
				'banner'=>$bannername,
				'ads_type'=>$tipe,
				'status'=>'unread'
				);
			if($this->db->insert('ads',$addsparams)) { //berhasil memasukan data ads
				$queryads = $this->m_ads->showLastAds();
				$idads = $queryads['id_ads'];//last ads
				$datapayment = array(
					'id_ads'=>$idads,
					'id_rek_bank'=>$bank,//bank kompetisiindonesia
					'no_ref'=>'',
					'expired_date'=>'',
					'value'=>'',
					);
			} else {
				//gagal insert data ads
			}
		}
		//memasukan data payment
	}
}
