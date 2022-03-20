<?php
	
	namespace classes;
	
	class Test {

		public function test () {
			$m = new User();
			return $m->getUser("all");
		}
	}