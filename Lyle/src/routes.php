<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->post('/login', function ($request, $response) {
	$input = $request->getParsedBody();
	$sth = $this->db->prepare("SELECT * FROM users WHERE email = :email AND pass = :pass");
	$sth->bindParam("email", $input['email']);
	$sth->bindParam("pass", $input['pass']);
	$sth->execute();
	if($sth->rowCount() != 0)
	{
		return $this->response->withJson(array("Successful Login",1));
	}
	else
	{
		return $this->response->withJson(array("Incorrect credentials; please try again",0));
	}
});

$app->group('/accounts', function () use ($app) {
    $app->get('/users', function ($request, $response, $args) {	
	//debugToConsole('REQUEST');
	    $sth = $this->db->prepare(
	        "SELECT * FROM users ORDER BY userName"
		);
	    $sth->execute();
	    $users = $sth->fetchAll();
	    return $this->response->withJson($users);
	});

	//get particular user's info
	$app->get('/user/[{userID}]', function ($request, $response, $args) {
	    $sth = $this->db->prepare(
	        "SELECT * FROM users WHERE userID=:userID"
	    );
	    $sth->bindParam("userID", $args['userID']);
	    $sth->execute();
	    $user = $sth->fetchObject();
	    return $this->response->withJson($user);
	});

	//account creation
	$app->post('/newUser', function ($request, $response) {
	    $input = $request->getParsedBody();
	    $sql = "INSERT INTO 
	        users (email, userName, pass, track, firstName, lastName) 
	        VALUES (:email, :userName, :pass, :track, :firstName, :lastName)";
	    $sth = $this->db->prepare($sql);
	    $sth->bindParam("email", $input['email']);
	    $sth->bindParam("userName", $input['userName']);
		$sth->bindParam("pass", $input['pass']);
		$sth->bindParam("track", $input['track']);
		$sth->bindParam("firstName", $input['firstName']);
		$sth->bindParam("lastName", $input['lastName']);
	    $sth->execute();
	    return $this->response->withJson($input);
	});

	//return emails
	$app->get('/emails', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT email FROM users"
	    );
	    $sth->execute();
	    $emails = $sth->fetchAll();
	    return $this->response->withJson($emails);
	});

	//information for a particular user
	$this->group('/{userID}', function () use ($app){
		//return user's password
		$app->get('/password', function ($request, $response, $args){
			$sth = $this->db->prepare(
				"SELECT pass FROM users WHERE userID=:userID"
			);
			$sth->bindParam("userID", $args['userID']);
			$sth->execute();
			$passW = $sth->fetchObject();
			return $this->response->withJson($passW);
		});

		//update user's info
		$app->put('/update', function ($request, $response) {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("UPDATE users SET 
			email = :email,
			userName = :userName, 
			pass = :pass, 
			track = :track,
			firstName = :firstName, 
			lastName = :lastName
			WHERE userID = :userID");
			$sth->bindParam("email", $input['email']);
			$sth->bindParam("userName", $input['userName']);
			$sth->bindParam("pass", $input['pass']);
			$sth->bindParam("track", $input['track']);
			$sth->bindParam("firstName", $input['firstName']);
			$sth->bindParam("lastName", $input['lastName']);
			$sth->bindParam("userID", $args['userID']);
			$sth->execute();
			return $this->response->withJson($input);
		});
	
		//see a user's posts
		$app->get('/posts', function ($request, $response, $args){
			$sth = $this->db->prepare(
				"SELECT * FROM posts WHERE userID=:userID"
			);
			$sth->bindParam("userID", $args['userID']);
			$sth->execute();
			$posts = $sth->fetchObject();
			return $this->response->withJson($posts);
		});

		//delete a user's post
		$app->delete('/deletePost', function($request, $response){
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("DELETE FROM posts WHERE postID = :postID AND userID = :userID");
			$sth->bindParam("postID", $input['postID']);
			$sth->bindParam("userID", $args['userID']);
			$sth->execute();
			return $this->response->withJson($input);
		});

		//return a user's comments and the posts they were made on
		$app->get('/comments', function ($request, $response, $args){
			$sth = $this->db->prepare(
				"SELECT p.*, m.* FROM posts p join messages m on p.postID = m.postID WHERE m.userID=:userID"
			);
			$sth->bindParam("userID", $args['userID']);
			$sth->execute();
			$posts = $sth->fetchObject();
			return $this->response->withJson($posts);
		});

		//delete user's comment
		$app->delete('/deleteComment', function($request, $response){
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("DELETE FROM messages WHERE msgID = :msgID AND userID = :userID");
			$sth->bindParam("msgID", $input['msgID']);
			$sth->bindParam("userID", $args['userID']);
			$sth->execute();
			return $this->response->withJson($input);
		});
	});
});

