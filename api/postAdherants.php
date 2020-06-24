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
$PATTERN_OUINON = "/^(Oui)|(Non)$/";
$PATTERN_CGU = "/accepted/";

$validators['reinscription'] = ["regex" => $PATTERN_OUINON, "require" => true];
$validators['activite'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => true];
$validators['nom'] = ["regex" => $PATTERN_ALPHA, "require" => true];
$validators['prenom'] = ["regex" => $PATTERN_ALPHA, "require" => true];
$validators['profession'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$validators['email'] = ["regex" => $PATTERN_EMAIL, "require" => true];
$validators['date_naissance'] = ["regex" => $PATTERN_DATE, "require" => true];
$validators['telephone'] = ["regex" => $PATTERN_TEL, "require" => true];
$validators['adresse'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => true];
$validators['code_postal'] = ["regex" => $PATTERN_CP, "require" => true];
$validators['ville'] = ["regex" => $PATTERN_ALPHA, "require" => true];
$validators['pays'] = ["regex" => $PATTERN_ALPHA, "require" => true];
$validators['representant_legal'] = ["regex" => $PATTERN_ALPHA, "require" => false];
$validators['contact_urgence'] = ["regex" => $PATTERN_ALPHA_NUM, "require" => false];
$validators['cgu'] = ["regex" => $PATTERN_CGU, "require" => true];

// Check values
foreach ($validators as $k => $v) {
  if ($v["require"] && !$_POST[$k]) $resp['Error'][$k] = "Ce champs est requis";
  if ($_POST[$k] && !preg_match($v["regex"], $_POST[$k])) $resp['Error'][$k] = "La valeur de ce champs n'est pas valide";
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
