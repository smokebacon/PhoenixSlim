<?php
require '../Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//GET Route -- Default screen
$app->get('/', function () {

    $template = <<<EOT
<!DOCTYPE html>
    <html><head></head>
    <title>Phoenix Slim App</title>
    <body><h1>Welcome to Phoenix Slim Web Service!</h1>
    <p>Panjapol Chiemsombat 100547314</p>
    </body></html>
EOT;
    echo $template;
});

//COMMON FUNCTION
/**
 * @return PDO
 * When called this function returns a reference to a PDO database connection object.
 * change the configuration to access the server
 */
function getConnection()
{
    //Connection details
    $dbhost = "127.0.0.1";
    $dbUser = "root";
    $dbpass = "root";
    $dbName = "phoenix";

    try {

        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbName", $dbUser, $dbpass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        echo "Error PDO Exception";
    }

}//End getConnection()
$app->post('/customer/getAuth','getAuth');
function getAuth()
{
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    $q->Auth = generateAuthKey($q->Email,$q->Password);

    echo '{"Auth":"'.$q->Auth.'"}';
}

function generateAuthKey($Email,$Password){

  $auth= md5($Email.$Password);
  return $auth;

}

//GET Single customer data from table
$app->get('/customer/:Auth','getCustomer');
function getCustomer($Auth)
{
    try {
        //get connection to server
        $dbh = getConnection();

        //SQL statement
        $sql = "SELECT * FROM Customer WHERE Auth =:Auth";

        //Assign value and execute SQL statement
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam("Auth",$Auth);
        $stmt->execute();

        //fetch records into array of objects
        $row = $stmt->fetchALL(PDO::FETCH_OBJ);

        //close connections
        $dbh = null;

        //return array of objects in JSON
        echo json_encode($row);
        // echo '{"Email":"'.$row->Email.'"}';


    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

//GET Methods

//GET all from tour table
//returns JSON
$app->get('/tour/all', 'getAllTour');
/**
 * @return JSON of tour table
 * Get all tour in the database
 */
function getAllTour()
{

    try {
        //get connection to server
        $dbh = getConnection();

        //SQL statement
        $sql = "SELECT * FROM Tour";

        //Assign value and execute SQL statement
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        //fetch records into array of objects
        $row = $stmt->fetchALL(PDO::FETCH_OBJ);

        //close connections
        $dbh = null;

        //return array of objects in JSON
        echo json_encode($row);
    } catch (PDOException $e) {
        echo $e - getMessage();
    }

}

//GET all available trip of a tour
//returns JSON
$app->get('/tour/open/:id','getAvailableTrip');
function getAvailableTrip($id){
    try{
        $dbh = getConnection();

        $sql = "SELECT * , (SELECT SUM(Max_Passengers - (SELECT SUM(Num_Adults + Num_Concessions) FROM Booking WHERE Trip_Id IN (SELECT Trip_Id FROM trip WHERE Tour_No = :id))))'Seats Remaining'
FROM trip WHERE Trip_Id IN (SELECT Trip_Id FROM trip WHERE Tour_No = :id) AND Max_Passengers > (SELECT SUM(Num_Concessions + Num_Adults) FROM Booking WHERE Trip_Id IN (SELECT Trip_Id FROM trip WHERE Tour_No = :id))";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam("id",$id);
        $stmt->execute();

        $row = $stmt->fetchALL(PDO::FETCH_OBJ);

        $dbh = null;

        echo json_encode($row);
    }catch (PDOException $e){
        echo $e->getMessage();
    }
}

//GET all specific details of the tour and its itinerary
//returns JSON
$app->get('/tour/:id/itinerary','getItinerary');
function getItinerary($id){
  try {
      //get connection to server
      $dbh = getConnection();

      //SQL statement
      $sql = "SELECT * FROM itinerary WHERE tour_no = :id";

      //Assign value and execute SQL statement
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam("id", $id);
      $stmt->execute();

      //fetch records into array of objects
      $row = $stmt->fetchALL(PDO::FETCH_OBJ);

      //close connections
      $dbh = null;

      //return array of objects in JSON
          echo json_encode($row);


  } catch (PDOException $e) {
      echo $e->getMessage();
  }
}

//accept cust_id
//returns JSON
$app->get('/booking/:id', 'getPendingBooking');
/**
 * @return JSON of trip(s)
 * @param $id
 * Get all pending booking of a customer
 * logic : any booking with 0 amount of deposit is considered pending booking
 */
function getPendingBooking($id)
{

    try {
        //get connection to server
        $dbh = getConnection();

        //SQL statement
        $sql = "SELECT *
                FROM Booking
                WHERE Customer_Id = :id";

        //Assign value and execute SQL statement
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();

        //fetch records into array of objects
        $row = $stmt->fetchALL(PDO::FETCH_OBJ);

        //close connections
        $dbh = null;

        //return array of objects in JSON
            echo json_encode($row);


    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

//POST Methods
//Register a customer into database
//Accepts JSON as a detail of created account
//returns status
$app->post('/customer/add/', 'addNewCustomer');
/**
 * @return Status
 * Get JSON of customer details and INSERT them into databases
 */
function addNewCustomer()
{
    //Use slim to get HTTP POST contents
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    $q->Auth = generateAuthKey($q->Email,$q->Password);

    $sql = "INSERT INTO Customer SET
            First_Name = :First_Name,
            Middle_Initial = :Middle_Initial,
            Last_Name = :Last_Name,
            Street_No = :Street_No,
            Street_Name = :Street_Name,
            Suburb = :Suburb,
            Postcode = :Postcode,
            Email = :Email,
            Phone = :Phone,
            Auth = :Auth,
            Enabled=0;";

    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections

        $stmt->bindParam('First_Name', $q->First_Name);
        $stmt->bindParam('Middle_Initial', $q->Middle_Initial);
        $stmt->bindParam('Last_Name', $q->Last_Name);
        $stmt->bindParam('Street_No', $q->Street_No);
        $stmt->bindParam('Street_Name', $q->Street_Name);
        $stmt->bindParam('Suburb', $q->Suburb);
        $stmt->bindParam('Postcode', $q->Postcode);
        $stmt->bindParam('Email', $q->Email);
        $stmt->bindParam('Phone', $q->Phone);
        $stmt->bindParam('Auth', $q->Auth);

        $stmt->execute();
        $dbh = null;

        echo '{"Auth":"'.$q->Auth.'"}';

    } catch(PDOException $e){
        echo $e->getMessage();
}



}//End function addNewCustomer()


//Add a new review entry into a trip
//Accepts JSON as a detail of the review
//returns status
$app->post('/customer/review','addNewReview');
function addNewReview(){

  //Use slim to get HTTP POST contents
  $request = \Slim\Slim::getInstance()->request();

  //decode JSON
  $q = json_decode($request->getBody());

  //PDO
  $sql = "INSERT INTO Customer_Review SET
                      Trip_Id = :Trip_Id,
                      Customer_Id = :Customer_Id,
                      Rating = :Rating,
                      General_Feedback = :General_Feedback,
                      Likes = :Likes,
                      Dislikes = :Dislikes";


  try {
      $dbh = getConnection();
      $stmt = $dbh->prepare($sql);

      //bind parameters to prevent sql injections
      $stmt->bindParam("Trip_Id", $q->Trip_Id);
      $stmt->bindParam("Customer_Id",$q->Customer_Id);
      $stmt->bindParam("Rating",$q->Rating);
      $stmt->bindParam("General_Feedback",$q->General_Feedback);
      $stmt->bindParam("Likes",$q->Likes);
      $stmt->bindParam("Dislikes",$q->Dislikes);

      $stmt->execute();
      $dbh = null;
  } catch(PDOException $e){
      echo $e->getMessage();
}

  echo json_encode($q);
  echo "Successfully added review";


}//End function addNewReview()


//TODO Finish the function later
//Book a trip
//Accepts JSON as Trip Booking details
$app->post('/customer/book/','bookNewTrip');
function bookNewTrip() {
    //Use slim to get HTTP POST contents
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    $sql = "INSERT INTO Booking SET
            Customer_Id = :Customer_Id,
            Trip_Id = :Trip_Id,
            Booking_Date= :Booking_Date,
            Num_Concessions = :Num_Concessions,
            Num_Adults = :Num_Adults,
            Deposit_Amount= :Deposit_Amount";


    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam("Customer_Id",$q->Customer_Id);
        $stmt->bindParam("Trip_Id", $q->Trip_Id);
        $stmt->bindParam("Booking_Date", $q->Booking_Date);
        $stmt->bindParam("Num_Concessions",$q->Num_Concessions);
        $stmt->bindParam("Num_Adults",$q->Num_Adults);
        $stmt->bindParam("Deposit_Amount", $q->Deposit_Amount);

        $stmt->execute();

        $dbh=null;

        echo json_encode($q);
        echo "Successfully booked!";


    } catch(PDOException $e){
        echo $e->getMessage();
    }


}


//PUT Methods

//Edit Account Info
//accepts JSON as a detail of edited account
//returns a status
$app->post('/customer/edit/:Email', 'editAccountInfo');
/**
 * @param $id
 * @return Status
 * GET JSON of customer details and UPDATE them according to their field
 *
 */
function editAccountInfo($Email)
{

    //Use slim to get HTTP POST contents
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    //PDO
    $sql = "UPDATE customer SET
            First_Name=:First_Name,Middle_Initial=:Middle_Initial,Last_Name=:Last_Name,Street_No=:Street_No,Street_Name=:Street_Name,Suburb=:Suburb,Postcode=:Postcode,Phone=:Phone WHERE Email=:Email";


    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam('Email',$Email);
        $stmt->bindParam('First_Name', $q->First_Name);
        $stmt->bindParam('Middle_Initial', $q->Middle_Initial);
        $stmt->bindParam('Last_Name', $q->Last_Name);
        $stmt->bindParam('Street_No', $q->Street_No);
        $stmt->bindParam('Street_Name', $q->Street_Name);
        $stmt->bindParam('Suburb', $q->Suburb);
        $stmt->bindParam('Postcode', $q->Postcode);
        $stmt->bindParam('Phone', $q->Phone);

        $stmt->execute();
        $dbh = null;

    } catch(PDOException $e){
        echo $e->getMessage();
    }
    echo json_encode($q);
}

//Change customer password
//Accepts JSON as old & new password
//Compare each of them to see if it fits the auth
//if correct generate new authkey with salt and send it back as a new authkey
$app->post('/customer/changepassword','changePassword');
function changePassword(){

  //generate auth = 'Customer_Id','Email','Password_new'
  //update auth

  //Use slim to get HTTP POST contents
  $request = \Slim\Slim::getInstance()->request();

  //decode JSON
  $q = json_decode($request->getBody());

  $newAuth = generateAuthKey($q->Email,$q->Password);

  $q->Auth = $newAuth;

  //PDO
  $sql = "UPDATE Customer SET
          Auth = :Auth
          WHERE Email = :Email";

  try {
      $dbh = getConnection();
      $stmt = $dbh->prepare($sql);

      //bind parameters to prevent sql injections
      $stmt->bindParam('Email',$q->Email);
      $stmt->bindParam('Auth',$newAuth);

      $stmt->execute();
      $dbh = null;

  } catch(PDOException $e){
      echo $e->getMessage();
  }

  echo json_encode($q);
//  echo "new auth ".$q->Customer_Id." Updated";
}


//DELETE Methods
//Cancel booking
//Accept trip booking no
//returns a status
$app->delete('/booking/delete/', 'deleteBooking');
/**
 * JSON for (Trip_Booking_No)
 * @return Status
 * GET booking_no of a customer then DELETE them from the system
 */
function deleteBooking()
{
//Use slim to get HTTP POST contents
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    //PDO
    $sql = "DELETE FROM Booking WHERE Booking_No = :Booking_No";


    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam("Booking_No",$q->Booking_No);

        $stmt->execute();
        $dbh = null;

    } catch(PDOException $e){
        echo $e->getMessage();
    }

    echo json_encode($q);
    echo "Booking deleted";

}


$app->run(); //end app
