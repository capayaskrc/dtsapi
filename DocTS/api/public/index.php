<?php
header("Access-Control-Allow-Origin: *");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';
$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

//localhost version
$app->post(
    '/insertDoc',
    function (Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
        $dtnumber = $data->dtnumber;
        $document_title = $data->document_title;
        $doc_type = $data->doc_type;
        $document_origin = $data->document_origin;
        $date_received = $data->date_received;
        $document_destination = $data->document_destination;
        $tag = $data->tag;

        //Database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dtsystem";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $sql = "INSERT INTO document_fields (dtnumber,document_title,doc_type,document_origin,date_received,document_destination,tag)
        VALUES ('" . $dtnumber . "','" . $document_title . "','" . $doc_type . "','" . $document_origin .
                "','" . $date_received . "','" . $document_destination . "','" . $tag . "')";
            // use exec() because no results are returned
            $conn->exec($sql);
            $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
        }
        $conn = null;
        return $response;
    }
);

/**************FETCH DOCUMENT DATA LIST************ */

//endpoint fetchDoc
$app->post('/fetchDoc', function (Request $request, Response $response, array $args) { //Database

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM document_fields";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"], "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"], "date_received" => $row["date_received"], "document_destination" => $row["document_destination"], "tag" => $row["tag"]
            ));
        }
        $data_body = array("status" => "success", "data" => $data);
        $response->getBody()->write(json_encode($data_body));
    } else {
        $response->getBody()->write(array("status" => "success", "data" => null));
    }
    $conn->close();
    return $response;
});



/**********FETCH/SEARCH SPECIFIC DOCUMENT VIA ID********** */
//searchDoc

$app->post('/searchDoc', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $dtnumber = $data->dtnumber;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM document_fields where dtnumber='" . $dtnumber . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"], "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"], "date_received" => $row["date_received"], "document_destination" => $row["document_destination"], "tag" => $row["tag"]
            ));
        }

        $data_body = array("status" => "success", "data" => $data);
        $response->getBody()->write(json_encode($data_body));
    } else {

        $response->getBody()->write(array("status" => "success", "data" => null));
    }
    $conn->close();

    return $response;
});

/**********DELETE DOCUMENT VIA ID/NUMBER********** */

$app->post('/deleteDoc', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $dtnumber = $data->dtnumber;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";;
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "DELETE FROM document_fields where dtnumber='" . $dtnumber . "'";
    if ($conn->query($sql) === TRUE) {
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    }
    $conn->close();

    return $response;
});



//*********************UPDATE DOCUMENT***************************

$app->post('/updateDoc', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());

    $dtnumber = $data->dtnumber;
    $document_title = $data->document_title;
    $doc_type = $data->doc_type;
    $document_origin = $data->document_origin;
    $date_received = $data->date_received;
    $document_destination = $data->document_destination;
    $tag = $data->tag;

    //Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";
    try {
        $conn = new

            PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // set the PDO error mode to exception
        $conn->setAttribute(
            PDO::ATTR_ERRMODE,

            PDO::ERRMODE_EXCEPTION
        );

        $sql = "UPDATE document_fields set document_title='" . $document_title . "',doc_type='" . $doc_type . "',
                document_origin='" . $document_origin . "',date_received='" . $date_received . "',
                document_destination='" . $document_destination . "',tag='" . $tag . "' where dtnumber='" . $dtnumber . "'";

        // use exec() because no results are returned
        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array(
            "status" => "error",

            "message" => $e->getMessage()
        )));
    }
    $conn = null;

    return $response;
});

//endpoint LOGIN (just a sample code)
// $app->post('/login', function (Request $request, Response $response, array
// $args) {
//     $data = json_decode($request->getBody());
//     $uname = $data->username;
//     $pw = $data->password;
//     //Database
//     $servername = "localhost";
//     $username = "root";
//     $password = "";
//     $dbname = "prac";
//     // Create connection
//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // Check connection
//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }
//     $sql = "SELECT * FROM prac_tbl where username='" . $uname . "'" . " AND `password`='" . $pw . "'";
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         // $data = array();
//         while ($row = $result->fetch_assoc()) {
//             $data = array(
//                 "username" => $row["username"], "email" => $row["email"]
//             );
//         }
//         $data_body = array("status" => "success", "data" => $data);
//         $response->getBody()->write(json_encode($data_body));
//     } else {
//         $response->getBody()->write(array("status" => "success", "data" => null));
//     }
//     $conn->close();
//     return $response;
// });

//endpoint register
$app->post('/addUser', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());
    $uname = $data->username;
    $name = $data->name;
    $email = $data->email;
    $pp = $data->pp;
    // $pw = $data->password;
    $pw = password_hash($data->password, PASSWORD_DEFAULT);
    $role = $data->role;
    $position = $data->position;
    $status = $data->status;

    $pref = substr($data->id, 0, 5);
    $uid = strtoupper(uniqid($pref));

    //Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $sql = "INSERT INTO user_info (userid,username,email,`password`,profile_picture,`role`,`name`,position,`status`)
        VALUES ('" . $uid . "','" . $uname . "','" . $email . "','" . $pw . "','" . $pp . "','" . $role . "','" . $name . "','" . $position . "','" . $status . "')";
        // use exec() because no results are returned
        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }
    $conn = null;
    return $response;
});

$app->run();
