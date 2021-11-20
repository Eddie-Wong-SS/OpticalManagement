<?php
/**
 Allows the user to reset their password
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Reset Password</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    function validate()
    {
        var a = document.getElementById("newPW").value;
        var b = document.getElementById("conPW").value;
        if (a!==b) {
            alert("Passwords do no match");
            return false;
        }
    }

    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Changed!', {
            body: 'Password for the account for <?php echo $_POST['stfName']; ?>  was successful',
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
<body>
<div class="container" onsubmit="validate()">
    <form method="post" action="">
    <?php
    $SQL = "SELECT IC, Username FROM tbllogin";
    $Result = mysqli_query($Link, $SQL);
    $flag = 0;

    for($i = 0; $i < mysqli_num_rows($Result); ++$i)
    {
        $RowInfo = mysqli_fetch_array($Result);
        if(md5($RowInfo['IC']) == $_GET['IC'])
        {?>
            <table>
                <caption>Reset Password</caption>
                <tr>
                    <td><label for="CName">Username: </label></td>
                    <td><input type="text" name="CName" id="CName" size="52" value="<?php echo $RowInfo['Username']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td colspan="2"><h2 style="color: #2b8bc6">Enter your new password</h2></td>
                </tr>
                <tr>
                    <td><label for="CPW">New Password: </label></td>
                    <td><input type="password" name="CPW" ID="CPW" onkeypress="callfunction(this)" required> </td>
                </tr>
                <tr>
                    <td><label for="RPW">Reconfirm Password: </label></td>
                    <td><input type="password" name="RPW" id="RPW" onkeypress="callfunction(this)" required></td>
                </tr>
            </table>
            <?php
            $IC = $RowInfo['IC'];
            break;
        }
    }
    if($flag == 0) echo "<h1 style='color: red;'>Sorry, it seems that you have not requested for a password reset but still received an email, please check your email security</h1>";
    ?>
    </form>
</div>
</body>
<?php
$UpSQL = "UPDATE tbllogin SET Password = '".strtoupper(md5(trim($_POST['CPW'])))."' WHERE IC = '".$IC."'";
$UpSQLResult = mysqli_query($Link, $UpSQL);
$flag = 1;
echo "<h1 style='color: green;'>Your Account Has Been Successfully Verified!</h1>";
?>
