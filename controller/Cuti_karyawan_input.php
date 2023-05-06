<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Cuti_karyawan_input extends CI_Controller 
{

public function index()
	{
		$session = $this->session->get_userdata();
		$id_dep = $session['id_dep'];
		$id_user = $session['id_user'];

		$data['title'] = 'E- Leave';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$current_date = date("Y");
		$data['jumlah_user'] = $this->db->query(" SELECT
													CASE WHEN jumlah is null THEN 0 
													ELSE SUM(jumlah) END  AS cuti_yang_diambil
													FROM cuti 
													WHERE id_user = '$id_user'
													AND tahun_cuti = '$current_date'
													AND status = 'Approve HRD'
													AND tipe ='cuti'")->row_array();

		$current_date = date("Y");
		$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
											CASE WHEN jumlah is null THEN 0 
											ELSE SUM(jumlah) END  AS sisa_cuti
											FROM cuti 
											WHERE id_user = '$id_user'
											AND tahun_cuti = '$current_date'
											AND status = 'Approve HRD'
											AND tipe ='cuti'")->row_array();
												
		
		$data['rest'] = $this->db->query(" SELECT * FROM overtime
										 WHERE take_rest = '0'
										 AND id_user = '$id_user'
										 AND YEAR(tanggal_overtime) = '$current_date' ")->result_array();

		$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
		$data['inventaris'] = $this->db->get('inventaris')->result_array();
		$data['lokasi'] = $this->db->get('m_lokasi')->result_array();
		$data['daily_routine'] = $this->db->get('dailyroutine')->result_array();
		// $data['userr'] = $this->db->get('user')->result_array();
		$data['userr'] = $this->db->get_where('user', ['id_dep' => $id_dep])->result_array();
		$data['device'] = $this->db->get('device')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();
		$data['jabatan'] = $this->db->get('jabatan')->result_array();
		$data['kontraktor'] = $this->db->get('kontraktor')->result_array();
		$data['inv'] = $this->db->get('inv')->result_array();
        $data['dpt'] = $this->db->query("SELECT * FROM departement where jenis_departement not in ('management','empty')")->result_array();
        $data['tahuncuti'] = $this->db->query("SELECT * FROM tahun_cuti where tahun AND status in ('1')")->result_array();
		$data['admin'] = $this->db->query("SELECT * FROM user where rule ='IT'")->result_array();

		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('id_user','Id_User','required');
		$this->form_validation->set_rules('id_dep','Id_Dep','required');
		$this->form_validation->set_rules('id_jabatan','Id_Jabatan','required');
		$this->form_validation->set_rules('tahun_cuti','Tahun_Cuti','required');
		$this->form_validation->set_rules('tipe','Tipe','required');
		$this->form_validation->set_rules('cuti_diambil','Cuti_Diambil');
		$this->form_validation->set_rules('start_date','Start_date');
		$this->form_validation->set_rules('end_date','End_Date');
		$this->form_validation->set_rules('sisa_cuti','Sisa_Cuti','required');
		$this->form_validation->set_rules('cuti_diminta','Cuti_Diminta');
		$this->form_validation->set_rules('jumlah','Jumlah','required');
		$this->form_validation->set_rules('delegasi','Delegasi');
		$this->form_validation->set_rules('no_telp','No_Telp');
		$this->form_validation->set_rules('reason','Reason');

		if($this->form_validation->run() ==false ) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('cuti_karyawan_input/index', $data);
			$this->load->view('templates/footer');		
		} else {

			if ($user['id_jabatan'] == 30 OR $user['id_jabatan'] == 47) {
			$data = [
				'name' => $this->input->post('name'),
				'id_user' => $this->input->post('id_user'),
				'id_dep' => $this->input->post('id_dep'),
				'id_jabatan' => $this->input->post('id_jabatan'),
				'tahun_cuti' => $this->input->post('tahun_cuti'),
				'tipe' => $this->input->post('tipe'),
				'cuti_diambil' => $this->input->post('cuti_diambil'),
				'start_date' =>	 date('Y-m-d', strtotime($this->input->post('start_date'))),
				'end_date' =>	 date('Y-m-d', strtotime($this->input->post('end_date'))),
				'sisa_cuti' => $this->input->post('sisa_cuti'),
				'jumlah' => $this->input->post('jumlah'),
				'no_telp' => $this->input->post('no_telp'),
				'reason' => $this->input->post('reason'),
				'status' => 'Approve HRD'
		   ];
			}else {
				$data = [
					'name' => $this->input->post('name'),
					'id_user' => $this->input->post('id_user'),
					'id_dep' => $this->input->post('id_dep'),
					'id_jabatan' => $this->input->post('id_jabatan'),
					'tahun_cuti' => $this->input->post('tahun_cuti'),
					'tipe' => $this->input->post('tipe'),
					'cuti_diambil' => $this->input->post('cuti_diambil'),
					'start_date' =>	 date('Y-m-d', strtotime($this->input->post('start_date'))),
					'end_date' =>	 date('Y-m-d', strtotime($this->input->post('end_date'))),
					'sisa_cuti' => $this->input->post('sisa_cuti'),
					'jumlah' => $this->input->post('jumlah'),
					'no_telp' => $this->input->post('no_telp'),
					'reason' => $this->input->post('reason'),
					'status' => 'Request'
			   ];
			}
			$id_cuti = $this->input->post('id_cuti');
			$name = $this->input->post('name');
			$id_user = $this->input->post('id_user');
			$id_dep = $this->input->post('id_dep');
			$id_jabatan = $this->input->post('id_jabatan');
		    $tahun_cuti = $this->input->post('tahun_cuti');
		    $tipe = $this->input->post('tipe');
		    $tanggal_pembuatan = $this->input->post('tanggal_pembuatan');
		    $status = $this->input->post('status');
		    $cuti_diambil = $this->input->post('cuti_diambil');
		    $start_date = $this->input->post('start_date');
		    $end_date = $this->input->post('end_date');
		    $sisa_cuti = $this->input->post('sisa_cuti');
		    $jumlah = $this->input->post('jumlah');
		    $no_telp = $this->input->post('no_telp');
		    $reason = $this->input->post('reason');
		   	$delegasi = $_POST['delegasi'];
		   if (is_array($_POST['delegasi']))
       			 {
        		$delegasi = implode(", ", $_POST['delegasi']);
				$this->db->set('delegasi', $delegasi);
        	} else {
			$rest = $this->input->post('rest');
			$take_rest = '1';
			$this->db->set('take_rest', $take_rest);
			$this->db->where('id_overtime', $rest);
			$this->db->update('overtime');
			}
			// echo ($delegasi);

			
			// die($rest);
	
			 $this->db->insert('cuti', $data, $delegasi);

			
			
			 $id_cuti = $this->db->insert_id();
			 //echo $this->db->last_query();die();
			 $name = $this->db->get_where('cuti', ['id_cuti' => $id_cuti])->row()->name;
			 $no_badge = $this->db->get_where('m_user', ['id_user' => $id_user])->row()->id_user;
			 $jabatan = $this->db->get_where('jabatan', ['id_jabatan' => $id_jabatan])->row()->jabatan;
			 $department = $this->db->get_where('departement', ['id_dep' => $id_dep])->row()->jenis_departement;
			 $tahun_cuti = $this->db->get_where('cuti', ['id_cuti' => $id_cuti])->row()->tahun_cuti;
			 $tahun = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->tahun;
			 $tipe = $this->db->get_where('cuti', ['id_cuti' => $id_cuti])->row()->tipe;
			 $tanggal_pembuatan = $this->db->get_where('cuti', ['id_cuti' => $id_cuti])->row()->tanggal_pembuatan;
			 $status = $this->db->get_where('cuti', ['id_cuti' => $id_cuti])->row()->status;
			 $cuti_diambil = $this->db->get_where('cuti', ['id_cuti' => $id_cuti])->row()->cuti_diambil;
			

			$email = $this->db->query("SELECT email FROM user WHERE id_jabatan in('3','46') AND id_dep = '$id_dep' ")->result_array();
			// print_r($email);
			// $mail =array();
			foreach ($email as $to) {
				$mail[] = implode(", ",$to);
			}
			$mali = implode(", ", $mail);
			$to = $mali;	
			// echo ($to);

			$this->notify_mail( $to, 
								$id_cuti, 
								$name, 
								$no_badge, 
								$jabatan, 
								$department, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$delegasi 
								);

			 $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">You Success Create E-Leave!</div>');
			redirect('cuti_karyawan');
		}
}

public function notify_mail($to, 
								$id_cuti,
								$name, 
								$no_badge, 
								$jabatan, 
								$department, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$delegasi){

	$session = $this->session->get_userdata();
		$id_user = $session['id_user'];
	$data['user'] = $this->db->get_where('m_user', ['username' => 
	$this->session->userdata('username')])->row_array();
									
							
	$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
	// $this->load->library('phpmailer_library');
	// $mail = $this->phpmailer_library->load();
	$mail = new PHPMailer(true);
	// $mail = new Exception();
	// $mail->SMTPDebug = 2;                               // Enable verbose debug output
	$mail->isSMTP();                                     
	$mail->Host = 'smtp.gmail.com';  
	$mail->SMTPAuth = true;                              
	$mail->Username = 'teamesystem@gmail.com';                 
	$mail->Password = 'itbfogutktnrfwfu';                           
	$mail->SMTPSecure = 'ssl';                            
	$mail->Port = 465;                                    
	$mail->isHTML(true); 
	

	$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
			$arr_delegasi = explode(',',$delegasi);
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where_in('id_user',$arr_delegasi);
			$list_delegasi = $this->db->get()->result();

	foreach($list_delegasi as $dmce) :
	$mailContent = 
		"<center>*******************************************************************************************************</br>
		<center><b>.:: Form E-Leave | E-System ALP Petro  ::.</b></center>
		*******************************************************************************************************
		<table>

		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$name."</td>
		  </tr>

		  <tr>
			<td>No. Badge</td>
			<td>:</td>
			<td>".$no_badge."</td>
		  </tr>

		  <tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>".$jabatan."</td>
		  </tr>

		  <tr>
			<td>Department</td>
			<td>:</td>
			<td>".$department."</td>
		  </tr>

		  <tr>
			<td>Tahun Cuti</td>
			<td>:</td>
			<td>".$tahun."</td>
		  </tr>

		  <tr>
			<td>Tipe</td>
			<td>:</td>
			<td>".$tipe."</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Telah Diambil</td>
			<td>:</td>
			<td>".$cuti_diambil." Hari</td>
		  </tr>

		  <tr>
			<td>Sisa Cuti</td>
			<td>:</td>
			<td>".$sisa_cuti." Hari</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Diminta</td>
			<td>:</td>
			<td>".$jumlah." Hari</td>
		  </tr>

		  <tr>
			<td>Dari Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($start_date))."</td>
		  </tr>

		  <tr>
			<td>Sampai Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($end_date))."</td>
		  </tr>

		  <tr>
			<td>No/Alamat Yang Bisa Dihubungi</td>
			<td>:</td>
			<td>".$no_telp."</td>
		  </tr>
		  
		  <tr>
			<td>Delegasi</td>
			<td>:</td>
			<td>".$dmce->name."</td>
		  </tr>
		  <tr>
		 
			<td>Status</td>
			<td>:</td>
			<td>".$status."</td>
		  </tr>
		  <tr>
			<td>Tanggal Pembuatan</td>
			<td>:</td>
			<td>".$tanggal_pembuatan."</td>
		  </tr>

		</table>
		*******************************************************************************************************
		<p>Terima kasih telah membuat request cuti pada website E-System ALP Petro.</br>
		Mohon untuk segera cek Request cuti diatas melalui website E-System ALP Petro. Klik link berikut. <a href='http://192.168.10.50/esistem/'> E-System ALP Petro</a></p>
		*******************************************************************************************************</center>";	
	endforeach;
	
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//end custom code

		$mail->setFrom('teamesystem@gmail.com','E-System E-Leave');
		if($user['id_jabatan'] == 3 OR $user['id_jabatan'] == 46) {
			$mail->addAddress('dekfiral@gmail.com');
			$mail->addReplyTo('dekfiral@gmail.com');
			// $mail->addAddress('fery.alp@alppetro.id');
			// $mail->addReplyTo('fery.alp@alppetro.id');
		} else {
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addAddress($address);
			// }

			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
			// 	$mail->addReplyTo($address);
			// }
		}
		$mail->Subject = ('E-Leave | ALP E-System');
		$mail->Body    = ($mailContent);
	
		if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		// echo $mail->print_debugger();
	 } else {
		// echo "Message has been sent";
		// echo $mail->print_debugger();
	 }
}

