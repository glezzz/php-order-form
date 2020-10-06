<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//we are going to use session variables so we need to enable sessions
session_start();

$email = $street = $street_nr = $city = $zipcode = "";
$emailErr = $streetErr = $street_nrErr = $cityErr = $zipcodeErr = "";     //vars for error messages

        //session vars
if (!empty($_SESSION['street'])){
    $street = $_SESSION['street'];
}
if (!empty($_SESSION['streetNumber'])){
    $street_nr = $_SESSION['streetNumber'];
}
if (!empty($_SESSION['city'])){
    $city = $_SESSION['city'];
}
if (!empty($_SESSION['zipCode'])){
    $zipcode = $_SESSION['zipcode'];
}


//check required fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email'])) {
        $emailErr = "Email is required";
    } else{
        $email = $_POST['email'];
    }

    if (empty($_POST["street"])) {
        $streetErr = "Street is required";
    } else {
        $street = test_input($_POST["street"]);     //when field is filled, send input to test_input function
    }

    if (empty($_POST["streetnumber"])) {
        $street_nrErr = "Street number is required";
    } else {
        $street_nr = test_input($_POST["streetnumber"]);
        $_SESSION['streetnumber'] = $street_nr;             // store data in SESSION
    }

    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    } else {
        $city = test_input($_POST["city"]);
        $_SESSION['city'] = $city;
    }

    if (empty($_POST["zipcode"])) {
        $zipcodeErr = "Zipcode is required";
    } else {
        $zipcode = test_input($_POST["zipcode"]);
        $_SESSION['zipcode'] = $zipcode;
    }
}

function test_input($data) {
    $data = trim($data);                //check and remove unnecessary characters
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$email_valid ="";
$street_nr_numeric = "";
$zipcode_numeric = "";            //email & numeric validation

if (!filter_var($email, FILTER_VALIDATE_EMAIL) && (!empty($_POST["email"]))) {
    $email_valid = "Not a valid email address";
}
if (!is_numeric($street_nr) && (!empty($_POST["streetnumber"]))){         //validate street number: if not numeric -> error
    $street_nr_numeric = "Street number must be a numeric value";

}
if(!is_numeric($zipcode) && (!empty($_POST["zipcode"]))){                      //validate zipcode: if not numeric -> error
    $zipcode_numeric = "Zipcode must be a numeric value";
}

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}
whatIsHappening();

//your products with their price.
$foods = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

$drinks = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];

$totalValue = 0;

// switch between drinks and food

if (!isset($_SESSION['products'])){     // if there's no session, food is default
    $products = $foods;
} else {
    $products = $_SESSION['products'];
}

if (isset($_GET['food'])){
    if ($_GET['food'] == "1"){
        $products = $foods;
        $_SESSION['products'] = $foods;
    } else {
        $products = $drinks;
        $_SESSION['products'] = $drinks;
    }
}

for ($i = 0; $i < count($products); $i++){      // loop through prices
    if (isset($_POST['products[i]'])){
        $totalValue += $products['i']['price'];

    }
}


$delivery = "";

// delivery time        //when express checked inc
if (isset($_POST['express_delivery'])){     // get current time & parse delivery time
    $delivery = "Delivered at " . date("H:i", strtotime("+45 minutes" ));  // use capital H for 24hr clock
    $totalValue += 5;

} else{
    $delivery = "Delivered at " . date("H:i", strtotime("+2 hours" )) . $totalValue;
}






/*$validation_message = "";
$errors = [$emailErr = $streetErr = $street_nrErr = $cityErr = $zipcodeErr = $email_valid = $street_nr_numeric = $zipcode_numeric];

    if ($errors == "") {
        $validation_message = "order sent";

    }*/











require 'form-view.php';
