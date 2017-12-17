<?php
class BackupModel extends CI_Model
{
	public function __CONSTRUCT()
	{
		$this->load->dbutil();
		$this->load->helper('file');
	}

	public function Listar()
	{
		$path = FCPATH . 'backups';

		if(!is_dir($path))
		{
			$this->responsemodel->setResponse(false, "No existe la carpeta backups en la ráiz del proyecto, agregue una carpeta backups en " . FCPATH);
		}
		else
		{
			$copias = array();
			foreach(scandir($path) as $k => $p)
			{
				if($p != '..' && $p != '.')
				{
					$fecha = explode('-', $p);

					$copias[] = (object) array(
						'Archivo' => $p,
						'Fecha'   => DateFormat(str_replace('.sql', '', $fecha[1]), 5)
					);
				}
			}

			$this->responsemodel->result = (object)$copias;
			$this->responsemodel->setResponse(true);
		}

		return $this->responsemodel;
	}

	public function Respaldar()
	{
		$this->benchmark->mark('code_start');
		$backup = $this->dbutil->backup(array(
			'format' => 'txt'
		));

		write_file('backups/backup-' . date('YmdHis') . '.sql', $backup);
		$this->benchmark->mark('code_end');

		$this->responsemodel->setResponse(true, $this->benchmark->elapsed_time('code_start', 'code_end'));
		return $this->responsemodel;
	}
}