public function detail_jumlah($id_cuti=0)
    {
        $data['title'] = 'Detail Data Report';
        $data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$start_date = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->start_date;
		$end_date = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->end_date;

		$this->db->where('start_date',$start_date);
		$this->db->where('end_date',$end_date);
		$this->db->select('*');
		$this->db->from('ecuti');
		$result =  $this->db->get()->result_array();
		// print_r($result);

		// echo $this->db->last_query();
		$content  = "<a style='color:red; font-size: 12px;'>From Date :</a>"."&nbsp;".
					"<a>".date('d M Y',strtotime($start_date))."</a>"."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
					"<a style='color:red; font-size: 12px;'>Up To :</a>"."&nbsp;".
					"<a>".date('d M Y',strtotime($end_date))."</a>";			
		echo $content;
}

public function detail_delegasi($id_cuti=0)
    {
        $data['title'] = 'Detail Data Report';
        $data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
		$arr_delegasi = explode(',',$delegasi);
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where_in('id_user',$arr_delegasi);
		$list_delegasi = $this->db->get()->result();
		//print_r($list_delegasi);

		//echo $this->db->last_query();
		$content  = "<table class='table table-bordered'>
					<tr style='color: black; font-size: 12px;' class='text-center'>
					<th>Nama</th>
					<th>Department</th>
					<th>Email</th>
					</tr>"; 
		foreach($list_delegasi as $dmce){
			$content .= "<tr style='color: black; font-size: 12px;' class='text-center'>
					<td>".$dmce->name."</td>".
					"<td>".$dmce->jenis_departement."</td>".
					"<td>".$dmce->email."</td>
					</tr>";
		}
		$content .= "</table>";
		echo $content;
}

public function detail_cuti_karyawan($id_cuti=null)
    {
        $data['title'] = 'Detail E-Leave';
        $data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
		$arr_delegasi = explode(',',$delegasi);
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where_in('id_user',$arr_delegasi);
		$list_delegasi = $this->db->get()->result();
		
		$data['delegasi'] = $list_delegasi;
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$data['userr'] = $this->db->get('user')->result_array();
		$data['cuti'] = $this->db->get('ecuti')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();

		

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan_input/detail_cuti_karyawan', $data);
        $this->load->view('templates/footer');
}

public function approvehod($id_cuti = null)
	{
		$session = $this->session->get_userdata();
		$id_dep = $session['id_dep'];
		$id_user = $session['id_user'];
		$username = $session['username'];

		// die($username);

		$data['title'] = 'Progress';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$current_date = date("Y-m-d");
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();

		$this->form_validation->set_rules('status','Status','required');

		  if(isset($_POST['save'])){
			if($this->form_validation->run() ==true ) {

			$status = $this->input->post('status');
	
			$this->db->set('action_head_by', $id_user);
			$this->db->set('action_head_date', $current_date);
			$this->db->set('status', $status);
			$this->db->where('id_cuti', $id_cuti);
			$this->db->update('cuti');
			// echo $this->db->last_query(); die();

			$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();

			foreach ($ecuti as $cuti) :
				$this->sendmail_hod($cuti['id_cuti'],
								$cuti['name'],
								$cuti['id_userrrr'],
								$cuti['jabatan'],
								$cuti['jenis_departement'],
								$cuti['tahun_cuti'],
								$cuti['tahun'],
								$cuti['tipe'],
								$cuti['tanggal_pembuatan'],
								$cuti['status'],
								$cuti['cuti_diambil'],
								$cuti['start_date'],
								$cuti['end_date'],
								$cuti['sisa_cuti'],
								$cuti['jumlah'],
								$cuti['no_telp'],
								$cuti['email'],
								$cuti['delegasi']);
			endforeach;
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Approved Success !</div>');
			redirect('cuti_karyawan');
		}
	} else {
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan_input/detail_cuti_karyawan', $data);
        $this->load->view('templates/footer'); 
	}
		
}

