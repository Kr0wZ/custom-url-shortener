<?php 

include("functions.php");

$sanitized_uri = sanitize_url($_SERVER['REQUEST_URI']);


//If default URL
if($sanitized_uri === "/" || $sanitized_uri === "/index.php"){
	//DEBUG
	/*print_r("Default index page<br>");
	print_r("SERVER: ".$_SERVER['SERVER_NAME']."<br>");
	print_r("URI: " .$sanitized_uri."<br><br>");*/

	echo print_form();

	if(isset($_POST['submit'])){
		if(isset($_POST['base_url']) && !empty($_POST['base_url'])){
			if(verify_url($_POST['base_url'])){
				$base_url = sanitize_url($_POST['base_url']);
				$short_url = generate_uri();
				insert_database($base_url, $short_url);
			}
			else{
				print_r("The URL you entered is not valid");
			}
		}
		else{
			print_r("Error, empty link");
		}	
	}
	
}
else{
	//Check if URI is in the correct format
	$url = sanitize_url(substr($_SERVER['REQUEST_URI'], 1));
	if(check_format($url)){
		if(link_exists($url)){
			update_count($url);
			header("Location: ".select_link($url)[2]);
		}
	}
	elseif(is_url_stats($url)){
		get_stats($url);
	}
	else{
		echo "Error, the link you entered doesn't exist... <br>";
	}
	
	
}

?>