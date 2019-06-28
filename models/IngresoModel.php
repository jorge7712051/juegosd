<?php

/**
 * 
 */
class IngresoModel extends Model
{
	
	public $NombreTabla="usuarios";
	public $TablaPerfiles="mu_perfiloptions";
	public $Menu=array();
	public $DatosUsuario=array();
  public $Error=array();
  public $Usuario;
  public $Password;
  public $RepetirPassword;

	function __construct($DB=JUEGO)
	{
		parent::__construct($DB);
	}

  public function validate($esenario)
  {
     $Errores= array();
    switch ($esenario) {
      case 'login':
        $requeridos= array('Usuario'=>getUsuario(),'nuevopass'=>getPassword());
        $Errores = ($this->Camporequerido($requeridos)) ? true  : $this->Camporequerido($requeridos) ;
         

        break;

      case 'CambioPass':
       
        $Requeridos= array('nuevopass'=>$this->getPassword(),'repetirpass'=>$this->getRepetirPassword());
        $Temp = $this->Camporequerido($Requeridos);    
        if (is_array($Temp)) { 
             $Temp = $this->Camporequerido($Requeridos);
             $Errores = array_merge($Errores, $Temp);
        }
        unset($Temp);
        $Temp = $this->Comparacion($this->getPassword(),$this->getRepetirPassword(),'repetirpass');    
        if (is_array($Temp)) {           
           $Errores = array_merge($Errores, $Temp);
        }     
        break;
      
      default:
        # code...
        break;
    }

    if (count($Errores)>0)
    {
      $Errores['repetirpass'] = 'El campo no coincide';
      $this->setError($Errores);
      return false;
    }
    else 
    {
      return true;
    }
    
      
  }


  public function getError()
  {
    return $this->Error;
  }

  public function setError($Datos)
  {
    $this->Error=$Datos;
  }

  public function getDatosUsuario()
  {
    return $this->DatosUsuario;
  }

  public function setDatosUsuario($Datos)
  {
    $this->DatosUsuario=$Datos;
  }

  public function getMenu()
  {
    return $this->Menu;
  }

  public function setMenu($Datos)
  {
    $this->Menu=$Datos;
  }

  public function getUsuario()
  {
    return $this->Usuario;
  }

  public function setUsuario($Datos)
  {
    $this->Usuario=$Datos;
  }

  public function getPassword()
  {
    return $this->Password;
  }

  public function setPassword($Datos)
  {
    $this->Password=$Datos;
  }
    public function getRepetirPassword()
  {
    return $this->RepetirPassword;
  }

  public function setRepetirPassword($Datos)
  {
    $this->RepetirPassword=$Datos;
  }

	public function ValidarLogin ($post)
	{
		$Mensaje='';
		$Configuracion = array(
            'nombreusuario' => strtoupper($post['usuario']),
            'password' => $post['password'],                     
    );
    $usuario =$this->Consulta($Configuracion);
    $this->setDatosUsuario($usuario);    
       
    if(count($usuario)>0)
        {
       		   $this->setMenu($Menu);
       			$Url=$this->cleanInput($post['cargaUrl']);
       			$this->Crearsesion($Url);      			
       			header("Location:".$Url."/juegosd/jugar/index");
       	} 
   
		return $Mensaje='<div class="alert alert-danger">Datos Incorrcetos</div>';

	}

	private function Crearsesion($Url='')
	{
    
		$_SESSION['JUEGO'] = array(
            'DATOS' => $this->getDatosUsuario(),             
            'URL'=>$Url,       
            'TIEMPO_LIMITE' => 3000,
            'tiempo' => date("Y-n-j H:i:s")
        );

	}


  public function Consulta($ConfiguracionEnviada = array())
  {  
      $ConfiguracionDefecto = array(
            'SELECT' => array(),
            'password' => '',
            'nombreusuario' =>'',          
            'orden' => array()            
        );
    $ConfiguracionExtendida = array_merge($ConfiguracionDefecto, $ConfiguracionEnviada);

    if($ConfiguracionExtendida['nombreusuario'] != ''){
            $FiltroSQL[] = $this->crearFiltro('u', 'nombreusuario', $ConfiguracionExtendida['nombreusuario']);
  }
        if($ConfiguracionExtendida['password'] != ''){
            $FiltroSQL[] = $this->crearFiltro('u', 'password', $ConfiguracionExtendida['password']);
        }
        
      
        $ConsultaSQL = "            
        SELECT
          u.id_usuario as idusuario,
          u.nombreusuario as nombre 
         
        FROM
            ".$this->NombreTabla." u 
         "                 
        .((count($FiltroSQL) > 0) ? ' WHERE '.implode(" AND ",$FiltroSQL) : '')."
        ".((isset($OrdenarPor)) ? "ORDER BY ".$OrdenarPor : '')."                     
        ".((isset($Limite)) ? $Limite : '')."
        "; 

        //  var_dump($ConsultaSQL);
        $ResultadoSQL = $this->pasarelaSql($ConsultaSQL,'assoc');
        return $ResultadoSQL;  
  }


	

  


   


}

?>

