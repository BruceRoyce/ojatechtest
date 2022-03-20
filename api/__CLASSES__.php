<?php
	
	function loadClass($c) {
		$prefix = "classes\\";
		$suffix = ".php";
		$classFileName = basename($c).$suffix;
		if (array_search($classFileName, scandir("classes\\"))) {
			$c = $prefix.$classFileName;
			require_once $c;
		}
	}
	spl_autoload_register('loadClass');
