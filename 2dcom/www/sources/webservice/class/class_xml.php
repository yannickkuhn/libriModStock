<?php
class readxml
{
	var $user 	  = "";
	var $password = "";
	var $data 	  = array();
	var $erreur	  = false;
	
	function readxml()
	{
		if(!defined("_XML_BALISE")) define("_XML_BALISE", "balise_xml");
		if(!defined("_XML_VALEUR")) define("_XML_VALEUR", "valeur_xml");
		if(!defined("_XML_ATTRS")) define("_XML_ATTRS", "attributs_xml");
		if(!defined("_XML_TEXTE")) define("_XML_TEXTE", "texte_xml");
	}
	
	function read( $data )
	{
		## Création d'un tableau avec les résultats
		$RETURN = $this->xmlEnTableau( $data, "assoc=1+" );
		
		## Vérification de la syntaxe XML
		if($this->erreur)
		{
			$this->erreur = "XML: Erreur de syntaxe, impossible de lire les données : ".$data;
			return false;
		}
		
		## Mise en forme des résultats
		if(!empty($RETURN["AUTH"]["USER"])) $this->user = $RETURN["AUTH"]["USER"];
		if(!empty($RETURN["AUTH"]["PASSWORD"])) $this->password = $RETURN["AUTH"]["PASSWORD"];
		if(!empty($RETURN["DATA"])) $this->data = $RETURN["DATA"];
		
		## Encodage des caractères (urlencode)
		$this->data = $this->format_array($this->data);
		
		return true;
	}
	
	function format_array( $data )
	{
		while(list($key, $val) = each($data))
		{
			if(is_array($val)) $data[$key] = $this->format_array($val);
			else $data[$key] = addslashes(html_entity_decode(trim($val)));
		}
		return $data;
	}
	
	function xmlEnTableau($data, $options = null)
	{
		$texte = $this->xmlEnTexte($data);
		
		if($texte)
		{
			list($xml_struct, $xml_index) = $this->lireXML($texte);
			
			if($this->controleXmlOK($xml_struct, $xml_index))
			{
				return $this->xmlStructEnTableau($xml_struct, $options);
			}
			else
			{
				$this->erreur = true;
				return NULL;
			}
		}
		else
		{
			return NULL;
		}
	}
		
	function xmlEnTexte($data)
	{
		$texte = $data;
		
		if(!empty($texte))
		{
			// Suppression des espaces et retour chariot 
			$texte = str_replace(chr(10), "", $texte); 
			$texte = str_replace(chr(12), "", $texte); 
			$texte = str_replace(chr(13), "", $texte); 
			$texte = str_replace(chr(9), "", $texte);  
		}
		
		return $texte;
	}
	
	function lireXML($texte)
	{
		## Lit le code XML et le convertit en 2 tableaux $valeurs et $index (Encodage UTF-8)
		$p = xml_parser_create("UTF-8");
		xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, false);
		if(!xml_parse_into_struct($p, $texte, $valeurs, $index)) $this->erreur = true;
		xml_parser_free($p);
		
