<?php
	namespace classes;
	
	require_once 'IdGenerator.php';
	require_once 'Password.php';
	require_once 'LocalStorage.php';
	require_once 'CONFIG.php';

	class User {
		public function __construct(public ?int $user_id = null) {
		}
		
		private function createUse (string $user_id, array $userData) : array {
			return [
					"id"=> $user_id,
					"email"=> $userData["email"],
					"password" => Password::hash($userData["password"]),
					"name" => $userData["name"]!=="" ? $userData["name"] : "N/A",
					"postcode" => $userData["postcode"]!=="" ? $userData["postcode"] : "N/A"
			];
		}
		
		private function getAllUsers () : array | bool {
			return (new LocalStorage())->readAll(CONFIG::USERS);
		}
		
		public function addUser (array $userData) : string | bool  {
			$user_id = IdGenerator::newId();
			$user = $this->createUse($user_id, $userData);
			$existingUsers = $this->getAllUsers();
			if (is_array($existingUsers) && count($existingUsers)>0) {
				$newUser = array_merge($existingUsers, [$user_id => $user] );
			} else {
				$newUser = [$user_id => $user];
			}
			if ((new LocalStorage())->insert(CONFIG::USERS, $newUser)) {
				return $user_id;
			}
			return false;
		}
		
		public function getUser (?string $user_id=null) : array | bool {
			$user_id = $user_id ?? $this->user_id;
			$allUsers = $this->getAllUsers();
			if ($user_id=== "all") return $allUsers;
			if (in_array($user_id, $allUsers)) {
				return $allUsers[$user_id];
			}
			return false;
		}
		
		public function findUser (string $key) {
			$allUsers = $this->getAllUsers();
			$results = [];
			if ($allUsers) {
				foreach ($allUsers as $user=>$data) {
					// 1 looking for id
					if (strtolower($user) == strtolower($key)) {
						$results[] = $allUsers[$user];
					}
					foreach ($data as $datum=>$value) {
						// 2 looking at all values
						if (strtolower($key) == strtolower($value)) {
							$results[] = array_merge($allUsers[$user], ["mark" => $datum]);
						}
					}
				}
				if (count($results)) return $results;
			}
			return false;
		}
		
		public function deleteUser(string $user_id) : bool {
			return (new LocalStorage())->delete(CONFIG::USERS, $user_id);
		}
		
	}