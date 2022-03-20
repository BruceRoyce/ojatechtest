<?php
	header('Content-type: application/json');
	require_once '__CLASSES__.php';
	session_start();
	require_once '..\classes\User.php';
	
	
	use classes\User;
	use classes\LocalStorage;
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json, true);
	$data = userProcessor ($obj["operation"], $obj);
	echo (json_encode($data));
	function userProcessor (string $operation, array $postData) {
		// initilizing the ajax response
		$successState = false;
		$user = new User();
		switch ($operation) {
			case "add_user":
				$newUser_id = $user->addUser($postData);
				$successState = $user->getUser($newUser_id);
				break;
			case "get_user":
				$successState = $user->getUser($postData);
				break;
			
			case "find_user":
				$successState = $user->findUser($postData);
				break;
			
			case "delete_user":
				if ($user->deleteUser($postData)) $successState = $user->getUser("all");
				break;
			
			case "delete_all":
				$successState = (new LocalStorage())->drop(CONFIG::USERS);
				break;
		}
		
		return $successState;
	}
?>