<?php

header('Content-Type: application/json');

/**
 * activite
 * nom
 * prenom
 * email
 * date_naissance
 * telephone
 * adresse
 * code_postal
 * ville
 * pays
 * representant_legal
 */

$resp;

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$PATTERN_ALPHA = "/^[a-zàâçéèêëîïôûùüÿñæœ '.-]*$/i";
$PATTERN_ALPHA_NUM = "/^[a-z0-9àâçéèêëîïôûùüÿñæœ ,'.-]*$/i";
$PATTERN_CP = "/^[0-9]{5}$/";
$PATTERN_TEL = "/^(0033|0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/";
$PATTERN_EMAIL = "/^([A-Z|a-z|0-9](\.|_){0,1})+[A-Z|a-z|0-9]\@([A-Z|a-z|0-9])+((\.){0,1}[A-Z|a-z|0-9]){2}\.[a-z]{2,3}$/i";
$PATTERN_DATE = "/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/";

$regex['activite'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => false];
$regex['nom'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$regex['prenom'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$regex['email'] = ["regex" => $PATTERN_EMAIL, "require" => false];
$regex['date_naissance'] = ["regex" => $PATTERN_DATE, "require" => false];
$regex['telephone'] = ["regex" => $PATTERN_TEL, "require" => false];
$regex['adresse'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => false];
$regex['code_postal'] = ["regex" => $PATTERN_CP, "require" => false];
$regex['ville'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$regex['pays'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$regex['representant_legal'] = ["regex" => $PATTERN_ALPHA, "require" => false];

// Check values
foreach ($_POST as $k => $v) {
  if ($regex[$k]["require"] && !$_POST[$k]) $resp['Error'][$k] = "Required";
  if ($_POST[$k] && !preg_match($regex[$k]["regex"], $v)) $resp['Error'][$k] = "Invalid";
}

if ($resp['Error']) {
  echo json_encode($resp);
  return;
} else {
  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Build sql request
  $sql_k = "";
  $sql_v = "";
  foreach ($_POST as $k => $v) {
    $sql_k .= $k . ", ";
    $sql_v .= "'" . $v . "', ";
  }

  $sql = "INSERT INTO adherants (" . $sql_k . "date_inscription)
  VALUES (" . $sql_v . "CURRENT_TIMESTAMP)";

  if ($resp = mysqli_query($conn, $sql)) {
    $resp = "Success";
  } else {
    $resp['Error']['global'] = "Error: " . $sql . " - " . mysqli_error($conn);
  }

  mysqli_close($conn);
}

echo json_encode($resp);
