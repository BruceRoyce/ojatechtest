<?php
	namespace classes;
	class LocalStorage {
		
		public function readAll (string $storage) : array | bool {
			return isset($_SESSION[$storage]) ? $_SESSION[$storage] : false;
		}
		
		public function insert(string $storage, mixed $data) : bool {
			$_SESSION[$storage] = $data;
			return isset($_SESSION[$storage]);
		}
		
		public function delete (string $storage, string $key) : bool {
			$all = $this->readAll($storage);
			if (isset($all[$key])) {
				unset($all[$key]);
				$this->insert($storage, $all);
				return true;
			}
			return false;
		}
		
		public function update (string $storage, string $key, array $updated) : bool {
			// todo
		}
		
		public function drop (string $storage) : bool {
			unset($_SESSION[$storage]);
			return !isset($_SESSION[$storage]);
		}
		
		
		
	}