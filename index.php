<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//we are going to use session variables so we need to enable sessions
session_start();
        // SESSION vars
$email = $_SESSION['email'];
$street = $_SESSION['street'];
$street_nr = $_SESSION['streetnumber'];
$city = $_SESSION['city'];
$zipcode = $_SESSION['zipcode'];

$emailErr = $streetErr = $street_nrErr = $cityErr = $zipcodeErr = "";     //vars for error messages
$email_style = $street_style = $street_nr_style = $city_style = $zipcode_style = "";
$error_style = 'style="border-color: red"'; // error style

$success_msg = "";
$totalValue = 0;
$delivery = "";

// set total value cookie
if (isset($_COOKIE['totalValue'])){
    $totalValue = $_COOKIE['totalValue'];
} else {
    $totalValue = 0;
}

/*
Step 2: Make sure the address is saved
Save all the address information as long as the user doesn't close the browser. When he closes the browser it is oké to lose his information.

Prefill the address fields with the saved address. Should you use a COOKIE or a SESSION variable for this?
*/
function saveDataInSession(){

    global $email, $street, $street_nr, $city, $zipcode;

    $_SESSION['email'] = $email;
    $_SESSION['street'] = $street;
    $_SESSION['streetnumber'] = $street_nr;
    $_SESSION['city'] = $city;
    $_SESSION['zipcode'] = $zipcode;
}
/*Step 1: Validation
Validate that the field e-mail is filled in and a valid e-mail address
Make sure that the street, street number, city and zipcode is a required field.
Make sure that street number and zipcode are only numbers.
After sending the form, when you have errors show them in a nice error box above the form, you can use the bootstrap alerts for inspiration.
You do not need to show each error with it's matching field, showing all errors on top of the form is enough for now. You can always come back it later and make it nicer.
If the form is invalid make sure all the values the user entered are still displayed in the form, so he doesn't need to fill them all in again!
If the form is valid (for now) just show the user a message above the form that his order has been sent
 */

function validateFields(){

    $has_errors = false;    // we'll call this later to validate. If no errors -> validate form
    global $email, $street, $street_nr, $city, $zipcode;
    global $emailErr, $streetErr, $street_nrErr, $cityErr, $zipcodeErr;
    global $email_style, $street_style, $street_nr_style, $city_style, $zipcode_style, $error_style;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];       // save the value even when invalid
        if (empty($email)) {
            $email_style = $error_style;
            $emailErr = " * Email is required";
            $has_errors = true;
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_style = $error_style;
                $emailErr = " * Not a valid email address";
                $has_errors = true;
            }
        }

        $street = $_POST["street"];   // save the value even when invalid
        if (empty($street)) {
            $street_style = $error_style;
            $streetErr = "* Street is required";
            $has_errors = true;
        } else {
            $street = test_input($street);     //when field is filled, send input to test_input function
        }

        $street_nr = $_POST["streetnumber"];
        if (empty($street_nr)) {
            $street_nr_style = $error_style;
            $street_nrErr = " * Street number is required";
            $has_errors = true;
        } else {
            $street_nr = test_input($street_nr);

            if (!is_numeric($street_nr)) {         //validate street number: if not numeric -> error
                $street_nr_style = $error_style;
                $street_nrErr = "* Street number must be a numeric value";
                $has_errors = true;
            }
        }

        $city = $_POST["city"];
        if (empty($city)) {
            $city_style = $error_style;
            $cityErr = "* City is required";
            $has_errors = true;
        } else {
            $city = test_input($city);
        }

        $zipcode = $_POST["zipcode"];
        if (empty($zipcode)) {
            $zipcode_style = $error_style;
            $zipcodeErr = "* Zipcode is required";
            $has_errors = true;
        } else {
            $zipcode = test_input($zipcode);

            if (!is_numeric($zipcode)) {                      //validate zipcode: if not numeric -> error
                $zipcode_style = $error_style;
                $zipcodeErr = "* Zipcode must be a numeric value";
                $has_errors = true;
            }
        }
    } else {
        $has_errors = true;     //line 55 -> only valid if coming from a POST
    }
    return $has_errors;
}

function test_input($data){

    $data = trim($data);                //check and remove unnecessary characters
    //$data = stripslashes($data);
    //$data = htmlspecialchars($data);
    return $data;
}


function whatIsHappening(){

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


// switch between drinks and food
if (!isset($_SESSION['products'])) {     // if there's no session, food is default
    $products = $foods;
} else {
    $products = $_SESSION['products'];
}

if (isset($_GET['food'])) {
    if ($_GET['food'] == "1") {
        $products = $foods;
        $_SESSION['products'] = $foods;
    } else {
        $products = $drinks;
        $_SESSION['products'] = $drinks;
    }
}

$current_total = 0;

function calcRevenue(){

    global $current_total, $totalValue, $products;

    if (isset($_POST['products'])) {
        $post_products = $_POST['products'];
        for ($i = 0; $i < count($products); $i++) {      // loop through products
            if(isset($post_products[$i])){               //   &
                $current_total += $products[$i]['price'];  // get [prices]
                $totalValue += $products[$i]['price'];      //$i because it has the current index in product array
                //$ordered_product = $products[$i]['name'];
                //return $ordered_product;

            }
        }
    }
}



/*$current_total = 0;

function calcRevenue(){

    global $current_total, $totalValue, $products;

    if (isset($_POST['products'])) {
        $post_products = $_POST['products'];
        for ($i = 0; $i < count($products); $i++) {      // loop through products
            if(isset($post_products[$i])){               //   &
                $current_total += $products[$i]['price'];  // get [prices]
                $totalValue += $products[$i]['price'];      //$i because it has the current index in product array
                //$ordered_product = $products[$i]['name'];
                //return $ordered_product;

            }
        }
    }
}
*/
//$ordered_product = calcRevenue();

// delivery time
function calcDelivery(){

    global $delivery, $current_total, $totalValue;

    if (isset($_POST['express_delivery'])) {
        $totalValue += 5;  //when express is checked, total +5 EUR  | get current time & parse delivery time
        $current_total += 5;                                        // use capital H for 24hr clock
        $delivery = "Your order will be delivered at " . date("H:i", strtotime("+45 minutes")) . ". Total &euro; " . number_format($current_total, 2);

    } else {
        $delivery = "Your order will be delivered at " . date("H:i", strtotime("+2 hours")) . ". Total &euro; " . number_format($current_total, 2);
    }

    setcookie("totalValue", strval($totalValue), time() + (86400 * 30), "/");
}

function sendEmail(){

    global /*$email, */$street, $street_nr, $city, $zipcode,$success_msg;
    $address = $street . ' ' . $street_nr . ', ' . $zipcode . ' ' . $city;
    //global $ordered_product;

    mail('alexglezk@gmail.com', 'Order Summary', $success_msg . 'to ' . $address);         //. You ordered ' . $ordered_product);

    //echo $address;
    //return $address;
}

$has_errors = validateFields();     //return gets stored here
if (!$has_errors) {
    calcRevenue();      // functions are called from here if there are no errors
    calcDelivery();
    $success_msg = '<div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Order Registered!</h4>
                           <hr>
                        <p class="mb-0">' . $delivery . '</p>
                    </div>';
    sendEmail();

}
        // call it after above to store data in SESSION
saveDataInSession();

whatIsHappening();

require 'form-view.php';

