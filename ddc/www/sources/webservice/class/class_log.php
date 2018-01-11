<?php

/// CLASS LOG ///
class log
{
	private $file = "./sources/webservice/data/log.txt";
	private $ftp;
	
	public function open() {
		$this->fp = fopen($this->file,"a+") or die("probleme d'ouverture du fichier de log ! ".$this->file);
		
	}
	public function close() {
		fclose($this->fp);
	}

	public function writeLog($text, $classFrom = "Version", $severity = "INFO") {

		$date["date"] = date("Y-m-d");
		$date["heure"] = date("H:i:s");
		
		$this->open();
		fwrite($this->fp, "[LOG - ".$severity." - ".$classFrom."] - le ".$date["date"]." à ".$date["heure"]." - ".$text."\n");
		$this->close();
	}

	public function test() {
		$this->open();
		fwrite($this->fp, "Bravo, vous avez créer un fichier de logs depuis le webservice !\n");
		$this->close();
	}
}
?>
