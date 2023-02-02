<?php

function generate_uri(){
	$permitted_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYV0123456789";
	return substr(str_shuffle($permitted_chars), 0, 8);
}

function print_form(){
	$form = "
		<form action='#' method='post'>
			<label>Enter your URL to shorten</label>
			<input type='text' name='base_url'></input>
			<input type='submit' name='submit' value='Shorten'> 
		</form>
	";

	return $form;
}

function sanitize_url($url){
	return filter_var($url, FILTER_SANITIZE_URL);
}

function verify_url($url){
	//Check with regex if URL is valid
	$sanitized_url = sanitize_url($url);
	$url_regex = "/^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/";
	return preg_match($url_regex, $sanitized_url);
}

function insert_database($base_url, $short_url){
	include("connect.php");

	$creation_date = date('Y-m-d H:i:s');

	//Insert into table, create date
	$query = $conn->prepare("INSERT INTO links(short_url, base_url, creation_date, click_count) VALUES (?, ?, ?, DEFAULT)");
    //ss means string and string for the next variables
    $query->bind_param('sss', $short_url, $base_url, $creation_date);
    
    if($query->execute()){
    	echo "Your shortened link has been generated: <br>";
    	echo "http://".$_SERVER['SERVER_NAME']."/".$short_url;
    }
    else{
    	echo "Impossible to insert into database";
    }
}

function check_format($uri){
	$uri = sanitize_url($uri);
	return strlen($uri) == 8;
}

function link_exists($short_url){
	include("connect.php");
	return $conn->query("SELECT count(*) from links WHERE short_url = '".$short_url."'")->fetch_array()[0];
}

function update_count($short_url){
	include("connect.php");
	//Update entry for english word, to add the related id.
    $query = $conn->prepare("UPDATE links SET click_count = click_count + 1 WHERE short_url=?");

    //si means string and integer for the next variables
    $query->bind_param('s', $short_url);

    $query->execute();
}

function select_link($short_url){
	include("connect.php");
	return $conn->query("SELECT * from links WHERE short_url = '".$short_url."'")->fetch_array();
}

function is_url_stats($short_url){
	//If special char at the end of the URL then return true
	return substr($short_url, -1) == "+";
}

function get_stats($short_url){
	//Call select_link to retrieve data and print them (remove the last char because it is a +)
	$data = select_link(substr($short_url, 0, -1));

	echo "<pre>";
	echo "Redirection to: ".$data[2]."<br>";
	echo "Shortened link created at: ".$data[3]."<br>";
	echo "Number of clicks: ".$data[4]."<br>";
	echo "</pre>";
}

?>