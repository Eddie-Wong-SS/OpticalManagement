<?php
/**
 Shows the customer and staff that do not have accounts
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
include ('Email.php');
?>
<script language="javascript">
    function toggle(MaxCheck)
    {
        var i = 1;
        if(document.getElementById('chkAll').checked === true)
        {
            for( i = 1; i <= MaxCheck; i++)
            {
                document.getElementById('Rec' + i).checked = true;
            }
        }

        if(document.getElementById('chkAll').checked === false)
        {
            for( i = 1; i <= MaxCheck; i++)
            {
                document.getElementById('Rec' + i).checked = false;
            }
        }
    }

</script>
<title>Non-Account Registers Search Result</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container" align="center">
    <?php
    if($_REQUEST['btnSub'])
    {
        // while(list($key,$val) = each($_POST)) Each function is deprecated
        foreach($_POST as $key => $val)
        {
            if($key != "chkAll" && $key != "btnSub")
            {
                $SQL = "SELECT StaffName, Email from tblstaff WHERE StaffIC = '".$key."'";
                $SQLResult = mysqli_query($Link, $SQL);
                $RowInfo = mysqli_fetch_array($SQLResult);
                if($RowInfo['StaffName'] != "")
                {
                    $name = generate_unique_username($RowInfo['StaffName'], 9999);
                    $PW = randomPassword(10, 1, "lower_case,numbers,upper_case,special_symbols");
                    $random_hash = substr(uniqid(rand(), true), 8, 8);
                    $SQLAdd = "INSERT INTO tbllogin(IC, Username, Password, veriCode, AccType, Status) VALUES (
                        '".strtoupper(trim($key))."',
                        '".strtoupper(trim($name))."',
                        '".strtoupper(trim($PW))."',
                         '".$random_hash."',
                        'STAFF',
                        'N'
                    )";
                    $SQLAddResult = mysqli_query($Link, $SQLAdd);
                    $Verify = "An account has been created for you in Optical Store Management! Please enter the code below to verify your account and receive your new username and password!<br/>
                                    ".$random_hash."<br/> Please ignore if you did not wish or plan on having an account with us";
                    sendEmail("Account Verification", $Verify, $RowInfo['Email']);
                }
                else
                {
                    $SQLI = "SELECT CustName, Email from tblcustomer WHERE CustIC= '".$key."'";
                    $SQLIResult = mysqli_query($Link, $SQLI);

                    if(mysqli_num_rows($SQLIResult) > 0)
                    {
                        $RowInfo = mysqli_fetch_array($SQLIResult);
                        $name = generate_unique_username($RowInfo['CustName'], 9999);
                        $PW = randomPassword(10, 1, "lower_case,numbers,upper_case,special_symbols");
                        $random_hash = substr(uniqid(rand(), true), 8, 8);
                        $SQLAdd = "INSERT INTO tbllogin(IC, Username, Password, veriCode, AccType, Status) VALUES (
                        '".strtoupper(trim($key))."',
                        '".strtoupper(trim($name))."',
                        '".strtoupper(trim($PW))."',
                        '".$random_hash."',
                        'MEMBER',
                        'N'
                        )";
                        $SQLAddResult = mysqli_query($Link, $SQLAdd);
                        $UpdateSQL = "UPDATE tblcustomer SET AccType = 'MEMBER' WHERE CustIC = '".$key."'";
                        $UpdateSQLRes = mysqli_query($Link, $UpdateSQL);
                        $Verify = "An account has been created for you in Optical Store Management! Please enter the code below to verify your account and receive your new username and password!<br/>
                                    ".$random_hash."<br/> Please ignore if you did not wish or plan on having an account with us";
                        sendEmail("Account Verification", $Verify, $RowInfo['Email']);
                    }
                }
            }
        }
        echo "<script>location='viewStaffCustResult.php?id=A&page=1';</script>";
    }
    else{
        $SQL = $_SESSION['SQL'];
        $SQLI = $_SESSION['SQLI'];
        $Result = mysqli_query($Link, $SQL);
        $ResultI = mysqli_query($Link, $SQLI);
        if($Result || $ResultI)
        {
            if(mysqli_num_rows($Result) > 0 || mysqli_num_rows($ResultI) > 0)
            {
                $page = $_GET['page'];
                $maxRec = mysqli_num_rows($Result);
                $maxRec += mysqli_num_rows($ResultI);
                $maxPage = ($maxRec / 25) + 1;
                settype($maxPage, "integer");
                $maxLim = $page * 25;
                $minLim = $maxLim - 24;
                $span = 13;
                if($maxLim > $maxRec) $maxLim = $maxRec;
                ?>
                <form method="post" action="">
                    <?php if($maxPage > 1 && $page != $maxPage)
                        echo "<button><a style='font-size: 21px'  href='viewStaffCustResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='viewStaffCustResult.php?page=".($page-1)."'>&#60;</a> </button>"?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col">No</th>
                            <?php $count = mysqli_num_rows($Result);
                            $count += mysqli_num_rows($ResultI);
                            echo "<th scope='col'><input type='checkbox' name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>"; ?>
                            <th scope="col">Name</th>
                            <th scope="col">IC</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Account Type</th>
                        </tr>
                        <?php
                        for($i = $minLim ; $i <= $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);
                            if($i > mysqli_num_rows($Result))
                            {
                                $RowInfo = mysqli_fetch_array($ResultI);
                                echo "<tr>";
                                echo "<td>".($i)."</td>";
                                echo "<td style='text-align: center'><input type=\"checkbox\" name=\"".$RowInfo['CustIC']."\" id=\"Rec".($i)."\"></td>";
                                echo "<td>".$RowInfo['CustName']."</td>";
                                echo "<td style='text-align: center'>".$RowInfo['CustIC']."</td>";
                                echo "<td style='text-align: center'>".$RowInfo['Gender']."</td>";
                                echo "<td style='text-align: center'>Customer</td>";
                                echo "</tr>";
                            }
                            else
                            {
                                echo "<tr>";
                                echo "<td>".($i)."</td>";
                                echo "<td style='text-align: center'><input type=\"checkbox\" name=\"".$RowInfo['StaffIC']."\" id=\"Rec".($i)."\"></td>";
                                echo "<td>".$RowInfo['StaffName']."</td>";
                                echo "<td style='text-align: center'>".$RowInfo['StaffIC']."</td>";
                                echo "<td style='text-align: center'>".$RowInfo['Gender']."</td>";
                                echo "<td style='text-align: center'>Staff</td>";
                                echo "</tr>";
                            }
                        }

                        echo "<tr>";
                        echo "<td></td>";
                        echo "<th style='background-color: initial'></td>";
                        echo "<td align=\"center\" colspan=\"100%\"><input type=\"submit\" name=\"btnSub\" value=\"Register Accounts\" id=\"Register\" onclick='return confirm(\"This will create accounts for the selected. Proceed?\")' class='button'></td>";
                        echo "</tr>";
                        ?>
                    </table>
                </form>
            <?php }
        }
    }
    ?>
</div>
</body>
<script type="text/javascript">
    var allLinks = document.getElementsByTagName('a');
    for(var i=0; i < allLinks.length; ++i) {
        if(allLinks[i].getAttribute('class') === "hoverme") {
            allLinks[i].onmouseover = function () {
                this.parentNode.parentNode.style.background = 'linear-gradient(#ADD8E6,#4169E1)';
                this.style.color = 'red';
            };
            allLinks[i].onmouseout = function () {
                this.parentNode.parentNode.style.background= '';
                this.style.color = 'blue';
            };
        }
    }

</script>
<?php
//Generate a unique username using Database
function generate_unique_username($string_name, $rand_no)
{
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


function username_exist_in_database($username)
{
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

function randomPassword($length,$count, $characters)
{

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

?>