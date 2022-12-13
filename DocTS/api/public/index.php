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
        $date_sent = $data->date_sent;
        $document_destination = $data->document_destination;
        $tag = $data->tag;
        $attachment = $data->attachment;
        $receive = $data->receive;

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
            $sql = "INSERT INTO document_fields (dtnumber,document_title,doc_type,document_origin,date_sent,document_destination,tag,attachment,`receive`)
        VALUES ('" . $dtnumber . "','" . $document_title . "','" . $doc_type . "','" . $document_origin .
                "','" . $date_sent . "','" . $document_destination . "','" . $tag . "','" . $attachment . "','" . $receive . "')";
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
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"],
                "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"],
                "date_received" => $row["date_received"], "receive" => $row["receive"],
                "document_destination" => $row["document_destination"], "tag" => $row["tag"],
                "date_sent" => $row["date_sent"], "attachment" => $row["attachment"]
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
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"],
                "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"],
                "date_received" => $row["date_received"], "receive" => $row["receive"],
                "document_destination" => $row["document_destination"], "tag" => $row["tag"],
                "date_sent" => $row["date_sent"], "attachment" => $row["attachment"]
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
$app->post('/login', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());
    $email = $data->email;
    $role = $data->role;
    $pw = $data->password;
    //Database
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
    // $sql = "SELECT * FROM user_info where email='" . $email . "'" . " AND `role`='" . $role . "'" . " AND `password`='" . $pw . "'";
    $sql = "SELECT * FROM user_info where email='" . $email . "'" . " AND `role`='" . $role . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // $data = array();
        while ($row = $result->fetch_assoc()) {
            if (password_verify($pw, $row['password'])) {
                $data = array(
                    "userid" => $row["userid"], "email" => $row["email"],
                    "username" => $row["username"], "role" => $row["role"],
                    "profile_pic" => $row["profile_picture"], "school" => $row["school"],
                    "name" => $row["name"], "position" => $row["position"],
                );
            }
        }
        $data_body = array("status" => "success", "data" => $data);
        $response->getBody()->write(json_encode($data_body));
    } else {
        $response->getBody()->write(array("status" => "success", "data" => null));
    }
    $conn->close();
    return $response;
});

// endpoint change password
$app->post('/changePassword', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());
    $userid = $data->userid;
    $current_pw = $data->current_password;
    // $new_pw = $data->new_password;
    $new_pw = password_hash($data->new_password, PASSWORD_DEFAULT);
    //Database
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
    $sql = "SELECT * FROM user_info where userid='" . $userid . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // $data = array();
        while ($row = $result->fetch_assoc()) {
            if (password_verify($current_pw, $row['password'])) {
                $sql = "UPDATE user_info set `password`='" . $new_pw . "' where userid='" . $userid . "'";
            }
        }
        $conn->query($sql);
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } else {
        $response->getBody()->write(array("status" => "success", "data" => null));
    }
    $conn->close();
    return $response;
});

//endpoint Add User
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
    $school = $data->school;

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
        $sql = "INSERT INTO user_info (userid,username,email,`password`,profile_picture,`role`,`name`,position,`status`,school)
        VALUES ('" . $uid . "','" . $uname . "','" . $email . "','" . $pw . "','" . $pp . "','" . $role . "','" . $name . "','" . $position . "','" . $status . "','" . $school . "')";
        // use exec() because no results are returned
        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }
    $conn = null;
    return $response;
});

