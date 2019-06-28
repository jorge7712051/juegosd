<?php 

/**
 * 
 */
set_time_limit(0); 
require_once 'GetDefinition.php';

class JugarModel extends Model
{
	public $NombreTabla='partida';

	function __construct($DB=JUEGO)
	{
        
		parent::__construct($DB);
	}


    public function Generarletras($length = 20) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZAAEEIIOOUU';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)]."-";
    }
    $randomString=trim($randomString,"-");
    return $randomString;
    }

    public function Validar($post)
    {
        $rae = new Rae();
        $respuesta=$rae->buscarpalabra($post['valor']);
        $fecha= date("Y-m-d H:i:s");
        $this->insertar(0,
            trim($post['tabla']),
            $fecha,
            $post['letras'],
            $_SESSION['JUEGO']['DATOS'][0]['idusuario']

        );

        //$respuesta=get_headers();
        $Datos = array( 0 => array( 'mensaje' => $respuesta));
        return $respuesta;
    }

    public function iniciar($post)
    {   
        $fecha= date("Y-m-d H:i:s");
        $letras=$this->Generarletras();
        $this->insertar(
            0,
            trim($post['tabla']),
            $fecha,
            $letras,
            $_SESSION['JUEGO']['DATOS'][0]['idusuario']);
    $Configuracion = array(
            'id_jugador' => $_SESSION['JUEGO']['DATOS'][0]['idusuario'],
            'orden' =>1,                     
    );
    $resultado =$this->Consulta($Configuracion);
    return $resultado;

    }

    public function Actualizar($post)
    {
        //$tabla='';
        $ahora = date("Y-n-j H:i:s"); 
        $tiempo_transcurrido=0;
        $fecha_ac = isset($_POST['timestamp']) ? $_POST['timestamp']:0;
        $ConsultaSQL  = "SELECT fecha,tabla FROM partida ORDER BY fecha DESC LIMIT 1";
        $ro=$this->pasarelaSql($ConsultaSQL,'assoc');
        $fecha_bd =strtotime($ro[0]['fecha']);
        $tabla=$ro[0]['tabla'];
       /* while( $fecha_bd <= $fecha_ac && $tiempo_transcurrido <3000)
        {   */
            $ConsultaSQL  = "SELECT fecha,tabla FROM partida ORDER BY fecha DESC LIMIT 1";
            $ro=$this->pasarelaSql($ConsultaSQL,'assoc');
        
            //usleep(100000);//anteriormente 10000
            //clearstatcache();
            $fecha_bd  = strtotime($ro[0]['fecha']);
            $tabla=$ro[0]['tabla'];
           // $tiempo_transcurrido +=1;
            //var_dump( $tiempo_transcurrido);
            //$tiempo=date("Y-n-j H:i:s");
            //$tiempo_transcurrido = strtotime($ahora)-strtotime($tiempo); 

        //}
        $query       = "SELECT * FROM partida  where id_jugador !=". $_SESSION['JUEGO']['DATOS'][0]['idusuario']." ORDER BY fecha DESC LIMIT 1";
        //print_r($query);
        $query2       = "SELECT * FROM partida  where id_jugador =". $_SESSION['JUEGO']['DATOS'][0]['idusuario']." ORDER BY fecha DESC LIMIT 1";
        $Consulta1 = $this->pasarelaSql($query,'assoc');
        $Consulta2 = $this->pasarelaSql($query2,'assoc');
        if (count($Consulta1)>0) 
        {
           $Datos = array( 0 => array( 'puntosoponente' =>$Consulta1[0]['puntos'] ,
                                    'letrasoponente' =>$Consulta1[0]['letras'],
                                    'puntos' =>$Consulta2[0]['puntos'],
                                    'letras' =>$Consulta2[0]['letras'],
                                    'tabla' => $tabla,
                                    'fecha' =>$fecha_bd )

            );
        }
        else
        {
            $Datos = array( 0 => array( 'puntosoponente' =>'sin jugador' ,
                                    'letrasoponente' =>'sin jugador',
                                    'puntos' =>$Consulta2[0]['puntos'],
                                    'letras' =>$Consulta2[0]['letras'],
                                    'tabla' => $tabla,
                                    'fecha' =>$fecha_bd )

                );
        }
        
        return $Datos;


    }

    public function insertar($puntos,$tabla,$fecha,$letras,$id_jugador)
    {
        
        $sql="INSERT INTO partida (puntos, tabla, letras,fecha,id_jugador) 
        VALUES 
        ('".$puntos."','".$tabla."','".$letras."','".$fecha."',".$id_jugador.")";
        //echo $sql;
        $this->inserdatos($sql);
    }

    public function Consulta($ConfiguracionEnviada = array())
  {  
      $ConfiguracionDefecto = array(
            'SELECT' => array(),
            'id_jugador' => '',           
            'orden' => ''            
        );
    $ConfiguracionExtendida = array_merge($ConfiguracionDefecto, $ConfiguracionEnviada);

    if($ConfiguracionExtendida['id_jugador'] != ''){
            $FiltroSQL[] = $this->crearFiltro('p', 'id_jugador', $ConfiguracionExtendida['id_jugador']);
        }
       
         if($ConfiguracionExtendida['orden'] != ''){
            $OrdenarPor = ' p.fecha DESC LIMIT 1';
        }
        
      
        $ConsultaSQL = "            
        SELECT
        u.nombreusuario as nombreusuario ,
        p.tabla as tabla ,  
        p.letras as letras , 
        p.puntos as puntos,
        p.id_partida 
            
        FROM
            partida p
        inner join usuarios u on u.id_usuario =p.id_jugador
         "                 
        .((count($FiltroSQL) > 0) ? ' WHERE '.implode(" AND ",$FiltroSQL) : '')."
        ".((isset($OrdenarPor)) ? "ORDER BY ".$OrdenarPor : '')."                     
        ".((isset($Limite)) ? $Limite : '')."
        "; 

        //var_dump($ConsultaSQL);
        $ResultadoSQL = $this->pasarelaSql($ConsultaSQL,'assoc');
        return $ResultadoSQL;  
  }




	
}

 ?>