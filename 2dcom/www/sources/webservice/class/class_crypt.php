<?php
class datacrypt
{	
	function cryptdata( $data, $key )
	{
		$cpt 	= 0;
		$return = "";
		
		## Key en BASE64
		$key = base64_encode($key);
		
		## Parcours des donn�es � crypter
		for($i = 0; $i < strlen($data); $i++)
		{
			## Extraction caract�res
			$char1 = ord(substr($data, $i, ($i + 1)));
			$char2 = ord(substr($key, $cpt, ($cpt + 1)));
			
			## Cryptage
			$return .= $this->ouex( $char1, $char2 ).";";
			
			if($cpt == (strlen($key) -1)) $cpt = 0;
			else $cpt++;
		}
		
		## Suppression dernier ;
		$return = substr($return, 0, -1);
		
		## Encodage en BASE64
		$return = base64_encode($return);
		
		return $return;
	}
	
	function uncryptdata( $data, $key )
	{
		$cpt 	= 0;
		$return = "";
		
		## Key en BASE64
		$key = base64_encode($key);
		## Donn�es en MIME
		$data = base64_decode($data);
		## Cr�ation tableau avec chaque caract�res
		$expl = explode(";", $data);
		
		## Parcours des donn�es � crypter
		for($i = 0; $i < count($expl); $i++)
		{
			## Extraction caract�res
			$char1 = $expl[$i];
			$char2 = ord(substr($key, $cpt, ($cpt + 1)));
			
			## Decryptage
			$return .= chr($this->ouex( $char1, $char2 ));
			
			if($cpt == (strlen($key) -1)) $cpt = 0;
			else $cpt++;
		}
		
		return $return;
	}
	
	// Fonction OU EXCLUSIF //
	function ouex( $data1, $data2 )
	{
		return ((int)$data1 ^ (int)$data2);
	}
	////
}
?>