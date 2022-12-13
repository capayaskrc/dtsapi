<?php
header("Access-Control-Allow-Origin: *");
if (isset($_FILES['file']['name'])) {

    /* Getting file name */
    $filename = $_FILES['file']['name'];

    /* Location */
    $location = "../attachments/" . $filename;
    $imageFileType = pathinfo($location, PATHINFO_EXTENSION);

    $response = 0;

    /* Upload file */
    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
        $response = "http://localhost/dts_api/dtsapi/DocTS/api/attachments/" . $filename;
    }

    echo $response;
    exit;
}

echo 0;
