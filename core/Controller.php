<?php
session_start();


/**
 * @access public
 * @copyright (c) 2018, Manejo Tenico de la Informacion
 * @version 1.0 - Clase core de los controladores
 *
 * @author v1.0 Jorge Correa <jorge.correa@tomsgreg.com>
 */
 class Controller 
 {
 	
 	function __construct()
 	{

 		$this->view = new View();
 		
 	}

 	function LoadModel($model)
 	{
 		$url='models/'.$model.'model.php';

 		if (isset($url))
 		{
 			require $url;

 			$ModelName=$model.'Model';

 			$this->model= new $ModelName();
 		}
 	}

 	public function ValidarUrl($listaurl=Array(),$subcadena)
	{
		$i=0;
		if(count($listaurl)!=0)
		{
			foreach($listaurl as $llave=>$valor)
			{
				$urlactual=str_replace($subcadena, "", $_SERVER['PHP_SELF']);
				if ($listaurl[$llave]['url']==$urlactual)
				{
					$i++;
				}				
			}			
		}
		if ($i==0)
		{
			header("location:../php/submit/ingreso/salir.php");

		}		
	}

	public function ValidarSesion() {
	
        if (!isset($_SESSION['JUEGO']) || !isset($_SESSION['JUEGO']['DATOS'])){  
            echo '<script type="text/javascript">alert("Su sesi\xf3n ha expirado.\nInicie nuevamente.");window.location="../ingreso/index";</script>';
            session_destroy();
            exit;
        }
     
    }

 	
 }
?>