public function sendmail_hod($id_cuti,
								$name, 
								$id_userrrr, 
								$jabatan, 
								$jenis_departement, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$email, 
								$delegasi){
	$session = $this->session->get_userdata();
	$id_user = $session['id_user'];
	$data['user'] = $this->db->get_where('m_user', ['username' => 
	$this->session->userdata('username')])->row_array();
																
														
	$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
	$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();

	// print_r($ecuti); die();
	// $this->load->library('phpmailer_library');
	// $mail = $this->phpmailer_library->load();
	$mail = new PHPMailer(true);
	// $mail = new Exception();
	// $mail->SMTPDebug = 2;                               // Enable verbose debug output
	$mail->isSMTP();                                     
	$mail->Host = 'smtp.gmail.com';  
	$mail->SMTPAuth = true;                              
	$mail->Username = 'teamesystem@gmail.com';                 
	$mail->Password = 'itbfogutktnrfwfu';                           
	$mail->SMTPSecure = 'ssl';                            
	$mail->Port = 465;                                    
	$mail->isHTML(true); 
	

	$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
			$arr_delegasi = explode(',',$delegasi);
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where_in('id_user',$arr_delegasi);
			$list_delegasi = $this->db->get()->result();

	foreach($list_delegasi as $dmce) :
	$mailContent = 
		"<center>*******************************************************************************************************</br>
		<center><b>.:: Form E-Leave | E-System ALP Petro  ::.</b></center>
		*******************************************************************************************************
		<table>

		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$name."</td>
		  </tr>

		  <tr>
			<td>No. Badge</td>
			<td>:</td>
			<td>".$id_userrrr."</td>
		  </tr>

		  <tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>".$jabatan."</td>
		  </tr>

		  <tr>
		  <td>Department</td>
		  <td>:</td>
		  <td>".$jenis_departement."</td>
		</tr>

		  <tr>
			<td>Tahun Cuti</td>
			<td>:</td>
			<td>".$tahun."</td>
		  </tr>

		  <tr>
			<td>Tipe</td>
			<td>:</td>
			<td>".$tipe."</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Telah Diambil</td>
			<td>:</td>
			<td>".$cuti_diambil." Hari</td>
		  </tr>

		  <tr>
			<td>Sisa Cuti</td>
			<td>:</td>
			<td>".$sisa_cuti." Hari</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Diminta</td>
			<td>:</td>
			<td>".$jumlah." Hari</td>
		  </tr>

		  <tr>
			<td>Dari Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($start_date))."</td>
		  </tr>

		  <tr>
			<td>Sampai Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($end_date))."</td>
		  </tr>

		  <tr>
			<td>No/Alamat Yang Bisa Dihubungi</td>
			<td>:</td>
			<td>".$no_telp."</td>
		  </tr>
		  
		  <tr>
			<td>Delegasi</td>
			<td>:</td>
			<td>".$dmce->name."</td>
		  </tr>
		  <tr>
		 
			<td>Status</td>
			<td>:</td>
			<td>".$status."</td>
		  </tr>
		  <tr>
			<td>Tanggal Pembuatan</td>
			<td>:</td>
			<td>".$tanggal_pembuatan."</td>
		  </tr>

		</table>
		*******************************************************************************************************
		<p>Terima kasih telah membuat request cuti pada website E-System ALP Petro.</br>
		Mohon untuk segera cek Request cuti diatas melalui website E-System ALP Petro. Klik link berikut. <a href='http://192.168.10.50/esistem/'> E-System ALP Petro</a></p>
		*******************************************************************************************************</center>";	
	endforeach;
	
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//end custom code

		$mail->setFrom('teamesystem@gmail.com','E-System E-Leave');
		if($status == 'Approve HOD') {
			$mail->addAddress('dekfiral@gmail.com');
			$mail->addReplyTo('dekfiral@gmail.com');
			$mail->addCC('firil.alp@alppetro.id');
			// $mail->addAddress('pga.alp.group@alppetro.id');
			// $mail->addReplyTo('pga.alp.group@alppetro.id');
			// $mail->addCC($email);
		} 
		if($status == 'Rejected HOD') {
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);
		}
		$mail->Subject = ('E-Leave | ALP E-System');
		$mail->Body    = ($mailContent);
	
		if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		// echo $mail->print_debugger();
	 } else {
		// echo "Message has been sent";
		// echo $mail->print_debugger();
	 }
	
}

public function approvehrd($id_cuti = null)
	{
		$session = $this->session->get_userdata();
		$id_user = $session['id_user'];
		$username = $session['username'];

		// die($username);

		$data['title'] = 'Progress';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$current_date = date("Y-m-d");
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
		
		$this->form_validation->set_rules('status','Status','required');
		$this->form_validation->set_rules('start_date','Start_date','required');
		$this->form_validation->set_rules('end_date','End_Date','required');
		$this->form_validation->set_rules('jumlah','Jumlah','required');

		  if(isset($_POST['save'])){
			if($this->form_validation->run() ==true ) {

			$status = $this->input->post('status');
			$id_cuti = $this->input->post('id_cuti');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$jumlah = $this->input->post('jumlah');

			$this->db->set('action_pga_by', $id_user);
			$this->db->set('action_pga_date', $current_date);
			$this->db->set('jumlah', $jumlah);
			$this->db->set('start_date', $start_date);
			$this->db->set('end_date', $end_date);
			$this->db->set('status', $status);
			$this->db->where('id_cuti', $id_cuti);
			$this->db->update('cuti');
		    // echo $this->db->last_query(); die();

			$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();
			// print_r($ecuti); die();
			foreach ($ecuti as $cuti) :
				$cuti['id_dep'];
			endforeach;
			$id_dep = $cuti['id_dep'];
			// die($id_dep);

			$email = $this->db->query("SELECT email FROM user WHERE id_jabatan in('3','46') AND id_dep = '$id_dep' ")->result_array();
			// print_r($email);
			// $mail =array();
			foreach ($email as $to) {
				$mail[] = implode(", ",$to);
			}
			$email = implode(", ", $mail);
			$to = $email;	
			// die($to);

			foreach ($ecuti as $cuti) :
				$this->sendmailhrd($to,
								$cuti['id_cuti'],
								$cuti['name'],
								$cuti['id_user'],
								$cuti['id_jabatann'],
								$cuti['jabatan'],
								$cuti['jenis_departement'],
								$cuti['tahun_cuti'],
								$cuti['tahun'],
								$cuti['tipe'],
								$cuti['tanggal_pembuatan'],
								$cuti['status'],
								$cuti['cuti_diambil'],
								$cuti['start_date'],
								$cuti['end_date'],
								$cuti['sisa_cuti'],
								$cuti['jumlah'],
								$cuti['no_telp'],
								$cuti['email'],
								$cuti['delegasi']);
			endforeach;
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Approved Success !</div>');
			redirect('cuti_karyawan');
		}
	} else {
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan_input/detail_cuti_karyawan', $data);
        $this->load->view('templates/footer'); 
	}
		
}

