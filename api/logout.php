<?php
session_start();
session_unset();
session_destroy();

header("Content-Type: application/json; charset=UTF-8");
$response = ["isError" => false, "message" => "Logged out successfully"];
echo json_encode($response);
exit();
