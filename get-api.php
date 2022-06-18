<?php
// Get List of Records
header('Access-Control-Allow-Headers: Content-Type, Cache-Control');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Origin: *');

include 'DotEnv.php';
include 'config.php';
include 'class.php';
include 'functions.php';

$classObj = new Token();
$output = array();

if (!isset($_POST["id"]) || $_POST["id"] == "") {
  $data = $classObj->getForm('?fields=&limit=100&offset=0&subform_order=desc');
  $ids = json_decode($data);
  foreach ($ids as $key => $value) {
    $record = $classObj->getForm('/' . $value->id . '?subform_order=desc');
    array_push($output, json_decode($record));
  }
} else {
  $ids = $_POST["id"];
  $record = $classObj->getForm('/' . $ids . '?subform_order=desc');
  array_push($output, json_decode($record));
}

if ($output) {
  echo json_encode(array('status' => 1, 'message' => 'Data retrieved', 'data' => $output));
} else {
  echo json_encode(array('status' => 0, 'message' => 'Error while saving data', 'data' => array()));
}