public function sendmailhrd($to,
								$id_cuti,
								$name, 
								$id_user, 
								$id_jabatann, 
								$jabatan, 
								$jenis_departement, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$email, 
								$delegasi){
	$session = $this->session->get_userdata();
	$id_user = $session['id_user'];
	$data['user'] = $this->db->get_where('m_user', ['username' => 
	$this->session->userdata('username')])->row_array();
																
														
	// print_r($ecuti); die();
	// $this->load->library('phpmailer_library');
	// $mail = $this->phpmailer_library->load();
	$mail = new PHPMailer(true);
	// $mail = new Exception();
	// $mail->SMTPDebug = 2;                               // Enable verbose debug output
	$mail->isSMTP();                                     
	$mail->Host = 'smtp.gmail.com';  
	$mail->SMTPAuth = true;                              
	$mail->Username = 'teamesystem@gmail.com';                 
	$mail->Password = 'itbfogutktnrfwfu';                           
	$mail->SMTPSecure = 'ssl';                            
	$mail->Port = 465;                                    
	$mail->isHTML(true); 
	

	$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
			$arr_delegasi = explode(',',$delegasi);
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where_in('id_user',$arr_delegasi);
			$list_delegasi = $this->db->get()->result();

	foreach($list_delegasi as $dmce) :
	$mailContent = 
		"<center>*******************************************************************************************************</br>
		<center><b>.:: Form E-Leave | E-System ALP Petro  ::.</b></center>
		*******************************************************************************************************
		<table>

		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$name."</td>
		  </tr>

		  <tr>
			<td>No. Badge</td>
			<td>:</td>
			<td>".$id_user."</td>
		  </tr>

		  <tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>".$jabatan."</td>
		  </tr>

		  <tr>
			<td>Department</td>
			<td>:</td>
			<td>".$jenis_departement."</td>
		  </tr>

		  <tr>
			<td>Tahun Cuti</td>
			<td>:</td>
			<td>".$tahun."</td>
		  </tr>

		  <tr>
			<td>Tipe</td>
			<td>:</td>
			<td>".$tipe."</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Telah Diambil</td>
			<td>:</td>
			<td>".$cuti_diambil." Hari</td>
		  </tr>

		  <tr>
			<td>Sisa Cuti</td>
			<td>:</td>
			<td>".$sisa_cuti." Hari</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Diminta</td>
			<td>:</td>
			<td>".$jumlah." Hari</td>
		  </tr>

		  <tr>
			<td>Dari Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($start_date))."</td>
		  </tr>

		  <tr>
			<td>Sampai Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($end_date))."</td>
		  </tr>

		  <tr>
			<td>No/Alamat Yang Bisa Dihubungi</td>
			<td>:</td>
			<td>".$no_telp."</td>
		  </tr>
		  
		  <tr>
			<td>Delegasi</td>
			<td>:</td>
			<td>".$dmce->name."</td>
		  </tr>
		  <tr>
		 
			<td>Status</td>
			<td>:</td>
			<td>".$status."</td>
		  </tr>
		  <tr>
			<td>Tanggal Pembuatan</td>
			<td>:</td>
			<td>".$tanggal_pembuatan."</td>
		  </tr>

		</table>
		*******************************************************************************************************
		<p>Terima kasih telah membuat request cuti pada website E-System ALP Petro.</br>
		Mohon untuk segera cek Request cuti diatas melalui website E-System ALP Petro. Klik link berikut. <a href='http://192.168.10.50/esistem/'> E-System ALP Petro</a></p>
		*******************************************************************************************************</center>";	
	endforeach;
	
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//end custom code

		$mail->setFrom('teamesystem@gmail.com','E-System E-Leave');
		if($status == 'Approve HRD') {
			$mail->addAddress('dekfiral@gmail.com');
			$mail->addReplyTo('dekfiral@gmail.com');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addCC($address);
			// }
		} 
		if($status == 'Rejected HRD' AND $id_jabatann == '46' OR $id_jabatann == '3') {
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $mail->addAddress('fery.alp@alppetro.id');
			// $mail->addReplyTo('fery.alp@alppetro.id');
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addCC($address);
			// }
		} else if ($status == 'Rejected HRD') {
			$mail->addAddress('dekfiral@gmail.com');
			$mail->addReplyTo('dekfiral@gmail.com');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addCC($address);
			// }
		}
		$mail->Subject = ('E-Leave | ALP E-System');
		$mail->Body    = ($mailContent);
	
		if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		// echo $mail->print_debugger();
	 } else {
		// echo "Message has been sent";
		// echo $mail->print_debugger();
	 }
	
}

public function reject_hod($id_cuti = null)
	{
		$session = $this->session->get_userdata();
		$id_user = $session['id_user'];

		$data['title'] = 'Progress';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$current_date = date("Y-m-d");
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();

		if ($user['id_jabatan'] == 10) {
			$status = 'Rejected BOD';
		} else {
			$status = 'Rejected HOD';
		}
			
			// die($status);

			$this->db->set('action_head_by', $id_user);
			$this->db->set('action_head_date', $current_date);
			$this->db->set('status', $status);
			$this->db->where('id_cuti', $id_cuti);
			$this->db->update('cuti');
			// echo $this->db->last_query(); die();

			$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();

			foreach ($ecuti as $cuti) :
				$this->rejecthod($cuti['id_cuti'],
								$cuti['name'],
								$cuti['id_userrrr'],
								$cuti['jabatan'],
								$cuti['jenis_departement'],
								$cuti['tahun_cuti'],
								$cuti['tahun'],
								$cuti['tipe'],
								$cuti['tanggal_pembuatan'],
								$cuti['status'],
								$cuti['cuti_diambil'],
								$cuti['start_date'],
								$cuti['end_date'],
								$cuti['sisa_cuti'],
								$cuti['jumlah'],
								$cuti['no_telp'],
								$cuti['email'],
								$cuti['delegasi']);
			endforeach;
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Approved Success !</div>');
			redirect('cuti_karyawan');
}

