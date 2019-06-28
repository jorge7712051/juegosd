<?php

require __DIR__.'/../vendor/autoload.php';

/**
 * 
 */
class Rae 
{	
	
	
	public function buscarpalabra($palabra)
	{
		$debug = true;
		 $rae = new \RAE\RAE(true);
		$search = $rae->searchWord($palabra);		
		if (count($search->getRes())>0)
		{	/*
			$wordId = $search->getRes()[0]->getId();
			$result = $rae->fetchWord($wordId);
			$defintions = $result->getDefinitions();
			$i = 1;
			foreach ($defintions as $definition) {
    		echo $i.'. Tipo: '.$definition->getType()."\n";
    		echo '   Definición: '.$definition->getDefinition()."\n\n";
    		$i++;
			}*/
			$mensaje='La palabra existe';
		}
		else{
			$mensaje='La palabra no  existe';
		}

		
		return $mensaje;
	}


}