// endpoint Fetch Users (Display)
$app->post('/fetchUsers', function (Request $request, Response $response, array $args) { //Database
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
    $sql = "SELECT * FROM user_info";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "userid" => $row["userid"], "username" => $row["username"], "name" => $row["name"], "email" => $row["email"], "role" => $row["role"], "status" => $row["status"], "position" => $row["position"], "profile_picture" => $row["profile_picture"], "school" => $row["school"]
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

// endpoint search User
$app->post('/searchUser', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $userid = $data->userid;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM user_info where userid='" . $userid . "'";
    $result = $conn->query($sql);
    if (
        $result->num_rows > 0
    ) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "userid" => $row["userid"], "username" => $row["username"], "name" => $row["name"], "email" => $row["email"], "role" => $row["role"], "status" => $row["status"], "position" => $row["position"], "profile_picture" => $row["profile_picture"], "school" => $row["school"]
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

// Endpoit Track document
$app->post('/trackDoc', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $q = $data->q;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dtsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM document_fields WHERE dtnumber='" . $q . "' OR document_title='" . $q .
        "' OR doc_type='" . $q . "' OR document_origin='" . $q . "' OR date_received='" . $q .
        "' OR document_destination='" . $q . "' OR tag='" . $q . "' OR receive='" . $q .
        "' OR date_sent='" . $q . "'";
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

// Change profile pic
$app->post('/changeProfilePic', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());
    $userid = $data->userid;
    $img = $data->img;

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

        $sql = "UPDATE user_info set profile_picture='" . $img . "' where userid='" . $userid . "'";

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

$app->post('/addSchool', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());
    $school_name = $data->school_name;

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
        $sql = "INSERT INTO school (school_name) VALUES ('" . $school_name . "')";
        // use exec() because no results are returned
        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }
    $conn = null;
    return $response;
});

$app->post('/fetchSchools', function (Request $request, Response $response, array $args) { //Database
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
    $sql = "SELECT * FROM school";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "id" => $row["id"], "school_name" => $row["school_name"]
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

$app->post('/updateSchool', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());

    $id = $data->id;
    $school_name = $data->school_name;

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

        $sql = "UPDATE school set school_name='" . $school_name . "' where id='" . $id . "'";

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

$app->post('/deleteSchool', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody());
    $id = $data->id;

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
    $sql = "DELETE FROM school where id='" . $id . "'";
    if ($conn->query($sql) === TRUE) {
        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    }
    $conn->close();

    return $response;
});

$app->post('/fetchIncomingDoc', function (Request $request, Response $response, array $args) { //Database
    $data = json_decode($request->getBody());
    $userSchool = $data->userSchool;

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
    $sql = "SELECT * FROM document_fields where `receive`='false' and document_destination='" . $userSchool . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"],
                "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"],
                "date_received" => $row["date_received"], "receive" => $row["receive"],
                "document_destination" => $row["document_destination"], "tag" => $row["tag"],
                "date_sent" => $row["date_sent"], "attachment" => $row["attachment"]
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

$app->post('/receiveDoc', function (Request $request, Response $response, array $args) {
    $data = json_decode($request->getBody());

    $dtnumber = $data->dtnumber;
    $date_received = $data->date_received;

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

        $sql = "UPDATE document_fields set `receive`='true', date_received='" . $date_received . "' where dtnumber='" . $dtnumber . "'";

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

$app->post('/fetchReceivedDoc', function (Request $request, Response $response, array $args) { //Database
    $data = json_decode($request->getBody());
    $userSchool = $data->userSchool;

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
    $sql = "SELECT * FROM document_fields where `receive`='true' and document_destination='" . $userSchool . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"],
                "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"],
                "date_received" => $row["date_received"], "receive" => $row["receive"],
                "document_destination" => $row["document_destination"], "tag" => $row["tag"],
                "date_sent" => $row["date_sent"], "attachment" => $row["attachment"]
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

$app->post('/fetchOutgoingDoc', function (Request $request, Response $response, array $args) { //Database
    $data = json_decode($request->getBody());
    $userSchool = $data->userSchool;

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
    $sql = "SELECT * FROM document_fields where `receive`='false' and document_origin='" . $userSchool . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, array(
                "dtnumber" => $row["dtnumber"], "document_title" => $row["document_title"],
                "doc_type" => $row["doc_type"], "document_origin" => $row["document_origin"],
                "date_received" => $row["date_received"], "receive" => $row["receive"],
                "document_destination" => $row["document_destination"], "tag" => $row["tag"],
                "date_sent" => $row["date_sent"], "attachment" => $row["attachment"]
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

$app->run();
