<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Fri, 01 Jan 2021 00:00:00 GMT");
header("Pragma: no-cache");
header('Content-Type: application/json');

echo json_encode($data);