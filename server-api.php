<?php

// To Create record in dynamic form

header('Access-Control-Allow-Headers: Content-Type, Cache-Control');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Origin: *');

include 'DotEnv.php';
include 'config.php';
include 'class.php';
include 'functions.php';

if (!isset($_POST["first_name"]) || $_POST["first_name"] == "") {
  echo json_encode(array('status' => 0, 'message' => 'First Name is required'));
  exit();
}
if (!isset($_POST["last_name"]) || $_POST["last_name"] == "") {
  echo json_encode(array('status' => 0, 'message' => 'Last Name is required'));
  exit();
}
if (!isset($_POST["email_address"]) || $_POST["email_address"] == "") {
  echo json_encode(array('status' => 0, 'message' => 'Email Address is required'));
  exit();
}
if (!isset($_POST["mobile_number"]) || $_POST["mobile_number"] == "") {
  echo json_encode(array('status' => 0, 'message' => 'Phone number is required'));
  exit();
}
if (!isset($_POST["password"]) || $_POST["password"] == "") {
  echo json_encode(array('status' => 0, 'message' => 'Password is required'));
  exit();
}

$classObj = new Token();

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email_address'];
$mobile_number = $_POST['mobile_number'];
$password = $_POST['password'];

$first_name =    clean($first_name);
$last_name = clean($last_name);
$email = cleanSpace($email);
$mobile_number = trim($mobile_number);
$password = trim($password);

$jsonPostFields = "[{
        \"fields\":[
          {
            \"element_name\": \"first_name\",
            \"value\": \"$first_name\"
          },
          {
            \"element_name\": \"last_name\",
            \"value\": \"$last_name\"
          },
          {
            \"element_name\": \"email_address\",
            \"value\": \"$email\"
          },
          {
            \"element_name\": \"mobile_number\",
            \"value\": \"$mobile_number\"
          },
          {
            \"element_name\": \"password\",
            \"value\": \"$password\"
          }
        ]
      }]";
$resp = $classObj->saveForm($jsonPostFields);
if ($resp != false) {
  echo json_encode(array('status' => 1, 'message' => 'Data Stored', 'data' => json_decode($resp)));
} else {
  echo json_encode(array('status' => 0, 'message' => 'Error while saving data'));
}
