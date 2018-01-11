<?php
## CLASS AUTHENTIFICATION ##
class auth
{
	private $user 	   = "";
	private $password  = "";
	
	public  $error	   = "";
	public  $brutforce  = "";
	
	private $brutforce_lock		= 5;
	private $brutforce_locktime	= 10;
	
	function load( $user, $password )
	{
		global $INFO;
		
		$this->user 	= $user;
		$this->password = $password;
		
		## CONTROLE BRUT FORCE
		if(!$this->brutforce_check())
		{
			$this->authfailed( $this->brutforce );
			return;
		}
		
		## Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		## Authentification utilisateur
		$INFO->Query("SELECT 
							su_id, 
							su_password, 
							bforce_cpt 
							FROM soapusers 
							WHERE 
							su_login = \"".urlencode($this->user)."\"");
		$ROW = $INFO->Result();
		$nb = $INFO->Num();
		
		## Déconnexion MySQL
		$INFO->Close();
		
		## Pas d'authorisation (Compte inexistant)
		if($nb <= 0)
		{
			$this->authfailed( "Nom d'utilisateur incorrect (".$this->user.")" );
			return false;
		}
		
		## Pas d'authorisation (Mot de passe incorrect)
		if($ROW["su_password"] !== sha1(urlencode($this->password)))
		{
			$this->brutforce_add($ROW["su_id"]);				
			$this->authfailed( "Mot de passe incorrect incorrect [Tentative ".($ROW["bforce_cpt"] + 1)."/".$this->brutforce_lock."]" );
			return;
		}
		
		## Authorisation OK + RESET BRUT FORCE
		$this->brutforce_reset($ROW["su_id"]);
		return true;
	}
	
	function authfailed( $data )
	{
		global $INFO;
		
		$this->error = $INFO->returnxml( "-1", utf8_decode("AUTH: Authentification échouée, ".$data) );
	}
	
	## BRUT FORCE SECURITY ##
	function brutforce_check()
	{
		global $INFO;
		
		## Connexion MySQL
		$INFO->Connect();
		
		$INFO->Query("SELECT 
							bforce_cpt, 
							bforce_time, 
							bforce_locktime 
							FROM soapusers 
							WHERE 
							su_login = \"".urlencode($this->user)."\"");
		$ROW = $INFO->Result();
		
		## Déconnexion MySQL
		$INFO->Close();
		
		$unixtime 		 = date("U");
		$bforce_locktime = $ROW["bforce_time"] + ($ROW["bforce_locktime"] * 60);
		
		if($ROW["bforce_cpt"] >= $this->brutforce_lock && $unixtime < $bforce_locktime)
		{
			$this->brutforce = "vous avez saisi un mot de passe erroné à ".$ROW["bforce_cpt"]." reprises. Le service est bloqué pour une durée d'environ ".round(($bforce_locktime - $unixtime) / 60)." minute(s)";
			return false;
		}
		
		return true;
	}
	
	function brutforce_add( $data )
	{
		global $INFO;
		
		## Connexion MySQL
		$INFO->Connect();
		
		$INFO->Query("SELECT 
							bforce_cpt, 
							bforce_time, 
							bforce_locktime 
							FROM soapusers 
							WHERE 
							su_id = \"".$data."\"");
		$ROW = $INFO->Result();
		
		// Calcul du nombre de mauvais password + temps de blocage
		$bforce_cpt = (int)$ROW["bforce_cpt"] + 1;
		if($bforce_cpt < $this->brutforce_lock)
		{
			$bforce_locktime = 0;
			$bforce_time = 0;
		}
		elseif((($bforce_cpt % $this->brutforce_lock) == "0") && empty($ROW["bforce_locktime"]))
		{
			$bforce_locktime = $this->brutforce_locktime;
			$bforce_time = date("U");
		}
		elseif(($bforce_cpt % $this->brutforce_lock) == "0")
		{
			$bforce_locktime = $ROW["bforce_locktime"] * 2;
			$bforce_time = date("U");
		}
		else
		{
			$bforce_locktime = $ROW["bforce_locktime"];
			$bforce_time = $ROW["bforce_time"];
		}
		
		$INFO->Query("UPDATE 
							soapusers 
							SET 
							bforce_cpt = \"".$bforce_cpt."\", 
							bforce_time = \"".$bforce_time."\", 
							bforce_locktime = \"".$bforce_locktime."\" 
							WHERE 
							su_id = \"".$data."\"");
		
		## Déconnexion MySQL
		$INFO->Close();
	}
	
	function brutforce_reset( $data )
	{
		global $INFO;
		
		## Connexion MySQL
		$INFO->Connect();
		
		$INFO->Query("UPDATE 
							soapusers 
							SET 
							bforce_cpt = \"0\", 
							bforce_time = \"0\", 
							bforce_locktime = \"0\" 
							WHERE 
							su_id = \"".$data."\"");
		
		## Déconnexion MySQL
		$INFO->Close();
	}
	####
}
####
?>