		return array($valeurs, $index);
	}
	
	function controleXmlOK($valeurs, $index)
	{
		foreach($index as $balises)
		{
			$ouvert = 0;
			
			foreach($balises as $balise)
			{
				$type = $valeurs[$balise]["type"];
				
				if($type == "open")
				{
					$ouvert++;
				}
				elseif($type == "close")
				{
					$ouvert--;
				}
			}
			
			if($ouvert == 0)
			{
				return true;
			}
		}
		
		return false;
	}
	
	function xmlStructEnTableau($xml_struct, $options = null)
	{
		$PILE = new PileXml();
		
		if($options)
		{
			list($balises, $niveaux_assoc) = $this->lireOptions($options); 
		}
		else
		{
			$balises = null;
			$niveaux_assoc = null;
		}
		$tableau = array();
		$nom_tab = "\$tableau";
		$j = array();
		
		for($i = 0; $i < sizeof($xml_struct); $i++)
		{
			if($i == 0)
			{
				$PILE->empiler($nom_tab, $xml_struct[0]["tag"]);
				
				## NOMBRE DE RESULTATS POUR LA RECHERCHE ##
				$count_tag = "";
				$count_value = "";
				if(!empty($xml_struct[0]["tag"])) $count_tag = $xml_struct[0]["tag"];
				if(!empty($xml_struct[0]["attributes"]["COUNT"])) $count_value = $xml_struct[0]["attributes"]["COUNT"];
				
				/*if(in_array($count_tag, $this->tagnb))
				{
					$this->nbresult = $count_value;
				}*/
			}
			elseif($PILE->estVide())
			{
				break;
			}
			else
			{
				$xml_elt = $xml_struct[$i];
				
				switch($xml_elt["type"])
				{
					case "open":
						$nom_tab = $PILE->sommetValeur();
						
						if($niveaux_assoc && in_array($xml_elt["level"] - 1, $niveaux_assoc)) 
						{
							## BALISES IDENTIQUES GROUPTITRE
							/*if(in_array($xml_elt["tag"], array("GROUPTITRE")))
							{
								if(!isset($i[$j][$xml_elt["tag"]]))
								{
									$i[$j][$xml_elt["tag"]] = 0;
								}
								
								$nom_tab .= "[\"".$xml_elt["tag"]."\"][".$i[$j][$xml_elt["tag"]]."]";
								$j[$xml_elt["tag"]]++;
							}
							else
							{
								$nom_tab .= "[\"".$xml_elt["tag"]."\"]";
							}*/
							
							$nom_tab .= "[\"".$xml_elt["tag"]."\"]";
						}
						else
						{
							$nom_tab .= "[".$PILE->incrementerFils()."]";
						}
						
						if(isset($xml_elt["attributes"]))
						{
							eval($nom_tab."[\""._XML_ATTRS."\"]="."\$xml_elt[\"attributes\"]".";");
							
							if($balises)
							{
								eval($nom_tab."[\""._XML_BALISE."\"]=\"".$xml_elt["tag"]."\";");
							}
							
							$nom_tab .= "[\"" . _XML_VALEUR . "\"]";
						}
						elseif($balises)
						{
							eval($nom_tab."[\""._XML_BALISE."\"]=\"".$xml_elt["tag"]."\";");
							
							$nom_tab .= "[\""._XML_VALEUR."\"]";
						}
						
						eval($nom_tab."=array();");
						
						$PILE->empiler($nom_tab, $xml_elt["tag"]);
						
						if(isset($xml_elt["value"]))
						{							
							$ligne = array(0 => array("tag" => _XML_TEXTE, "type" => "cdata", "level" => ($xml_elt["level"] + 1), "value" => $xml_elt["value"]));
							array_splice($xml_struct, ($i + 1), 0, $ligne);
						}
					break;
					
					case "complete":
					
					case "cdata":
						$nom_tab = $PILE->sommetValeur();
						$xml_tag = isset($xml_elt["tag"]) ? $xml_elt["tag"] : _XML_TEXTE;
						
						if($niveaux_assoc && in_array($xml_elt["level"] - 1, $niveaux_assoc))
						{
							## BALISES IDENTIQUES
							/*if(in_array($xml_elt["tag"], $this->multitag))
							{
								if(!isset($j[$nom_tab][$xml_elt["tag"]]))
								{
									$j[$nom_tab][$xml_elt["tag"]] = 0;
								}
								
								$fils = $nom_tab."[\"$xml_tag\"][".$j[$nom_tab][$xml_elt["tag"]]."]";
								$j[$nom_tab][$xml_elt["tag"]]++;
							}
							else
							{*/
								$fils = $nom_tab."[\"$xml_tag\"]";
							//}
						}
						else
						{
							$fils = $nom_tab."[".$PILE->incrementerFils()."]";
						}
						
						if(isset($xml_elt["attributes"]))
						{
							eval($fils."[\""._XML_ATTRS."\"]="."\$xml_elt[\"attributes\"]".";");
							
							if($balises)
							{
								eval($fils."[\""._XML_BALISE."\"]=\"$xml_tag\";");
							}
							
							$fils .= "[\""._XML_VALEUR."\"]";
						}
						elseif($balises)
						{
							eval($fils."[\""._XML_BALISE."\"]=\"$xml_tag\";");
							$fils .= "[\""._XML_VALEUR."\"]";
						}
						
						if(isset($xml_elt["value"]))
						{
							$xml_elt["value"] = utf8_decode($xml_elt["value"]);
							
							## Corrige le bug des EAN commencant par 0
							/*if(is_numeric($xml_elt["value"]))
							{
								eval($fils."=".$xml_elt["value"].";");
							}
							else
							{*/
								eval($fils."=\"".htmlspecialchars($xml_elt["value"],ENT_QUOTES|ENT_SUBSTITUTE)."\";");
							//}
						}
					break;
					case "close":
						if($PILE->sommetComparerBalise($xml_elt["tag"]))
						{
							$nom_tab = $PILE->depiler();
						}
						else
						{
							break;
						}
				}
			}
		}
		
		if($PILE->estVide() && ($i == sizeof($xml_struct)))
		{
			return $tableau;
		}
		else
		{
			return null;
		}
	}
	
	function lireOptions($options)
	{
		if($options == "balises")
		{
			return array(true, null);
		}
		elseif(strpos($options, "assoc") !== false)
		{
				$pos = strpos($options, "=");
				$niveaux = explode("+", substr($options, $pos+1));
				
				if($niveaux[sizeof($niveaux)-1] == "")
				{
					$dernier = $niveaux[sizeof($niveaux)-2];
					$niveaux[sizeof($niveaux)-1] = $dernier+1;
					
					for($i = ($dernier + 2); $i <= 10; $i++)
					{
						$niveaux[] = $i;
					}
				}
				
				return array(null, $niveaux);
		}
		else
		{
			return array(null, null);
		}
	}
}

class PileXml
{
	var $elements;
	
	function PileXml()
	{
		$this->elements = array();
	}
	
	function empiler($valeur, $balise)
	{
		$OBJ = new ElementPile($valeur, $balise);
		array_push($this->elements, $OBJ);
	}
	
	function depiler()
	{
		if($this->estVide())
		{
			return null;
		}
		else
		{
			$obj = array_pop($this->elements);
			return $obj;
		}
	}
	
	function sommetValeur()
	{
		return $this->elements[$this->sommet()]->retournerValeur();
	}
	
	function incrementerFils()
	{
		if($this->estVide())
		{
			return null;
		}
		else
		{
			return $this->elements[$this->sommet()]->incrementerFils();
		}
	}
	
	function sommetComparerBalise($nom_tag)
	{
		return $this->elements[$this->sommet()]->comparerBalise($nom_tag);
	}
	
	function estVide()
	{
		if(sizeof($this->elements))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function sommet()
	{
		return (sizeof($this->elements) - 1);
	}
}

class ElementPile
{
	var $valeur;
	var $balise;
	var $nb_fils;
	
	function ElementPile($valeur, $balise)
	{
		$this->valeur = $valeur;
		$this->balise = $balise;
		$this->nb_fils = 0;
	}
	
	function incrementerFils()
	{
		$fils = $this->nb_fils++;
		return $fils;
	}
	
	function retournerValeur()
	{
		return $this->valeur;
	}
	
	function comparerBalise($balise)
	{
		return ($this->balise == $balise);
	}
}
?>