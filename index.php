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


/*--------GET Route------*/
//GET all from tour table
//returns JSON
$app->get('/tour/all', 'getAllTour');

//GET all specific details of the tour and its itinerary
//returns JSON
$app->get('/tour/:id/itinerary','getItinerary');

//GET all PENDING status booking details -- Logic -- get only trip with 0 amount of deposit associate with the cust_id
//accept cust_id
//returns JSON
$app->get('/booking/:id', 'getPendingBooking');

//GET all trip details with this customer id -- regardless of deposits
//Accepts cust_id
//returns JSON
$app->get('/yourtrip/:id', 'getAllBooking');


/*--------POST Route------*/
//Register a customer into database
//Accepts JSON as a detail of created account
//returns status
$app->post('/customer/add/', 'addNewCustomer');

/*--------POST Route------*/
//Add a new review entry into a trip
//Accepts JSON as a detail of the review
//returns status
$app->post('/customer/review','addNewReview');

/*--------POST Route------*/
//Book a trip
//Accepts JSON as Trip Booking details
$app->post('/customer/book/','bookNewTrip');


//Link a trip to customer
$app->post('/customer/link/','linkCustomer');


/*--------PUT Route------*/
//Edit Account Info
//accepts JSON as a detail of edited account
//returns a status
$app->put('/customer/edit/', 'editAccountInfo');

//Change customer password
//Accepts JSON as old & new password
//Compare each of them to see if it fits the auth
//if correct generate new authkey with salt and send it back as a new authkey
$app->put('/staff/passwordchange','changePassword');


/*--------DELETE Route------*/
//Cancel booking
//Accept trip booking no
//returns a status
$app->delete('/booking/delete/', 'deleteBooking');


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


//GET Methods

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

function getItinerary($id)
{
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

/**
 * @return JSON of trip(s)
 * @param $id
 * Get all booking of a customer regarding of their pending status
 * Logic : any booking with or without deposits
 */
function getAllBooking($id)
{

    try {
        //get connection to server
        $dbh = getConnection();

        //SQL statement
        $sql = "SELECT c.Trip_Booking_No,c.Customer_Id,c.num_concessions,c.num_adults,t.trip_id,t.booking_date,t.Deposit_amount
                FROM Customer_Booking c JOIN trip_booking t ON c.trip_Booking_No = t.trip_Booking_No
                WHERE c.Customer_id = :id";

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

    //PDO
    $sql = "INSERT INTO Customer SET
            Customer_id = :Customer_id,
            First_Name = :First_Name,
            Middle_Initial = :Middle_Initial,
            Last_Name = :Last_Name,
            Street_No = :Street_No,
            Street_Name = :Street_Name,
            Suburb = :Suburb,
            Postcode = :Postcode,
            Email = :Email,
            Phone = :Phone";

//    $sql = "INSERT INTO Customer VALUES (:Customer_Id,:First_Name,:Middle_Initial,:Last_Name,:Street_No,:Street_Name,:Suburb,:Postcode,:Email,:Phone)";

    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam("Customer_id", $q->Customer_id);
        $stmt->bindParam("First_Name", $q->First_Name);
        $stmt->bindParam("Middle_Initial", $q->Middle_Initial);
        $stmt->bindParam("Last_Name", $q->Last_Name);
        $stmt->bindParam("Street_No", $q->Street_No);
        $stmt->bindParam("Street_Name", $q->Street_Name);
        $stmt->bindParam("Suburb", $q->Suburb);
        $stmt->bindParam("Postcode", $q->Postcode);
        $stmt->bindParam("Email", $q->Email);
        $stmt->bindParam("Phone", $q->Phone);

        $stmt->execute();
        $dbh = null;
    } catch(PDOException $e){
        echo $e->getMessage();
}

    echo json_encode($q);
    echo "Successfully added customer";

}//End function addNewCustomer()

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
  echo "Successfully added customer";


}//End function addNewReview()


//TODO Finish the route
/**
 * @param $trip_id , JSON in this format (Trip_Booking_No,Trip_Id,Booking_Date,Customer_Id,Num_Concession,Num_Adults)
 * Deposit amount will be 0 until the staff can confirm the booking by calling back to customer
 * After the first INSERT of trip booking just initiate another INSERT at customer Booking
 */
function bookNewTrip() {
    //Use slim to get HTTP POST contents
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    $sql = "INSERT INTO Trip_Booking SET
            Trip_Booking_No = :Trip_Booking_No,
            Trip_Id = :Trip_Id,
            Booking_Date= :Booking_Date,
            Deposit_Amount= :Deposit_Amount";



//    $sql = "INSERT INTO Customer VALUES (:Customer_Id,:First_Name,:Middle_Initial,:Last_Name,:Street_No,:Street_Name,:Suburb,:Postcode,:Email,:Phone)";

    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam("Trip_Booking_No", $q->Trip_Booking_No);
        $stmt->bindParam("Trip_Id", $q->Trip_Id);
        $stmt->bindParam("Booking_Date", $q->Booking_Date);
        $stmt->bindParam("Deposit_Amount", $q->Deposit_Amount);

        $stmt->execute();

        $dbh=null;


    } catch(PDOException $e){
        echo $e->getMessage();
    }


}

function linkCustomer(){

    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    $sql = "INSERT INTO Customer_Booking SET
            Trip_Booking_No = :Trip_Booking_No,
            Customer_Id = :Customer_Id,
            Num_Concessions = :Num_Concessions,
            Num_Adults = :Num_Adults";

    try {

        $dbh = getConnection();
        //prepare customer booking
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam("Trip_Booking_No", $q->Trip_Booking_No);
        $stmt->bindParam("Customer_Id", $q->Customer_Id);
        $stmt->bindParam("Num_Concessions", $q->Num_Concessions);
        $stmt->bindParam("Num_Adults", $q->Num_Adults);

        $stmt->execute();

        $dbh = null;
    }catch(PDOException $e){
        echo $e->getMessage();
    }

    echo json_encode($q);
    echo "Successfully booked trip";

}


//PUT Methods
/**
 * @param $id
 * @return Status
 * GET JSON of customer details and UPDATE them according to their field
 *
 */
function editAccountInfo()
{

    //Use slim to get HTTP POST contents
    $request = \Slim\Slim::getInstance()->request();

    //decode JSON
    $q = json_decode($request->getBody());

    //PDO
    $sql = "UPDATE Customer SET
            First_Name=:First_Name,Middle_Initial=:Middle_Initial,Last_Name=:Last_Name,Street_No=:Street_No,Street_Name=:Street_Name,Suburb=:Suburb,Postcode=:Postcode,Email=:Email,Phone=:Phone WHERE Customer_Id=:Customer_Id";


    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam("Customer_Id",$q->Customer_Id);
        $stmt->bindParam("First_Name", $q->First_Name);
        $stmt->bindParam("Middle_Initial", $q->Middle_Initial);
        $stmt->bindParam("Last_Name", $q->Last_Name);
        $stmt->bindParam("Street_No", $q->Street_No);
        $stmt->bindParam("Street_Name", $q->Street_Name);
        $stmt->bindParam("Suburb", $q->Suburb);
        $stmt->bindParam("Postcode", $q->Postcode);
        $stmt->bindParam("Email", $q->Email);
        $stmt->bindParam("Phone", $q->Phone);



        $stmt->execute();
        $dbh = null;

    } catch(PDOException $e){
        echo $e->getMessage();
    }

    echo json_encode($q);
    echo "Customer ".$q->Customer_Id." Updated";




}

function changePassword(){

  //TODO finish this function

}


//DELETE Methods
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
    $sql = "DELETE FROM Customer_Booking WHERE Trip_Booking_No = :Trip_Booking_No;DELETE FROM Trip_Booking WHERE Trip_Booking_No = :Trip_Booking_No";


    try {
        $dbh = getConnection();
        $stmt = $dbh->prepare($sql);

        //bind parameters to prevent sql injections
        $stmt->bindParam("Trip_Booking_No",$q->Trip_Booking_No);

        $stmt->execute();
        $dbh = null;

    } catch(PDOException $e){
        echo $e->getMessage();
    }

    echo json_encode($q);
    echo "Booking deleted";

}


$app->run(); //end app
