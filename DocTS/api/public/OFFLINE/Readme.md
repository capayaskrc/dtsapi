# Document Tracking System API
### Offline api



## 1. fetchDoc

### Description:
Fetch the data from database.

### Fields:
    dtnumber - Document Tracking Number
    document_title - Tittle of the Document
    doc_type - Type of Document 
    document_origin - Origin of the Document
    date_recieved - Date of when the documnent is recieved
    document_destination - Destination of the Document
    tag - tags of the document


### PHP CODE
    $app->post('/fetchDoc', function (Request $request, Response $response, array $args) {
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
    $data=array();
    while($row = $result->fetch_assoc()) {


    array_push($data,array(
    "dtnumber"=>$row["dtnumber"]
    ,"document_title"=>$row["document_title"]
    ,"doc_type"=>$row["doc_type"]
    ,"document_origin"=>$row["document_origin"]
    ,"date_recieved"=>$row["date_recieved"]
    ,"document_destination"=>$row["document_destination"]
    ,"tag"=>$row["tag"]));
    }
    $data_body=array("status"=>"success","data"=>$data);
    $response->getBody()->write(json_encode($data_body));
    } else {
    $response->getBody()->write(array("status"=>"success","data"=>null));
    }
    $conn->close();
    return $response;
    });

### Output sample
        {
            "status": "success",
            "data": [
                {
                    "dtnumber": "XXXX-XXX1",
                    "document_title": "1",
                    "doc_type": "1",
                    "document_origin": "1",
                    "date_recieved": "1",
                    "document_destination": "1",
                    "tag": "1"
                }
            ]
        }

## 2.searchDoc
    Search documement via dtnumber or Document Tracking Number.

## PHP CODE
        //searchDoc
        $app->post('/searchDoc', function (Request $request, Response $response, array $args) {
        $data=json_decode($request->getBody());
        $dtnumber =$data->dtnumber;
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dtsystem";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM document_fields where dtnumber='". $dtnumber ."'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        $data=array();while($row = $result->fetch_assoc()) {
        array_push($data,array("dtnumber"=>$row["dtnumber"]
        ,"document_title"=>$row["document_title"]
        ,"doc_type"=>$row["doc_type"]
        ,"document_origin"=>$row["document_origin"]
        ,"date_recieved"=>$row["date_recieved"]
        ,"document_destination"=>$row["document_destination"]
        ,"tag"=>$row["tag"]));
        }

        $data_body=array("status"=>"success","data"=>$data);
        $response->getBody()->write(json_encode($data_body));
        } else {$response->getBody()->write(array("status"=>"success","data"=>null));
        }$conn->close();
        return $response;
        });





## 3.deleteDoc
    Delete Document and its Details in the Record.


## PHP CODE
        $app->post('/deleteDoc', function (Request $request, Response $response, array $args) {
        $data=json_decode($request->getBody());
        $dtnumber =$data->dtnumber;
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
        $sql = "DELETE FROM document_Fields where dtnumber='". $dtnumber ."'";
        if ($conn->query($sql) === TRUE) {
            $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));
        }$conn->close();
        return $response;
        });




## 4.updateDoc
    Update Document details.


### PHP CODE


        $app->post('/updateDoc', function (Request $request, Response $response, array $args) {
        $data=json_decode($request->getBody());
        $dtnumber=$data->dtnumber;
        $document_title=$data->document_title;
        $doc_type=$data->doc_type;
        $document_origin=$data->document_origin;
        $date_recieved=$data->date_recieved;
        $document_destination=$data->document_destination;
        $tag=$data->tag;
        //Database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "demo";
        try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);$conn->setAttribute(PDO::ATTR_ERRMODEPDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE names set 
        document_title='".$document_title."',
        doc_type='".$doc_type."',
        document_origin='".$document_origin."',
        date_recieved='".$date_recieved."',
        document_destination='".$document_destination."',
        tag='".$tag."' where dtnumber='". $dtnumber ."'";
        // use exec() because no results are returned
        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));}catch(PDOException $e){
        $response->getBody()->write(json_encode(array("status"=>"error","message"=>$e->getMessage())));
        }
        $conn = null;
        return $response;
        });



