<?php
	include_once '../config.php';
	class User{
		private $_userId = $_SESSION['userId'], $_username, $_dateJoined, $_role;
		public function getProfileInfo($user){
			$db= DB::getInstance();
			$sql = "SELECT * FROM users WHERE Id = :userId ";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['userId'=>$this->_userId]);
			if($userData= $stmt->fetch(PDO::FETCH_ASSOC)){
				$this->_userId= $userData['Id'];
				$this->_username=$userData['username'];
				$this->_dateJoined=$userData['joined'];
				$this->_role = $userData['role'];

			}
		}	
		public function displayProfileInfo($user){
			$getUserData = getProfileInfo($user);
				echo "User Id: " . $this->_userId. "<br />";
				echo "Username: " . $this->_username. "<br />";
				echo "Date joined: " . $this->_dateJoined. "<br />";
				echo "Role: " . $this->_role. "<br />";	
		}	
	}
?>