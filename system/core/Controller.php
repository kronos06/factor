<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;
	public $conf;
	public $user;
	public $license;
	public $version;
	public $menu;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
		
		// Usuario
		$this->user = $this->session->userdata('usuario');
		
		if($this->user != false)
		{
			// Configuracion
			$this->conf = $this->configuracionmodel->Obtener();			
			if($this->router->class == 'acceso' && $this->router->method != 'logout') redirect('inicio');
			
			if(!$this->input->is_ajax_request())
			{
				// Cargamos el menu
				$this->menu = $this->menumodel->Listar();
			}
		}
		else
		{
			if(!$this->input->is_ajax_request())
			{
				if($this->router->class != 'acceso') redirect('');
			}else
			{
				if($this->router->class != 'acceso') exit(json_encode(array('response' => 'login')));
			}
		}
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */