<?php

header('Content-Type: application/json');

$resp;

include "../security.php";

$PATTERN_ALPHA = "/^[a-zàâçéèêëîïôûùüÿñæœ '.-]*$/i";
$PATTERN_ALPHA_NUM = "/^[a-z0-9àâçéèêëîïôûùüÿñæœ ,'.-]*$/i";
$PATTERN_CP = "/^[0-9]{5}$/";
$PATTERN_TEL = "/^(0033|0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/";
$PATTERN_EMAIL = "/^([A-Z|a-z|0-9](\.|_){0,1})+[A-Z|a-z|0-9]\@([A-Z|a-z|0-9])+((\.){0,1}[A-Z|a-z|0-9]){2}\.[a-z]{2,3}$/i";
$PATTERN_DATE = "/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/";

$validators['activite'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => false];
$validators['nom'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$validators['prenom'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$validators['email'] = ["regex" => $PATTERN_EMAIL, "require" => false];
$validators['date_naissance'] = ["regex" => $PATTERN_DATE, "require" => false];
$validators['telephone'] = ["regex" => $PATTERN_TEL, "require" => false];
$validators['adresse'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => false];
$validators['code_postal'] = ["regex" => $PATTERN_CP, "require" => false];
$validators['ville'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$validators['pays'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$validators['representant_legal'] = ["regex" => $PATTERN_ALPHA, "require" => false];

// Check values
foreach ($_POST as $k => $v) {
  if ($validators[$k]["require"] && !$_POST[$k]) $resp['Error'][$k] = "Required";
  if ($_POST[$k] && !preg_match($validators[$k]["regex"], $v)) $resp['Error'][$k] = "Invalid";
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

  $sql = "INSERT INTO adherents (" . $sql_k . "date_inscription)
  VALUES (" . $sql_v . "CURRENT_TIMESTAMP)";

  if ($resp = mysqli_query($conn, $sql)) {
    $resp = "Success";
  } else {
    $resp['Error']['global'] = "Error: " . $sql . " - " . mysqli_error($conn);
  }

  mysqli_close($conn);
}

echo json_encode($resp);
