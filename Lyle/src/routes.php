<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->group('/api', function () use ($app) {
    $app->get('/hello', function ($request, $response, $args) {
        
	debugToConsole('SUCCESS!');
Return "Hello World";
    });

 //    $app->get('/[{name}]', function (Request $request, Response $response, array $args) {
	//     // Sample log message
	//     $this->logger->info("Slim-Skeleton '/' route");

	//     // Render index view
	//     return $this->renderer->render($response, 'index.phtml', $args);
	// });
function debugToConsole($msg) { 
        echo "<script>console.log(".json_encode($msg).")</script>";
}

    	$app->get('/users', function ($request, $response, $args) {	
	//debugToConsole('REQUEST');
	    $sth = $this->db->prepare(
	        "SELECT * FROM Users ORDER BY userName"
	    );
	    $sth->execute();
	    $users = $sth->fetchAll();
	    return $this->response->withJson($users);
	});

	$app->get('/user/[{userName}]', function ($request, $response, $args) {
	    $sth = $this->db->prepare(
	        "SELECT * FROM users WHERE userName=:userName"
	    );
	    $sth->bindParam("userName", $args['userName']);
	    $sth->execute();
	    $user = $sth->fetchObject();
	    return $this->response->withJson($user);
	});

	$app->post('/user', function ($request, $response) {
	    $input = $request->getParsedBody();
	    $sql = "INSERT INTO 
	        users (email, userName, passw) 
	        VALUES (:email, :userName, :passw)";
	    $sth = $this->db->prepare($sql);
	    $sth->bindParam("email", $input['email']);
	    $sth->bindParam("userName", $input['userName']);
	    $sth->bindParam("passw", $input['passw']);
	    $sth->execute();
	    return $this->response->withJson($input);
	});

	//get all data for one user
	$app->get('/users/[{userName}]', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT * FROM Users WHERE userName=:userName"
	    );
	    $sth->bindParam("userName", $args['userName']);
	    $sth->execute();
	    $user = $sth->fetchObject();
	    return $this->response->withJson($user);
	});

	//return emails
	$app->get('/email', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT email FROM Users"
	    );
	    $sth->execute();
	    $emails = $sth->fetchAll();
	    return $this->response->withJson($emails);
	});

	//get password for an account
	$app->get('/passW/[{email}]', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT passW FROM Users WHERE email=:email"
	    );
	    $sth->bindParam("email", $args['email']);
	    $sth->execute();
	    $passW = $sth->fetchObject();
	    return $this->response->withJsoon($passW);
	});

	//get all children associated with an individual email
	// $app->get('/children/[{email}]', function ($request, $response, $args){
	//     $sth = $this->db->prepare(
	//         "SELECT * FROM Children WHERE parent=:email"
	//     );
	//     $sth->bindParam("email", $args['email'] + '.com');
	//     $sth->execute();
	//     $children = $sth->fetchObject();
	//     return $this->response->withJson($children);
	// });

	//get all children associated with an individual username
	$app->get('/children/[{userName}]', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT * FROM Children WHERE parent=:userName"
	    );
	    $sth->bindParam("userName", $args['userName']);
	    $sth->execute();
	    $children = $sth->fetchObject();
	    return $this->response->withJson($children);
	});

	//get all posts
	$app->get('/posts', function ($request, $response, $args){
	    $th = $this->db->prepare(
	        "SELECT * FROM Posts"
	    );
	    $sth->execute();
	    $posts = $sth->fetchAll();
	    return $this->response->withJson($posts);
	});

	//gets a post based on the posting id
	$app->get('/post/[{id}]', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT * FROM Posts WHERE id=:id"
	    );
	    $sth->bindParam("id", $args['id']);
	    $sth->execute();
	    $post = $sth->fetchObject();
	    return $this->response->withJson($post);
	});

	//get all comments
	$app->get('/comments', function ($request, $response, $args){
	    $th = $this->db->prepare(
	        "SELECT * FROM Comments"
	    );
	    $sth->execute();
	    $comments = $sth->fetchAll();
	    return $this->response->withJson($comments);
	});
	
	//gets a comment based on the posting id
	$app->get('/comment/[{id}]', function ($request, $response, $args){
	    $sth = $this->db->prepare(
	        "SELECT * FROM Comments WHERE id=:id"
	    );
	    $sth->bindParam("id", $args['id']);
	    $sth->execute();
	    $comment = $sth->fetchObject();
	    return $this->response->withJson($comment);
	});
});