public function rejecthod($id_cuti,
								$name, 
								$id_userrrr, 
								$jabatan, 
								$jenis_departement, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$email, 
								$delegasi){
	$session = $this->session->get_userdata();
	$id_user = $session['id_user'];
	$data['user'] = $this->db->get_where('m_user', ['username' => 
	$this->session->userdata('username')])->row_array();
																
														
	$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
	$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();

	// print_r($ecuti); die();
	// $this->load->library('phpmailer_library');
	// $mail = $this->phpmailer_library->load();
	$mail = new PHPMailer(true);
	// $mail = new Exception();
	// $mail->SMTPDebug = 2;                               // Enable verbose debug output
	$mail->isSMTP();                                     
	$mail->Host = 'smtp.gmail.com';  
	$mail->SMTPAuth = true;                              
	$mail->Username = 'teamesystem@gmail.com';                 
	$mail->Password = 'itbfogutktnrfwfu';                           
	$mail->SMTPSecure = 'ssl';                            
	$mail->Port = 465;                                    
	$mail->isHTML(true); 
	

	$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
			$arr_delegasi = explode(',',$delegasi);
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where_in('id_user',$arr_delegasi);
			$list_delegasi = $this->db->get()->result();

	foreach($list_delegasi as $dmce) :
	$mailContent = 
		"<center>*******************************************************************************************************</br>
		<center><b>.:: Form E-Leave | E-System ALP Petro  ::.</b></center>
		*******************************************************************************************************
		<table>

		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$name."</td>
		  </tr>

		  <tr>
			<td>No. Badge</td>
			<td>:</td>
			<td>".$id_userrrr."</td>
		  </tr>

		  <tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>".$jabatan."</td>
		  </tr>

		  <tr>
		  <td>Department</td>
		  <td>:</td>
		  <td>".$jenis_departement."</td>
		</tr>

		  <tr>
			<td>Tahun Cuti</td>
			<td>:</td>
			<td>".$tahun."</td>
		  </tr>

		  <tr>
			<td>Tipe</td>
			<td>:</td>
			<td>".$tipe."</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Telah Diambil</td>
			<td>:</td>
			<td>".$cuti_diambil." Hari</td>
		  </tr>

		  <tr>
			<td>Sisa Cuti</td>
			<td>:</td>
			<td>".$sisa_cuti." Hari</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Diminta</td>
			<td>:</td>
			<td>".$jumlah." Hari</td>
		  </tr>

		  <tr>
			<td>Dari Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($start_date))."</td>
		  </tr>

		  <tr>
			<td>Sampai Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($end_date))."</td>
		  </tr>

		  <tr>
			<td>No/Alamat Yang Bisa Dihubungi</td>
			<td>:</td>
			<td>".$no_telp."</td>
		  </tr>
		  
		  <tr>
			<td>Delegasi</td>
			<td>:</td>
			<td>".$dmce->name."</td>
		  </tr>
		  <tr>
		 
			<td>Status</td>
			<td>:</td>
			<td>".$status."</td>
		  </tr>
		  <tr>
			<td>Tanggal Pembuatan</td>
			<td>:</td>
			<td>".$tanggal_pembuatan."</td>
		  </tr>

		</table>
		*******************************************************************************************************
		<p>Terima kasih telah membuat request cuti pada website E-System ALP Petro.</br>
		Mohon untuk segera cek Request cuti diatas melalui website E-System ALP Petro. Klik link berikut. <a href='http://192.168.10.50/esistem/'> E-System ALP Petro</a></p>
		*******************************************************************************************************</center>";	
	endforeach;
	
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//end custom code

		$mail->setFrom('teamesystem@gmail.com','E-System E-Leave');
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);

		$mail->Subject = ('E-Leave | ALP E-System');
		$mail->Body    = ($mailContent);
	
		if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		// echo $mail->print_debugger();
	 } else {
		// echo "Message has been sent";
		// echo $mail->print_debugger();
	 }
	
}

public function approvegm($id_cuti = null)
	{
		$session = $this->session->get_userdata();
		$id_user = $session['id_user'];
		// die($username);

		$data['title'] = 'Progress';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$current_date = date("Y-m-d");
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
		$this->form_validation->set_rules('status','Status','required');

		  if(isset($_POST['save'])){
			if($this->form_validation->run() ==true ) {

			$status = $this->input->post('status');
			// die($status);
			if ($user['id_jabatan'] == 3 OR $user['id_jabatan'] == 46) {
				$this->db->set('action_head_by', $id_user);
				$this->db->set('action_head_date', $current_date);
			} elseif ($user['id_jabatan'] == 30 OR $user['id_jabatan'] == 47) {
				$this->db->set('action_pga_by', $id_user);
				$this->db->set('action_pga_date', $current_date);
			} 
			$this->db->set('status', $status);
			$this->db->where('id_cuti', $id_cuti);
			$this->db->update('cuti');
		    // echo $this->db->last_query(); die();

			$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();
			// print_r($ecuti); die();
			foreach ($ecuti as $cuti) :
				$this->sendmail_gm($cuti['id_cuti'],
								$cuti['name'],
								$cuti['id_userrrr'],
								$cuti['jabatan'],
								$cuti['jenis_departement'],
								$cuti['tahun_cuti'],
								$cuti['tahun'],
								$cuti['tipe'],
								$cuti['tanggal_pembuatan'],
								$cuti['status'],
								$cuti['cuti_diambil'],
								$cuti['start_date'],
								$cuti['end_date'],
								$cuti['sisa_cuti'],
								$cuti['jumlah'],
								$cuti['no_telp'],
								$cuti['email'],
								$cuti['delegasi']);
			endforeach;
			// die($cuti['email']);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Approved Success !</div>');
			redirect('cuti_karyawan');
		}
	} else {
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan_input/detail_cuti_karyawan', $data);
        $this->load->view('templates/footer'); 
	}
		
}

