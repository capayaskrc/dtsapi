<?php
header("Access-Control-Allow-Origin: *");
if (isset($_FILES['file']['name'])) {

    /* Getting file name */
    $filename = $_FILES['file']['name'];

    /* Location */
    $location = "../images/" . $filename;
    $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);

    /* Valid extensions */
    $valid_extensions = array("jpg", "jpeg", "png");

    $response = 0;
    /* Check file extension */
    if (in_array(strtolower($imageFileType), $valid_extensions)) {
        /* Upload file */
        if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
            $response = "http://localhost/dts_api/dtsapi/DocTS/api/images/" . $filename;
        }
    }

    echo $response;
    exit;
}

echo 0;
