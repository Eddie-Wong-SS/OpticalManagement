<?php
/**
 Adds a new account for a member, and upgrades a patient to member if they are not yet a member
 */
error_reporting(2);
session_start();
include("database.php");
include("Menu.php");
include ('Email.php');

if($_SESSION['Login'] == true)
{
    echo "<script>alert('You already have an account, you dont need to make a new account');</script>";
    echo "<script>location = 'Main Page.php'; </script>";
}?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Registered!', {
            body: 'Registration of the account for member <?php echo $_POST['pName']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function callfunction(source)
    {

        var textBox = source;
        var textLength = textBox.value.length;

        if(textLength<8)
        {
            textBox.style.borderColor = "red";
        }
        else textBox.style.borderColor = "green";


    }
</script>
<?php
$SQL = "SELECT CustName from tblcustomer WHERE CustIC = '".$_GET['Id']."'";
$SQLResult = mysqli_query($Link, $SQL);
$RowInfo = mysqli_fetch_array($SQLResult);

//Generate a unique username using Database
function generate_unique_username($string_name, $rand_no){
    while(true){
        $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = (!empty($username_parts[0]))?substr($username_parts[0], 0,8):""; //cut first name to 8 letters
        $part2 = (!empty($username_parts[1]))?substr($username_parts[1], 0,5):""; //cut second name to 5 letters
        $part3 = ($rand_no)?rand(0, $rand_no):"";

        $username = $part1. str_shuffle($part2). $part3; //str_shuffle to randomly shuffle all characters

        $username_exist_in_db = username_exist_in_database($username); //check username in database
        if(!$username_exist_in_db){
            return $username;
        }
        else echo generate_unique_username($string_name, 9999);
    }
}


function username_exist_in_database($username){
    $mysqli = new mysqli('localhost','username','password','dbadmin'); //connect to database

    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    $statement = $mysqli->prepare("SELECT userId FROM tbllogin WHERE username = ?");
    $statement->bind_param('s', $username);
    if($statement->execute()){
        $statement->store_result();
        return $statement->num_rows;
    }
}

function randomPassword($length,$count, $characters) {

// $length - the length of the generated password
// $count - number of passwords to be generated
// $characters - types of characters to be used in the password

// define variables used within the function
    $symbols = array();
    $passwords = array();
    $used_symbols = '';
    $pass = '';

// an array of different character types
    $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
    $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $symbols["numbers"] = '1234567890';
    $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

    $characters = split(",",$characters); // get characters types to be used for the passsword
    foreach ($characters as $key=>$value) {
        $used_symbols .= $symbols[$value]; // build a string with all characters
    }
    $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1

    for ($p = 0; $p < $count; $p++) {
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $symbols_length); // get a random character from the string with all characters
            $pass .= $used_symbols[$n]; // add the character to the password string
        }
        $passwords[] = $pass;
        return $passwords[$p]; // return the generated password
    }
}

if($_REQUEST['btnSubmit'])
{
    $checkSQl = "SELECT * FROM tbllogin WHERE IC = '".$_GET['Id']."'";
    $checkSQLResult = mysqli_query($Link, $checkSQl);

    if(!mysqli_num_rows($checkSQLResult))
    {
        $setSQL = "SELECT AccType, Email FROM tblcustomer WHERE CustIC = '".$_GET['Id']."'";
        $setSQLResult = mysqli_query($Link, $setSQL);
        $sRowInfo = mysqli_fetch_array($setSQLResult);
        if($sRowInfo['AccType'] != "MEMBER")
        {
            $aSQL = "UPDATE tblcustomer SET AccType = 'MEMBER' WHERE CustIC = '".$_GET['Id']."'";
            $aSQLResult = mysqli_query($Link, $aSQL);
        }
        $random_hash = substr(uniqid(rand(), true), 8, 8);
        $SQL = "INSERT INTO tbllogin(IC, Username, Password, veriCode, AccType, Status) VALUES (
            '".strtoupper(trim($_GET['Id']))."',
            '".strtoupper(trim($_POST['pUseName']))."',
            '".strtoupper(trim($_POST['pPW']))."',
            '".$random_hash."',
            'MEMBER',
            'N'
        )";
        $SQLResult = mysqli_query($Link, $SQL);
        if($SQLResult)
        {
            $Verify = "An account has been created for you in Optical Store Management! Please enter the code below to verify your account and receive your new username and password!<br/>
                                    ".$random_hash."<br/> Please ignore if you did not wish or plan on having an account with us";
            sendEmail("Account Verification", $Verify, $sRowInfo['Email']);
            ?>
                    <script>checkers();</script>
        <?php }
        else echo "<script>alert('Oops, something has gone wrong'); </script>";
    }
    else echo "<script>alert('An account has already been made!');</script>";
}
?>
<title>Add Member Account</title>
<link rel="stylesheet" type="text/css" href="Default%20Theme.css">
<style>
    .spoils
    {
        background-color: black !important;
        color: black;
    }

    .spoils:hover
    {
        background-color: transparent !important;
    }
</style>

<body onsubmit="validate()">
<div class="container">
    <h3>Password may be changed, but username is set</h3>
    <form method="post" action="">
        <div align="center">
            <table border="0">
                <caption>Add Member Account</caption>
                <tr>
                    <td class="move"><label for="pName">Member Name: </label></td>
                    <td><input type="text" name="pName" id="pName" value="<?php echo $RowInfo['CustName']; ?>" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="pUseName">Username: </label></td>
                    <td><input type="text" name="pUseName" id="pUseName" value="<?php echo generate_unique_username($RowInfo['CustName'], 9999); ?>" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="pPW">*Password: </label></td>
                <td><input type="text" name="pPW" id="pPW" value="<?php echo randomPassword(10,1,"lower_case,numbers"); ?>" class="spoils" onkeypress="callfunction(this)" required> </td>
                </tr>
            </table>

            <input type="submit" name="btnSubmit" value="Register" class="button">
            <br/><br/>
            <!--input type="submit" name="btnPDF" value="Convert to PDF" class="button"-->
        </div>
    </form>
</div>
</body>