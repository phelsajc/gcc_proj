<?php
session_start();
$errmsg_arr = array();
$errflag = false;
// configuration
$dbhost 	= "localhost";//server
$dbname		= "db_gcc_assets";//database name
$dbuser		= "root";//username
$dbpass		= "";//no password

// database connection
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);

// new data
$user = $_POST['list'];
$password = $_POST['password'];

if($user == '') {
	$errmsg_arr[] = 'You must enter your Username';
	$errflag = true;
}
if($password == '') {
	$errmsg_arr[] = 'You must enter your Password';
	$errflag = true;
}

// query
$result = $conn->prepare("SELECT * FROM employee WHERE username= :hjhjhjh AND password= :asas");
$result->bindParam(':hjhjhjh', $user);
$result->bindParam(':asas', $password);
$result->execute();
$rows = $result->fetch(PDO::FETCH_NUM)	;
if($rows > 0) {
header("location: ../main.html");
}
else{
	$error="username or password is incorrect";
	header("Location: ../../index.php?id=$error");	

}

?>