<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cuti_karyawan extends CI_Controller 
{

public function index()
	{

		$session = $this->session->get_userdata();
		$id_dep = $session['id_dep'];
		$id_user = $session['id_user'];
		// $id_jabatan = $session['id_jabatan'];

		$data['title'] = 'E-Leave';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();
		$user = $this->db->get_where('m_user',['id_user' => $id_user])->row_array();
		
		$this->load->model('cuti_model','name');
	


// View HOD & SPRD 		
		if($user['id_jabatan'] == 3 OR $user['id_jabatan'] == 46) {
			
			if(isset($_GET['tahun']) && ! empty($_GET['tahun'])){ 
				$tahun = $_GET['tahun'];

				$tahun = $tahun;
				$data['jumlah_user'] = $this->db->query(" SELECT 
												CASE WHEN jumlah is null THEN 0 
												ELSE SUM(jumlah) END  AS cuti_yang_diambil
												FROM ecuti 
												WHERE id_user = '$id_user'
												AND tahun = '$tahun'
												AND status = 'Approve HRD'
												AND tipe ='cuti'")->row_array();

				$tahun = $tahun;
				$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
								CASE WHEN jumlah is null THEN 0 
								ELSE SUM(jumlah) END  AS sisa_cuti
								FROM ecuti 
								WHERE id_user = '$id_user'
								AND tahun = '$tahun'
								AND status = 'Approve HRD'
								AND tipe ='cuti'")->row_array();

				$data['ecutii'] = $this->name->getCheckReportByPic($id_dep, $tahun, $id_user);
	
			} else {
				$current_date = date("Y");
				$data['jumlah_user'] = $this->db->query(" SELECT 
											  CASE WHEN jumlah is null THEN 0 
											  ELSE SUM(jumlah) END  AS cuti_yang_diambil
											  FROM ecuti 
											  WHERE id_user = '$id_user'
											  AND tahun = '$current_date'
											  AND status = 'Approve HRD'
											  AND tipe ='cuti'")->row_array();

				$current_date = date("Y");
				$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
											CASE WHEN jumlah is null THEN 0 
											ELSE SUM(jumlah) END  AS sisa_cuti
											FROM ecuti 
											WHERE id_user = '$id_user'
											AND tahun = '$current_date'
											AND status = 'Approve HRD'
											AND tipe ='cuti'")->row_array();
				$data['ecutii'] = $this->name->getCheckReportByPic($id_dep, $current_date, $id_user);
}
			
// View Team PGA	
		}else if($user['id_jabatan'] == 30 OR $user['id_jabatan'] == 47){

			if(isset($_GET['tahun']) && ! empty($_GET['tahun'])){ 
				$tahun = $_GET['tahun'];

				$tahun = $tahun;
				$data['jumlah_user'] = $this->db->query(" SELECT 
												CASE WHEN jumlah is null THEN 0 
												ELSE SUM(jumlah) END  AS cuti_yang_diambil
												FROM ecuti 
												WHERE id_user = '$id_user'
												AND tahun = '$tahun'
												AND status = 'Approve HRD'
												AND tipe ='cuti'")->row_array();

				$tahun = $tahun;
				$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
								CASE WHEN jumlah is null THEN 0 
								ELSE SUM(jumlah) END  AS sisa_cuti
								FROM ecuti 
								WHERE id_user = '$id_user'
								AND tahun = '$tahun'
								AND status = 'Approve HRD'
								AND tipe ='cuti'")->row_array();

				$data['eecuti'] = $this->name->getCheckReportHOD($tahun, $id_user);
	
			} else {
				$current_date = date("Y");
				$data['jumlah_user'] = $this->db->query(" SELECT 
											  CASE WHEN jumlah is null THEN 0 
											  ELSE SUM(jumlah) END  AS cuti_yang_diambil
											  FROM ecuti 
											  WHERE id_user = '$id_user'
											  AND tahun = '$current_date'
											  AND status = 'Approve HRD'
											  AND tipe ='cuti'")->row_array();

				$current_date = date("Y");
				$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
											CASE WHEN jumlah is null THEN 0 
											ELSE SUM(jumlah) END  AS sisa_cuti
											FROM ecuti 
											WHERE id_user = '$id_user'
											AND tahun = '$current_date'
											AND status = 'Approve HRD'
											AND tipe ='cuti'")->row_array();
				$data['eecuti'] = $this->name->getCheckReportHOD($current_date, $id_user);
}
			
// View GM			
		} else if($user['id_jabatan'] == 10 ){
			if(isset($_GET['tahun']) && ! empty($_GET['tahun'])){ 
				$tahun = $_GET['tahun'];

				$tahun = $tahun;
				$data['jumlah_user'] = $this->db->query(" SELECT 
												CASE WHEN jumlah is null THEN 0 
												ELSE SUM(jumlah) END  AS cuti_yang_diambil
												FROM ecuti 
												WHERE id_user = '$id_user'
												AND tahun = '$tahun'
												AND status = 'Approve HRD'
												AND tipe ='cuti'")->row_array();

				$tahun = $tahun;
				$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
								CASE WHEN jumlah is null THEN 0 
								ELSE SUM(jumlah) END  AS sisa_cuti
								FROM ecuti 
								WHERE id_user = '$id_user'
								AND tahun = '$tahun'
								AND status = 'Approve HRD'
								AND tipe ='cuti'")->row_array();

				$data['cuti'] = $this->name->getApproveGM($tahun);
	
			} else {
				$current_date = date("Y");
				$data['jumlah_user'] = $this->db->query(" SELECT 
											  CASE WHEN jumlah is null THEN 0 
											  ELSE SUM(jumlah) END  AS cuti_yang_diambil
											  FROM ecuti 
											  WHERE id_user = '$id_user'
											  AND tahun = '$current_date'
											  AND status = 'Approve HRD'
											  AND tipe ='cuti'")->row_array();

				$current_date = date("Y");
				$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
											CASE WHEN jumlah is null THEN 0 
											ELSE SUM(jumlah) END  AS sisa_cuti
											FROM ecuti 
											WHERE id_user = '$id_user'
											AND tahun = '$current_date'
											AND status = 'Approve HRD'
											AND tipe ='cuti'")->row_array();
				$data['cuti'] = $this->name->getApproveGM($current_date);
}
			
// View User			
		} else {
		
				if(isset($_GET['tahun']) && ! empty($_GET['tahun'])){ 
					$tahun = $_GET['tahun'];

					$tahun = $tahun;
					$data['jumlah_user'] = $this->db->query(" SELECT 
													CASE WHEN jumlah is null THEN 0 
													ELSE SUM(jumlah) END  AS cuti_yang_diambil
													FROM ecuti 
													WHERE id_user = '$id_user'
													AND tahun = '$tahun'
													AND status = 'Approve HRD'
													AND tipe ='cuti'")->row_array();

					$tahun = $tahun;
					$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
									CASE WHEN jumlah is null THEN 0 
									ELSE SUM(jumlah) END  AS sisa_cuti
									FROM ecuti 
									WHERE id_user = '$id_user'
									AND tahun = '$tahun'
									AND status = 'Approve HRD'
									AND tipe ='cuti'")->row_array();

					$data['ecuti'] = $this->name->getCheckReport($tahun);
		
				} else {
					$current_date = date("Y");
					$data['jumlah_user'] = $this->db->query(" SELECT 
												  CASE WHEN jumlah is null THEN 0 
												  ELSE SUM(jumlah) END  AS cuti_yang_diambil
												  FROM ecuti 
												  WHERE id_user = '$id_user'
												  AND tahun = '$current_date'
												  AND status = 'Approve HRD'
												  AND tipe ='cuti'")->row_array();

					$current_date = date("Y");
					$data['sisa_cuti'] = $this->db->query(" SELECT 12 -
												CASE WHEN jumlah is null THEN 0 
												ELSE SUM(jumlah) END  AS sisa_cuti
												FROM ecuti 
												WHERE id_user = '$id_user'
												AND tahun = '$current_date'
												AND status = 'Approve HRD'
												AND tipe ='cuti'")->row_array();
					$data['ecuti'] = $this->name->getCheckReport($current_date);
				}
		}


		
		
		$this->load->model('Listall_cuti_model');
		$data['option_tahun'] = $this->Listall_cuti_model->option_tahuncuti();
		$data['inventaris'] = $this->db->get('inventaris')->result_array();
		// $data['ecuti'] = $this->db->get('ecuti')->result_array();
		$data['daily_routine'] = $this->db->get('dailyroutine')->result_array();
		$data['userr'] = $this->db->get('user')->result_array();
		$data['device'] = $this->db->get('device')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();
		$data['admin'] = $this->db->query("SELECT * FROM user where rule ='admin'")->result_array();

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('cuti_karyawan/index', $data);
			$this->load->view('templates/footer');		
}

public function list_all()
	{

		$session = $this->session->get_userdata();
		$id_dep = $session['id_dep'];
		$id_user = $session['id_user'];
		// $id_jabatan = $session['id_jabatan'];

		$data['title'] = 'E-Leave';
		$data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();

		$data['inventaris'] = $this->db->get('inventaris')->result_array();
		// $data['ecuti'] = $this->db->get('ecuti')->result_array();
		$data['daily_routine'] = $this->db->get('dailyroutine')->result_array();
		$data['userr'] = $this->db->get('user')->result_array();
		$data['device'] = $this->db->get('device')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();
		$data['admin'] = $this->db->query("SELECT * FROM user where rule ='admin'")->result_array();

		$this->load->model('Listall_cuti_model');

		if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user

            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                $url_export = 'cuti_karyawan/export?filter=1&tanggal='.$tgl;
                $ecuti = $this->Listall_cuti_model->view_by_date($tgl); // Panggil fungsi view_by_date yang ada di Listall_cuti_model
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $url_export = 'cuti_karyawan/export?filter=2&bulan='.$bulan.'&tahun='.$tahun;
                $ecuti = $this->Listall_cuti_model->view_by_month($bulan, $tahun); // Panggil fungsi view_by_month yang ada di Listall_cuti_model
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                $url_export = 'cuti_karyawan/export?filter=3&tahun='.$tahun;
                $ecuti = $this->Listall_cuti_model->view_by_year($tahun); // Panggil fungsi view_by_year yang ada di Listall_cuti_model
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $url_export = 'cuti_karyawan/export';
            $ecuti = $this->Listall_cuti_model->view_all(); // Panggil fungsi view_all yang ada di Listall_cuti_model
        }
		$data['url_export'] = base_url($url_export);
		$data['ecuti'] = $ecuti;
        $data['option_tahun'] = $this->Listall_cuti_model->option_tahun();
		$data['progress'] = $this->db->get('m_progress')->result_array();

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('cuti_karyawan/list_all_cuti_karyawan', $data);
			$this->load->view('templates/footer');		
}

public function export($id_cuti=0){
		$this->load->model('Listall_cuti_model');

        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user

            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                $ecuti = $this->Listall_cuti_model->view_by_date($tgl); // Panggil fungsi view_by_date yang ada di Listall_cuti_model
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $ecuti = $this->Listall_cuti_model->view_by_month($bulan, $tahun); // Panggil fungsi view_by_month yang ada di Listall_cuti_model
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                $ecuti = $this->Listall_cuti_model->view_by_year($tahun); // Panggil fungsi view_by_year yang ada di Listall_cuti_model
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ecuti = $this->Listall_cuti_model->view_all(); // Panggil fungsi view_all yang ada di Listall_cuti_model
        }
		$spreadsheet = new Spreadsheet;

		$spreadsheet->setActiveSheetIndex(0)
		          ->setCellValue('A1', 'NO. BADGE')
		          ->setCellValue('B1', 'NAMA')
		          ->setCellValue('C1', 'DEPARTMENT')
		          ->setCellValue('D1', 'TIPE')
		          ->setCellValue('E1', 'TAHUN CUTI')
		          ->setCellValue('F1', 'CUTI YANG TELAH DIAMBIL')
		          ->setCellValue('G1', 'SISA CUTI')
		          ->setCellValue('H1', 'CUTI YANG DIMINTA')
		          ->setCellValue('I1', 'DELEGASI')
		          ->setCellValue('J1', 'DARI TANGGAL')
		          ->setCellValue('K1', 'SAMPAI TANGGAL')
		          ->setCellValue('L1', 'NO/ALAMAT YANG BISA DIHUBUNGI')
		          ->setCellValue('M1', 'TANGGAL PEMBUATAN');

		$kolom = 2;
		$nomor = 1;
		
		foreach($ecuti as $cuti) {
			$spreadsheet->setActiveSheetIndex(0)
			// ->setCellValue('A' . $kolom, $nomor)
			->setCellValue('A' . $kolom, '#'.$cuti['id_user'])
			->setCellValue('B' . $kolom, $cuti['name'])
			->setCellValue('C' . $kolom, $cuti['jenis_departement'])
			->setCellValue('D' . $kolom, $cuti['tipe'])
			->setCellValue('E' . $kolom, $cuti['tahun_cuti'])
			->setCellValue('F' . $kolom, $cuti['cuti_diambil'])
			->setCellValue('G' . $kolom, $cuti['sisa_cuti'])
			->setCellValue('H' . $kolom, $cuti['jumlah'])
			->setCellValue('I' . $kolom, $cuti['delegasi'])
			->setCellValue('J' . $kolom, date('d M Y',strtotime($cuti['start_date'])))
			->setCellValue('K' . $kolom, date('d M Y',strtotime($cuti['end_date'])))
			->setCellValue('L' . $kolom, $cuti['no_telp'])
			->setCellValue('M' . $kolom, $cuti['tanggal_pembuatan']);

		   $kolom++;
		   $nomor++;
		}

		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="E-Leave_'.date('Y_m_d_H_i_s').'_.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		
}

public function detail_all($id_cuti=null)
    {
        $data['title'] = 'Detail E-Leave';
        $data['user'] = $this->db->get_where('m_user', ['username' => 
		$this->session->userdata('username')])->row_array();
		
		$data['ecuti'] = $this->db->get_where('ecuti', ['id_cuti' => $id_cuti])->row_array();
		$data['userr'] = $this->db->get('user')->result_array();
		$data['departement'] = $this->db->get('departement')->result_array();

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
        $this->load->view('cuti_karyawan/detail_all_cuti_karyawan', $data);
        $this->load->view('templates/footer');
    }
}