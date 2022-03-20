<?php
	namespace classes;
	
	class IdGenerator {
		public static function newId () {
			return uniqid();
		}
	}