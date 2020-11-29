<?php namespace App\Controllers;
use App\Models\ArtikelModel;
use App\Models\UserModel;
class Article extends BaseController
{
	protected $artikelModel;
	public function __construct()
	{
		$this->artikelModel= new ArtikelModel();
	}
	public function index()
	{
		
		//$artikel = $this->artikelModel->findAll();
		$data = [
			'artikel' => $this->artikelModel->getArtikelUser()->findAll()
			
		];
		
		$title = [
			'title' => 'Artikel | Panganku'
		];
		// dd($data);
		// dd(session()->get('is_admin'));
		echo view('header_v',$title);
		echo view('article/article_v',$data);
		echo view('footer_v');
	}

	public function detail($id_artikel)
	{
		$data = [
			'artikel' => $this->artikelModel->getArtikel($id_artikel),
			'artikeluser' => $this->artikelModel->getArtikelUser($id_artikel)
		];
		$title = ['title' => 'Detail Artikel | Panganku'];
		// dd($data);
		echo view('header_v',$title);
		echo view('article/detail_v',$data);
		echo view('footer_v');
	}
	public function create()
	{
				
		$title =  ['title' => 'Buat Article | Panganku'];
		$data = [
			'validation' =>  \Config\Services::validation(),
			//'artikel' => $this->artikelModel->getArtikel()
		];
		
			echo view('header_v',$title);
			echo view('article/create_v', $data);
			echo view('footer_v');
		
	}
	public function save()
	{
		//  dd($this->request->getVar());
		if(!$this->validate([
			'judul' => ['rules'=>'required',
						'errors'=>[ 'required'=> 'Judul Harus diisi']
					   ],
			'isi' => ['rules'=>'required',
						'errors'=>[ 'required'=> 'Isi Artikel Harus diisi']
			   			],
			'gambar' =>['rules'=>'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
							   'errors'=>[ 'uploaded'	=> 'Artikel harus diberi gambar',
											'is_image'	=> 'File harus berupa gambar',
											'mime_in'	=> 'File berformat jpg/jpeg/png']
							 ],


		]))
		{
			return redirect()->to(base_url('/article/create'))->withInput();
		}
		$fileArtikel = $this->request->getFile('gambar');
		$namaArtikel = $fileArtikel->getRandomName();
		$fileArtikel->move('img/article/', $namaArtikel);

		$this->artikelModel->save([
			'id_user' 		=> $this->request->getVar('id_user'),
			'judul' 		=> $this->request->getVar('judul'),
			
			
			'isi' 		=> $this->request->getVar('isi'),
			'gambar' => $namaArtikel
			
						
		]);
		
		return redirect()->to(base_url('/article'));
	}
	public function delete($id_artikel)
	{
		$this->artikelModel->delete($id_artikel);
		session()->setFlashdata('pesan', 'Resep Berhasil Dihapus.');
		return redirect()->to(base_url('/article'));
	}

	//--------------------------------------------------------------------

}
