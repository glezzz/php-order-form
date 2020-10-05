<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//we are going to use session variables so we need to enable sessions
session_start();

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

//your products with their price.
$products = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

$products = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];

$totalValue = 0;


$email = $street = $street_no = $city = $zipcode = "";
$streetErr = $street_noErr = $cityErr = $zipcodeErr = "";     //vars for error messages

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }}*/

          //check required fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["street"])) {
        $streetErr = "Street is required";
    } else {
        $street = test_input($_POST["street"]);     //when field is filled, send input to test_input function
    }

    if (empty($_POST["streetnumber"])) {
        $street_noErr = "Street number is required";
    } else {
        $street_no = test_input($_POST["streetnumber"]);
    }

    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    } else {
        $city = test_input($_POST["city"]);
    }

    if (empty($_POST["zipcode"])) {
        $zipcodeErr = "Zipcode is required";
    } else {
        $zipcode = test_input($_POST["zipcode"]);
    }
}

function test_input($data) {
    $data = trim($data);                //check and remove unnecessary characters
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_valid = "$email is not a valid email address";            //validate email
    }
    if (!is_numeric($street_no)){                                       //validate street number: if not numeric -> error
        $street_no_numeric = "Street number must be a numeric value";

    }
    if(!is_numeric($zipcode)){                      //validate zipcode: if not numeric -> error
        $zipcode_numeric = "Zipcode must be a numeric value";
    }










require 'form-view.php';
