<?php
require '../Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//GET route
$app->get('/cities','getCities');

$app->get('/cities/:id', 'getLikesForCity');

$app->get('/cities/like/most', 'getMost');

$app->get('/cities/like/least', 'getLeast');

//Add a put route
$app->put('/cities/like','addLike');

$app->put('/cities/unlike','removeLike');



//GET Route
$app->get('/',function(){

    $template=<<<EOT
<!DOCTYPE html>
    <html><head></head>
    <title>Phoenix Slim App</title>
    <body><h1>Welcome to Phoenix Slim Web Service!</h1>
    <p>Panjapol Chiemsombat 100547314</p>
    </body></html>
EOT;
    echo $template;
});


function getCities(){

    try
    {
        //First we need to get a connection object to the database server.
        $hostname = 'localhost';
        $username = 'root';
        $password = 'root';
        $dbname = 'likeacity';
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$username,$password);

        //not lets craft an SQL select string
        $sql = "SELECT * from citylikes";

        //Make sql string into an SQL statement and execute the statement
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        //fetch records and place in array of objects
        $row=$stmt->fetchALL(PDO::FETCH_OBJ);

        //IMPORTANT to close connection after you have finished with it.
        //There could be hundreds of clients connecting to your site.
        //If you don't close connections to database this could slow you system
        $dbh = null;

        //return array of objects in JSON format
        echo json_encode($row);
    }catch(PDOException $e){
        echo $e->getMessage();
    }

}



function getLikesForCity($id)
{
    try {
        //First we need to get a connection object to the database server.
        $hostname = 'localhost';
        $username = 'root';
        $password = 'root';
        $dbname = 'likeacity';
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

        //not lets craft an SQL select string
        $sql = "SELECT * from citylikes where id=:id";

        //Make sql string into an SQL statement and execute the statement
        $stmt=$dbh->prepare($sql);
        $stmt->bindParam("id",$id);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_OBJ);

        //IMPORTANT to close connection after you have finished with it.
        //There could be hundreds of clients connecting to your site.
        //If you don't close connections to database this could slow you system
        $dbh = null;

        //can simple return the record in json format
        echo json_encode($row);
        echo "<br/>";

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getMost(){

    try{
        //First we need to get a connection object to the database server.
        $hostname = 'localhost';
        $username = 'root';
        $password = 'root';
        $dbname = 'likeacity';
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

        //not lets craft an SQL select string
        $sql = "Select * from citylikes Where Likes in (SELECT Max(likes) as likes from citylikes)";

        $stmt=$dbh->prepare($sql);
        $stmt->execute();
        $row=$stmt->fetchALL(PDO::FETCH_OBJ);

        //IMPORTANT
        $dbh = null;
        //

        echo json_encode($row);
        echo "<br/>";

    }catch (PDOException $e) {
        echo $e->getMessage();
    }

}

function getLeast()
{

    try {
        //First we need to get a connection object to the database server.
        $hostname = 'localhost';
        $username = 'root';
        $password = 'root';
        $dbname = 'likeacity';
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

        //not lets craft an SQL select string
        $sql = "Select * from citylikes Where Likes in (SELECT Min(likes) as likes from citylikes)";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchALL(PDO::FETCH_OBJ);

        //IMPORTANT
        $dbh = null;
        //

        echo json_encode($row);
        echo "<br/>";

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}



//addlike method
function addLike(){
    //Code here
    //Use slim to get the contents of the HTTP POST request
    $request = \Slim\Slim::getInstance()->request();

    //the request is in JSON format so we need to decode it
    $q = json_decode($request->getBody());

    //PDO stuff as usual
    // Create SQL UPDATE STRING
    $sql = "UPDATE citylikes set likes=likes + 1 Where countryid = :countryid and cityname= :cityname";
    try{
        // encapsulate connection stuff in a function
        $dbh = getConnection();
        $stmt=$dbh->prepare($sql);
        $stmt->bindParam("countryid",$q->countryid);
        $stmt->bindParam("cityname",$q->cityname);
        $stmt->execute();
        $dbh = null;
    } catch(PDOException $e){
        echo $e->getMessage();
    }


} //end addLike



function removeLike(){

    $request = \Slim\Slim::getInstance()->request();

    $q = json_decode($request->getBody());

    $sql = "UPDATE citylikes SET likes=likes - 1 WHERE countryid = :countryid and cityname= :cityname";
    try{
        // encapsulate connection stuff in a function
        $dbh = getConnection();
        $stmt=$dbh->prepare($sql);
        $stmt->bindParam("countryid",$q->countryid);
        $stmt->bindParam("cityname",$q->cityname);
        $stmt->execute();
        $dbh = null;
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

}

//encapsulate connection stuff in a function.
//When called this function returnes a reference to a PDO database connection object.
function getConnection(){
    //Connection details
    $dbhost="127.0.0.1";
    $dbUser="root";
    $dbpass="root";
    $dbName="likeacity";

    try{

        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbName",$dbUser,$dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }catch(PDOException $e){
        echo "Error PDO Exception";
    }

}//End getConnection()





$app->run();
