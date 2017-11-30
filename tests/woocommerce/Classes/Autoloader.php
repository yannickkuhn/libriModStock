<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 11/10/2017
    // ---------------------------------
    // CLASSE AUTOLOADER
    // ----------------------------------

	namespace Classes;

	class Autoloader {

		static function register() {
			spl_autoload_register(array(__CLASS__, 'autoload'));
		}

		static function autoload($class) {
			$class = str_replace('\\', '/', $class);
			require $class.'.php';
		}

	}