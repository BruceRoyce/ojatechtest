<?php
	namespace classes;
	class Password {
		public function __construct(public ?int $user_id=null, public string $password)
		{
		}
		
		public static function hash (string $password) : string {
			/* 	Password must be hashed
					but for this demo I leave them as is
					in real life the following line must be uncommented
			*/
//			return password_hash($password, PASSWORD_DEFAULT);
			return "$password (unhashed)";
		}
 	
	}
	
	