<?php 

/**
 * 
 */
class Jugar extends Controller
{
	
	public $Mensaje='';

	function __construct()
	{
		parent::__construct();
		$this->view->mensaje="";
		$this->ValidarSesion();	
		
	}



	public function Index ()
	{
		//$this->model->insert();
		
		$RemplazaAVista = array(
    		'MENSAJE' => $this->Mensaje,    		
    	);

		if (!empty($_POST) && isset($_POST))
		
		{
			$Mensaje=$this->model->ValidarLogin($_POST);
			$RemplazaAVista['MENSAJE'] = $Mensaje;
    		$contenido=$this->view->set_contenido_vista($RemplazaAVista,'ingreso/index');
    		$Datos=array(
		   		 'CONTENT'=>$contenido,		   		
			);
		    echo $this->view->render($Datos);
			exit;
		}

		$contenido=$this->view->set_contenido_vista($RemplazaAVista,'jugar/index');
    	$Datos=array(
		   		'CONTENT'=>$contenido,
		   		'TITULO'=>'JUEGO SD',
		   		'JAVASCRIPT-FOOTER'=>array(
            	'JS-INGRESO' =>'<script type="text/javascript" src="'.constant("RUTA_RAIZ").'public/js/juego/juego.js"></script>'          	
            ),
		);
		echo $this->view->render($Datos);	
	}
/*
	function render()
	{
		$this->index();
	}
*/
	function Actualizar()
	{
		echo json_encode($this->model->Actualizar($_POST));
	}

	function Iniciar()
	{

		echo json_encode($this->model->Iniciar($_POST));	
	}

	function validar()
	{

		echo json_encode($this->model->Validar($_POST));	
	}
	
}

 ?>