public function sendmail_gm($id_cuti,
								$name, 
								$id_userrrr, 
								$jabatan, 
								$jenis_departement, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$email, 
								$delegasi){
	$session = $this->session->get_userdata();
	$id_user = $session['id_user'];
	$data['user'] = $this->db->get_where('m_user', ['username' => 
	$this->session->userdata('username')])->row_array();
																
														
	// print_r($ecuti); die();
	// $this->load->library('phpmailer_library');
	// $mail = $this->phpmailer_library->load();
	$mail = new PHPMailer(true);
	// $mail = new Exception();
	// $mail->SMTPDebug = 2;                               // Enable verbose debug output
	$mail->isSMTP();                                     
	$mail->Host = 'smtp.gmail.com';  
	$mail->SMTPAuth = true;                              
	$mail->Username = 'teamesystem@gmail.com';                 
	$mail->Password = 'itbfogutktnrfwfu';                           
	$mail->SMTPSecure = 'ssl';                            
	$mail->Port = 465;                                    
	$mail->isHTML(true); 
	

	$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
			$arr_delegasi = explode(',',$delegasi);
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where_in('id_user',$arr_delegasi);
			$list_delegasi = $this->db->get()->result();

	foreach($list_delegasi as $dmce) :
	$mailContent = 
		"<center>*******************************************************************************************************</br>
		<center><b>.:: Form E-Leave | E-System ALP Petro  ::.</b></center>
		*******************************************************************************************************
		<table>

		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$name."</td>
		  </tr>

		  <tr>
			<td>No. Badge</td>
			<td>:</td>
			<td>".$id_userrrr."</td>
		  </tr>

		  <tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>".$jabatan."</td>
		  </tr>

		  <tr>
			<td>Department</td>
			<td>:</td>
			<td>".$jenis_departement."</td>
		  </tr>

		  <tr>
			<td>Tahun Cuti</td>
			<td>:</td>
			<td>".$tahun."</td>
		  </tr>

		  <tr>
			<td>Tipe</td>
			<td>:</td>
			<td>".$tipe."</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Telah Diambil</td>
			<td>:</td>
			<td>".$cuti_diambil." Hari</td>
		  </tr>

		  <tr>
			<td>Sisa Cuti</td>
			<td>:</td>
			<td>".$sisa_cuti." Hari</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Diminta</td>
			<td>:</td>
			<td>".$jumlah." Hari</td>
		  </tr>

		  <tr>
			<td>Dari Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($start_date))."</td>
		  </tr>

		  <tr>
			<td>Sampai Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($end_date))."</td>
		  </tr>

		  <tr>
			<td>No/Alamat Yang Bisa Dihubungi</td>
			<td>:</td>
			<td>".$no_telp."</td>
		  </tr>
		  
		  <tr>
			<td>Delegasi</td>
			<td>:</td>
			<td>".$dmce->name."</td>
		  </tr>
		  <tr>
		 
			<td>Status</td>
			<td>:</td>
			<td>".$status."</td>
		  </tr>
		  <tr>
			<td>Tanggal Pembuatan</td>
			<td>:</td>
			<td>".$tanggal_pembuatan."</td>
		  </tr>

		</table>
		*******************************************************************************************************
		<p>Terima kasih telah membuat request cuti pada website E-System ALP Petro.</br>
		Mohon untuk segera cek Request cuti diatas melalui website E-System ALP Petro. Klik link berikut. <a href='http://192.168.10.50/esistem/'> E-System ALP Petro</a></p>
		*******************************************************************************************************</center>";	
	endforeach;
	
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//end custom code

		$mail->setFrom('teamesystem@gmail.com','E-System E-Leave');
		if($status == 'Approve BOD') {
			$mail->addAddress('dekfiral@gmail.com');
			$mail->addReplyTo('dekfiral@gmail.com');
			$mail->addCC('firil.alp@alppetro.id');
			// $mail->addAddress('pga.alp.group@alppetro.id');
			// $mail->addReplyTo('pga.alp.group@alppetro.id');
			// $mail->addCC($email);
		} 
		if($status == 'Rejected BOD') {
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);
		}
		$mail->Subject = ('E-Leave | ALP E-System');
		$mail->Body    = ($mailContent);
	
		if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		// echo $mail->print_debugger();
	 } else {
		// echo "Message has been sent";
		// echo $mail->print_debugger();
	 }
	
}

public function leave_check($tahun_cuti){
	
		$session = $this->session->get_userdata();
		$id_user = $session['id_user'];

		 $data['diambil'] = $this->db->query(" SELECT
													CASE WHEN jumlah is null THEN 0 
													ELSE SUM(jumlah) END  AS diambil
													FROM ecuti 
													WHERE id_user = '$id_user'
													AND tahun_cuti = '$tahun_cuti'
													AND status = 'Approve HRD'
													AND tipe ='cuti'")->row_array();
	
		$data['sisacuti'] = $this->db->query(" SELECT 12 -
													CASE WHEN jumlah is null THEN 0 
													ELSE SUM(jumlah) END  AS sisacuti
													FROM ecuti 
													WHERE id_user = '$id_user'
													AND tahun_cuti = '$tahun_cuti'
													AND status = 'Approve HRD'
													AND tipe ='cuti'")->row_array();
		// print_r($data); die();
		echo json_encode($data);
}

public function edit_hrd($id_cuti=null)
    {
        $data['title'] = 'Detail E-Leave';
        $data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
		$arr_delegasi = explode(',',$delegasi);
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where_in('id_user',$arr_delegasi);
		$list_delegasi = $this->db->get()->result();
		
		$data['delegasi'] = $list_delegasi;
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$data['userr'] = $this->db->get('user')->result_array();
		$data['cuti'] = $this->db->get('ecuti')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();

		$this->form_validation->set_rules('start_date','Start_date','required');
		$this->form_validation->set_rules('end_date','End_Date','required');
		$this->form_validation->set_rules('jumlah','Jumlah','required');

	if($this->form_validation->run() ==false ) {
        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan_input/edit_hrd', $data);
        $this->load->view('templates/footer');
	} else {

		$id_cuti = $this->input->post('id_cuti');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$jumlah = $this->input->post('jumlah');

		$this->db->set('jumlah', $jumlah);
		$this->db->set('start_date', $start_date);
		$this->db->set('end_date', $end_date);
		$this->db->where('id_cuti', $id_cuti);
		$this->db->update('cuti');
		
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Edit Success !</div>');
		redirect('cuti_karyawan');
    }
}

public function approvedhrd($id_cuti=null)
    {
        $data['title'] = 'Detail E-Leave';
        $data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
		$arr_delegasi = explode(',',$delegasi);
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where_in('id_user',$arr_delegasi);
		$list_delegasi = $this->db->get()->result();
		
		$data['delegasi'] = $list_delegasi;
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$data['userr'] = $this->db->get('user')->result_array();
		$data['cuti'] = $this->db->get('ecuti')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();

		$this->form_validation->set_rules('start_date','Start_date','required');
		$this->form_validation->set_rules('end_date','End_Date','required');
		$this->form_validation->set_rules('jumlah','Jumlah','required');
		$this->form_validation->set_rules('status','Status','required');

	if($this->form_validation->run() ==false ) {
        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan_input/approvedhrd', $data);
        $this->load->view('templates/footer');
	} else {

		$id_cuti = $this->input->post('id_cuti');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$jumlah = $this->input->post('jumlah');
		$status = $this->input->post('status');

		$this->db->set('status', $status);
		$this->db->set('jumlah', $jumlah);
		$this->db->set('start_date', $start_date);
		$this->db->set('end_date', $end_date);
		$this->db->where('id_cuti', $id_cuti);
		$this->db->update('cuti');

		$ecuti = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->result_array();
			// print_r($ecuti); die();
			foreach ($ecuti as $cuti) :
				$cuti['id_dep'];
			endforeach;
			$id_dep = $cuti['id_dep'];
			// die($id_dep);

			$email = $this->db->query("SELECT email FROM user WHERE id_jabatan in('3','46') AND id_dep = '$id_dep' ")->result_array();
			// print_r($email);
			// $mail =array();
			foreach ($email as $to) {
				$mail[] = implode(", ",$to);
			}
			$email = implode(", ", $mail);
			$to = $email;	
			// die($to);

			foreach ($ecuti as $cuti) :
			$this->sendmail_approvedhrd($to,
								$cuti['id_cuti'],
								$cuti['name'],
								$cuti['id_user'],
								$cuti['id_jabatann'],
								$cuti['jabatan'],
								$cuti['jenis_departement'],
								$cuti['tahun_cuti'],
								$cuti['tahun'],
								$cuti['tipe'],
								$cuti['tanggal_pembuatan'],
								$cuti['status'],
								$cuti['cuti_diambil'],
								$cuti['start_date'],
								$cuti['end_date'],
								$cuti['sisa_cuti'],
								$cuti['jumlah'],
								$cuti['no_telp'],
								$cuti['email'],
								$cuti['delegasi']);
			endforeach;
		
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Approved Success !</div>');
		redirect('cuti_karyawan');
    }
}

public function sendmail_approvedhrd($to,
								$id_cuti,
								$name, 
								$id_user, 
								$id_jabatann, 
								$jabatan, 
								$jenis_departement, 
								$tahun_cuti, 
								$tahun, 
								$tipe, 
								$tanggal_pembuatan, 
								$status, 
								$cuti_diambil, 
								$start_date, 
								$end_date, 
								$sisa_cuti, 
								$jumlah, 
								$no_telp, 
								$email, 
								$delegasi){
	$session = $this->session->get_userdata();
	// $id_user = $session['id_user'];
	$data['user'] = $this->db->get_where('m_user', ['username' => 
	$this->session->userdata('username')])->row_array();
																
														
	// print_r($ecuti); die();
	// $this->load->library('phpmailer_library');
	// $mail = $this->phpmailer_library->load();
	$mail = new PHPMailer(true);
	// $mail = new Exception();
	// $mail->SMTPDebug = 2;                               // Enable verbose debug output
	$mail->isSMTP();                                     
	$mail->Host = 'smtp.gmail.com';  
	$mail->SMTPAuth = true;                              
	$mail->Username = 'teamesystem@gmail.com';                 
	$mail->Password = 'itbfogutktnrfwfu';                           
	$mail->SMTPSecure = 'ssl';                            
	$mail->Port = 465;                                    
	$mail->isHTML(true); 
	

	$delegasi = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row()->delegasi;
			$arr_delegasi = explode(',',$delegasi);
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where_in('id_user',$arr_delegasi);
			$list_delegasi = $this->db->get()->result();

	foreach($list_delegasi as $dmce) :
	$mailContent = 
		"<center>*******************************************************************************************************</br>
		<center><b>.:: Form E-Leave | E-System ALP Petro  ::.</b></center>
		*******************************************************************************************************
		<table>

		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$name."</td>
		  </tr>

		  <tr>
			<td>No. Badge</td>
			<td>:</td>
			<td>".$id_user."</td>
		  </tr>

		  <tr>
			<td>Jabatan</td>
			<td>:</td>
			<td>".$jabatan."</td>
		  </tr>

		  <tr>
			<td>Department</td>
			<td>:</td>
			<td>".$jenis_departement."</td>
		  </tr>

		  <tr>
			<td>Tahun Cuti</td>
			<td>:</td>
			<td>".$tahun."</td>
		  </tr>

		  <tr>
			<td>Tipe</td>
			<td>:</td>
			<td>".$tipe."</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Telah Diambil</td>
			<td>:</td>
			<td>".$cuti_diambil." Hari</td>
		  </tr>

		  <tr>
			<td>Sisa Cuti</td>
			<td>:</td>
			<td>".$sisa_cuti." Hari</td>
		  </tr>

		  <tr>
			<td>Cuti Yang Diminta</td>
			<td>:</td>
			<td>".$jumlah." Hari</td>
		  </tr>

		  <tr>
			<td>Dari Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($start_date))."</td>
		  </tr>

		  <tr>
			<td>Sampai Tanggal</td>
			<td>:</td>
			<td>".date('d M Y',strtotime($end_date))."</td>
		  </tr>

		  <tr>
			<td>No/Alamat Yang Bisa Dihubungi</td>
			<td>:</td>
			<td>".$no_telp."</td>
		  </tr>
		  
		  <tr>
			<td>Delegasi</td>
			<td>:</td>
			<td>".$dmce->name."</td>
		  </tr>
		  <tr>
		 
			<td>Status</td>
			<td>:</td>
			<td>".$status."</td>
		  </tr>
		  <tr>
			<td>Tanggal Pembuatan</td>
			<td>:</td>
			<td>".$tanggal_pembuatan."</td>
		  </tr>

		</table>
		*******************************************************************************************************
		<p>Terima kasih telah membuat request cuti pada website E-System ALP Petro.</br>
		Mohon untuk segera cek Request cuti diatas melalui website E-System ALP Petro. Klik link berikut. <a href='http://192.168.10.50/esistem/'> E-System ALP Petro</a></p>
		*******************************************************************************************************</center>";	
	endforeach;
	
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//end custom code

		$mail->setFrom('teamesystem@gmail.com','E-System E-Leave');
		if($status == 'Approve HRD') {
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addCC($address);
			// }
		} 
		if($status == 'Rejected HRD' AND $id_jabatann == '46' OR $id_jabatann == '3') {
			$mail->addAddress('firil.alp@alppetro.id');
			$mail->addReplyTo('firil.alp@alppetro.id');
			// $mail->addAddress('fery.alp@alppetro.id');
			// $mail->addReplyTo('fery.alp@alppetro.id');
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addCC($address);
			// }
		} else if ($status == 'Rejected HRD') {
			$mail->addAddress('dekfiral@gmail.com');
			$mail->addReplyTo('dekfiral@gmail.com');
			// $mail->addAddress($email);
			// $mail->addReplyTo($email);
			// $addresses = explode(',', $to);                
			// foreach ($addresses as $address) {
    		// 	$mail->addCC($address);
			// }
		}
		$mail->Subject = ('E-Leave | ALP E-System');
		$mail->Body    = ($mailContent);
	
		if(!$mail->Send()) {
		// echo "Mailer Error: " . $mail->ErrorInfo;
		// echo $mail->print_debugger();
	 } else {
		// echo "Message has been sent";
		// echo $mail->print_debugger();
	 }
	
	}

}