//message boards
$app->group('/forums', function () use ($app) {
	//main page, show forums
	$app->get('/', function ($request, $response, $args){
		$sth = $this->db->prepare(
			"SELECT * FROM forums"
		);
		$sth->execute();
		$forumList = $sth->fetchObject();
		return $this->response->withJson($forumList);
	});

	//create a new message board
	$app->post('/newForum', function ($request, $response) {
	    $input = $request->getParsedBody();
	    $sql = "INSERT INTO 
	        forums (forumName) 
			VALUES (:forumName)";
	    $sth = $this->db->prepare($sql);
	    $sth->bindParam("forumName", $input['forumName']);
	    $sth->execute();
	    return $this->response->withJson($input);
	});

	//particular message board
	$this->group('/{forumID}', function () use ($app) {
		//get all posts
		$app->get('/all', function ($request, $response, $args){
			$sth = $this->db->prepare(
				"SELECT * FROM posts WHERE forumID = :forumID"
			);
			$sth->bindParam("forumID", $args['forumID']);
			$sth->execute();
			$posts = $sth->fetchAll();
			return $this->response->withJson($posts);
		});
			
		//create new post in the chosen board
		$app->post('/newPost', function ($request, $response, $args) {
			$input = $request->getParsedBody();
			$sql = "INSERT INTO 
				posts (title, body, forumID, userID) 
				VALUES (:title, :body, :forumID, :userID)";
			$sth = $this->db->prepare($sql);
			$sth->bindParam("forumID", $args['forumID']);
			$sth->bindParam("title", $input['title']);
			$sth->bindParam("body", $input['body']);
			$sth->bindParam("userID", $input['userID']);
			$sth->execute();
			return $this->response->withJson($input);
		});
	
		//go to particular post in the chosen board
		$this->group('/{postID}', function () use ($app) {
			//gets a post based on the posting id
			$app->get('/', function ($request, $response, $args){
				$sth = $this->db->prepare(
					"SELECT * FROM posts WHERE postID=:postID"
				);
				$sth->bindParam("postID", $args['postID']);
				$sth->execute();
				$post = $sth->fetchObject();
				return $this->response->withJson($post);
			});

			//show comments on the chosen post
			$app->get('/comments', function ($request, $response, $args){
				$sth = $this->db->prepare(
					"SELECT * FROM messages WHERE postID=:postID"
				);
				$sth->bindParam("postID", $args['postID']);
				$sth->execute();
				$post = $sth->fetchObject();
				return $this->response->withJson($post);
			});

			//make new comment on chosen post
			$app->post('/newComment', function ($request, $response, $args){
				$input = $request->getParsedBody();
				$sth = $this->db->prepare(
					"INSERT INTO messages (postID, body, userID)
					VALUES (:postID, :body, :userID)"
				);
				$sth->bindParam("postID", $args['postID']);
				$sth->bindParam("body", $input['body']);
				$sth->bindParam("userID", $input['userID']);
				$sth->execute();
				return $this->response->withJson($input);
			});
		});
	});
});