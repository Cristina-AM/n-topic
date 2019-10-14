<?php
	include_once  '../config.php';

	class Db{
		private static $_instance = null;
		private $_query,
		$_error = false,
		$_results,
		$_count = 0,
		$_dbName= 'nTopic_db',
		$_dbPassword = '',
		$_dbUser ='root',
		$_dbHost ='localhost';
		public $pdo;
		// Connects to the database

		public function __construct(){ 
			 try{
				  $this->pdo = new PDO('mysql:host='.$this->_dbHost.';dbname='.$this->_dbName, $this->_dbUser,$this->_dbPassword);
				}catch(PDOException $e){
				  die($e->getMessage());
			 }
		}

		// Checks if exists an instance of the database, if not - it creates a new one, if yes - it returns that instance instead
		public static function getInstance(){
			 if(!isset(self::$_instance)){
				  self::$_instance = new DB();
			 }
			 return self::$_instance;
		}
		// Checks the username availability
		public function checkUsername($user,$available= false){
			$db= DB::getInstance();
			$sql = "SELECT * FROM users WHERE username = :username";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['username'=>$user]);
			$usernameTaken = $stmt->fetch(PDO::FETCH_ASSOC);
			if($usernameTaken){
				$available= false;
				echo "Sorry, this username is already taken.";
			}else {
				$available = true;
			}
			return $available;
		}
		// Registering a user
		public function newUser($user,$pass){
			$db= DB::getInstance();
			$sql = "INSERT INTO users (username, pass)  VALUES (:username, :pass)";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['username'=>$user, 'pass' => $pass]);
		}
		// Log in the user
		public function logUser($user,$pass){
			$db= DB::getInstance();
			$sql = "SELECT * FROM users WHERE username = :username && pass = :pass";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['username'=>$user, 'pass' => $pass]);
			$loggedUser = $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['user'] = $loggedUser['username'];	
			$_SESSION['userId'] = $loggedUser['Id']; 
		}
		// Checking if the user is an admin
		public function checkAdmin (){
			
		}
		// Displaying the forum's topics
		public function displayTopics(){
			$db=DB::getInstance();
			$sql = "SELECT * FROM topics";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute();
	?>
			<div class="row">
				<div class="topics col-12">
					<h1>Choose a topic</h1>
				<?php
					while ($topic = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo "<div class='col-5'><a href='forum.php?cat=".$topic['Id_topic']."'>". $topic['topic_name'] ."</a></div>";
						$this->topicId=$topic['Id_topic'];
					}
				?>
				</div>
			</div>
	
	<?php
		}
		// Creates a new post for a topic
		public function createPost($topicId){
			if(isset($_POST['publish'])){
				$topicId=$_POST['topicId'];
				$db=DB::getInstance();
				$postTitle = $_POST['postTitle'];
				$postContent = $_POST['postContent'];
				$authorId=$_SESSION['userId'];
				$sqlPost = "INSERT INTO posts (post_title, post_content, post_author, topic)
					VALUES (:post_title, :post_content, :post_author, :topic)";
				$stmt1=$db->pdo->prepare($sqlPost);		
				$stmt1->execute(['post_title' => $postTitle, 'post_content' => $postContent, 'post_author' => $authorId, 'topic'=>$topicId]);
				
				if($stmt1){
					header('Location: forum.php?cat='.$topicId);
					echo "Post created";
				} else {
					echo "Publish failed";
				}
			}
		}
		// Displays the posts already created by the users
		public function displayPosts($catId){
			$db=DB::getInstance();
			if(isset($_GET['cat'])){
				$catId=$_GET['cat'];
				$sqlPosts = "SELECT * FROM posts WHERE topic = :topic";
				$stmt=$db->pdo->prepare($sqlPosts);	
				$stmt->execute(['topic' => $catId]);
				$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

				foreach ($posts as $post){
					$authorId=$post['post_author'];
					$sqlAuthor="SELECT * FROM users WHERE Id = :Id";
					$stmt=$db->pdo->prepare($sqlAuthor);	
					$stmt->execute(['Id' =>$authorId]);
					$authorName = $stmt->fetch(PDO::FETCH_ASSOC);
					echo "<div class='post'><a href='posts.php?post=".$post['Id_post']."'>".$post['post_title']." by ". $authorName['username']."</a></div>";

				}	
				if(!$posts){
					echo "<div class='post'>No posts to display. Go ahead and create some. </div>";
				}
			} else {
				header('Location: forum.php');
			}
		}
		// Displays the content of the post
		public function renderPost($postId){
			if(isset($_GET['post'])){
				$db=DB::getInstance();
				$postId= $_GET['post'];
				$sql = "SELECT * FROM posts WHERE Id_post = :Id_post";
				$stmt=$db->pdo->prepare($sql);	
				$stmt->execute(['Id_post' => $postId]);
				$postData = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
					<div id="titlePost">
						<?php echo $postData['post_title']; ?>
					</div>
					<div id="postContent">
						<?php echo $postData['post_content'];?>
					</div>
				<?php
				}
			}
		
			public function displayProfile(){
				$db=DB::getInstance();
				if(isset($_SESSION['userId'])){
					$userId=$_SESSION['userId'];
					$sqlPosts = "SELECT * FROM users WHERE Id = :Id";
					$stmt=$db->pdo->prepare($sqlPosts);	
					$stmt->execute(['Id' => $userId]);
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
					?>
					<div class="col-10">
						<div><?php echo $user['username']?>'s profile</div>
						<div>User Id: <?php echo $user['Id'];?></div>
						<div>Username: <?php echo $user['username'];?></div>
						<div>Registration date: <?php echo $user['joined'];?></div>
						<div>Role: <?php echo $user['user_role'];?></div>
					</div>	
					<?php
				}
			}
		}

?>   
   