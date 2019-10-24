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
				echo "<div class='col-12 bg-danger mx-auto'>Sorry, this username is already taken.</div>";
			}else {
				$available = true;
			}
			return $available;
		}
		// Registering a user
		// base64_encode was used to encrypt the password, since this is a localhost project only, 
		// otherwise another more powerful encryption system would have been used
		public function newUser($user,$pass){
			$db= DB::getInstance();
			$sql = "INSERT INTO users (username, pass)  VALUES (:username, :pass)";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['username'=>$user, 'pass' => base64_encode($pass)]);
		}
		// Log in the user
		public function logUser($user,$pass){
			$db= DB::getInstance();
			$enPass=base64_encode($pass);;
			$sql="SELECT * FROM users WHERE username= :username && pass = :pass";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['username'=>$user,'pass'=>$enPass]);
			$userData= $stmt->fetch(PDO::FETCH_ASSOC);
			$dbPass=$userData['pass'];
			if($userData && $dbPass===$enPass){
				$_SESSION['user'] = $user;	
				$_SESSION['userId'] = $userData['Id'];
				$_SESSION['isLogged']=true; 
				header('Location: /ntopic/main/index.php');
			}										
		}
		// Checking if the user is an admin
		public function checkAdmin (){
			$userId=$_SESSION['userId'];
			$role = 2;
			$db= DB::getInstance();
			$sql = "SELECT * FROM users WHERE Id = :Id && user_role = :user_role";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute(['Id'=>$userId, 'user_role'=>$role]);
			$admin = $stmt->fetch(PDO::FETCH_ASSOC);
			if($admin){
				return true;
			} else return false;
		}
		// The admin creates a new topic
		public function createTopic(){
			if(isset($_POST['addTopic'])){
				$db=DB::getInstance();
				$topicName = $_POST['topicName'];
				$sqlTopic = "INSERT INTO topics (topic_name) VALUES (:topic_name)";
				$stmt1=$db->pdo->prepare($sqlTopic);		
				$stmt1->execute(['topic_name' => $topicName]);
			}
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
					<h3 class="topic">Choose a topic</h3>
				<?php
					while ($topic = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo "<div class='col-xs-12 col-md-12 topics'>
									<div class='col-12'>
										<a href='/ntopic/main/forum.php?cat=".$topic['Id_topic']."'>". $topic['topic_name'] ."</a>
									</div>
								</div>";
						$this->topicId=$topic['Id_topic'];
					}
				?>
				</div>
			</div>
	
	<?php
		}

		// Displays all topics in the admin's cpanel
		public function displayAllTopics(){
			$db=DB::getInstance();
			$sql = "SELECT * FROM topics";
			$stmt=$db->pdo->prepare($sql);
			$stmt->execute();
	?>
			<div class="row">
				<form action='/ntopic/main/cpanel.php' method='POST'>
					<input type='text' placeholder='Add new topic' name='topicName'>
					<input type='submit' value='Add topic' name='addTopic'>
				</form>
				<?php
				
					while ($topic = $stmt->fetch(PDO::FETCH_ASSOC)){
						echo "<div class='topics col-xs-12 col-md-12'>
									<a href='/ntopic/main/forum.php?cat=".$topic['Id_topic']."'>". $topic['topic_name'] ."</a>
									<a href='/ntopic/main/cpanel.php?deleteTopic=".$topic['Id_topic']."'>X</a>
								</div>";
						$this->topicId=$topic['Id_topic'];
						
						$renameTopic=$this->topicId;
					}
					
				?>
			</div>
	
	<?php
		}
		// Admin deletes a topic
		public function deleteTopic(){
			if(isset($_GET['deleteTopic'])){
				$topicId=$_GET['deleteTopic'];
				$db= DB::getInstance();
				$sql = "DELETE FROM topics WHERE Id_topic = :Id_topic";
				$stmt=$db->pdo->prepare($sql);
				$stmt->execute(['Id_topic'=>$topicId]);
			}
		}
		
		// Creates a new post for a topic
		public function createPost($topicId){
			if(isset($_POST['publish'])){
				$topicId=$_GET['cat'];		
				$db=DB::getInstance();
				$postTitle = $_POST['postTitle'];
				$postContent = $_POST['postContent'];
				$authorId=$_SESSION['userId'];
				$sqlPost = "INSERT INTO posts (post_title, post_content, post_author, topic) 
								VALUES (:post_title, :post_content, :post_author, :topic)";
				$stmt1=$db->pdo->prepare($sqlPost);		
				$result=$stmt1->execute(['post_title'=>$postTitle, 'post_content'=>$postContent, 'post_author'=>$authorId, 'topic'=>$topicId]);
				if($result){
					header("Location: /ntopic/main/forum.php?cat=$topicId");
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
					echo "<div class='post'><a href='/ntopic/main/posts.php?post=".$post['Id_post']."'>".$post['post_title'].
								" by ". $authorName['username']." | ". $post['post_date'] ."</a></div>";
				}	
				if(!$posts){
					echo "<div class='post posts'>No posts to display. Go ahead and create some. </div>";
				}
			} else {
				header('Location: forum.php');
			}
		}
		// Displays all posts in the admin's cpanel
		public function displayAllPosts(){
			$db=DB::getInstance();
			$sqlPosts = "SELECT * FROM posts";
			$stmt=$db->pdo->prepare($sqlPosts);	
			$stmt->execute();
			$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($posts as $post){
				$authorId=$post['post_author'];
				$sqlAuthor="SELECT * FROM users WHERE Id = :Id";
				$stmtAuth=$db->pdo->prepare($sqlAuthor);	
				$stmtAuth->execute(['Id' =>$authorId]);
				$authorName = $stmtAuth->fetch(PDO::FETCH_ASSOC);
				echo "<div class='topics col-12'>
							<a href='/ntopic/main/posts.php?post=".$post['Id_post']."'>".$post['post_title']." by ". $authorName['username']." </a> 
							<a href='/ntopic/main/editPost.php?postId=".$post['Id_post']."'>&nbsp;<i class='fa fa-pencil'></i></a> 
							<a href='/ntopic/main/deletePost.php?postId=".$post['Id_post']."'>&nbsp;X</a>
						</div>";
					}	
			if(!$posts){
				echo "<div class='post'>No posts to display. Go ahead and create some. </div>";
			}
		}
		// Admin deletes a post
		public function deletePost(){
			if(isset($_GET['postId'])){
				$postId = $_GET['postId'];
				$db=DB::getInstance();
				$sqlPosts = "DELETE FROM posts WHERE Id_post = :Id_post";
				$stmt=$db->pdo->prepare($sqlPosts);	
				if($stmt->execute(['Id_post' => $postId])){
					header('Location: /ntopic/main/cpanel.php');
				}
			}
		}
		// Admin edits a post
		public function editPost(){
			if(isset($_GET['postId'])){
				$db=DB::getInstance();
				$postId=$_GET['postId'];
				$sqlPost = "SELECT * FROM posts WHERE Id_post = :Id_post";
				$stmt=$db->pdo->prepare($sqlPost);	
				$stmt->execute(['Id_post' => $postId]);
				$post = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
				<div class="container">
				<div class="row">
					<div class=" col-10">

						<form action="" id="publishForm" name="publishForm" method="POST">
							<input required type="text" name="postTitle" value="<?php echo $post['post_title'];?>" id="title"/>
							<textarea required name="postContent" cols="40" rows="20" ><?php echo $post['post_content'];?></textarea>
							<input required type="submit" name="republish" id="publish" value="Edit post">
						</form>
					</div>
				</div>
				<?php
				if (isset($_POST['republish'])){
					$postTitle = $_POST['postTitle'];
					$postContent= $_POST['postContent'];
					$updateSql= "UPDATE posts SET post_title = :post_title, post_content = :post_content WHERE Id_post = :Id_post";
					$statement = $db->pdo->prepare($updateSql);
					$statement->execute(['post_title'=>$postTitle,'post_content'=>$postContent,'Id_post'=>$postId]);
					if($statement){
						header('Location: /ntopic/main/cpanel.php');
					}	
				}
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
				if($postData){
					$authorId=$postData['post_author'];
					$sqlAuthor="SELECT * FROM users WHERE Id = :Id";
					$stmt=$db->pdo->prepare($sqlAuthor);	
					$stmt->execute(['Id' =>$authorId]);
					$authorName = $stmt->fetch(PDO::FETCH_ASSOC);
				}
				?>
				<div id="titlePost">
					<?php echo $postData['post_title']; ?>
				</div>
				<div class="post_author">
					<?php echo "Posted by: " .$authorName['username'] . " | " . $postData['post_date'] ?>
				</div>
				<div class="col-md-10 mx-auto" id="postContent">
					<?php echo nl2br($postData['post_content']);?>
				</div><?php
				}
			}
			// Displays user's profile
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

						<form action="/ntopic/main/profile.php" id="remove" method="POST">
							<input type="submit" name="deleteAllPosts" value="DELETE all my posts">
							<input type="submit" name="deleteAccount" value="DELETE my ACCOUNT">
						</form>
					</div>	
					<?php
				}
			}
			// Deletes all posts created by a certain user
			public function deleteUserPosts(){
				if(isset($_POST['deleteAllPosts'])){
					$userId = $_SESSION['userId'];
					$db=DB::getInstance();
					$sqlPosts = "DELETE FROM posts WHERE post_author = :post_author";
					$stmt=$db->pdo->prepare($sqlPosts);	
					if($stmt->execute(['post_author' => $userId])){
						header('Location: /ntopic/main/forum.php');
					}
				}
			}
			// Deletes a user's account
			public function deleteAccount(){
				if(isset($_POST['deleteAccount'])){
					$userId = $_SESSION['userId'];
					$db=DB::getInstance();
					$deleteUserSql = "DELETE FROM users WHERE Id = :Id";
					$stmt=$db->pdo->prepare($deleteUserSql);	
					$stmt->execute(['Id' => $userId]);
					session_destroy();
					header('Location: /ntopic/index.php');
				}
			}
		}
?>   
   