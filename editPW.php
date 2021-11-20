<?php
/**
Allows for the editing of staff account passwords
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    function validate()
    {
        var a = document.getElementById("newPW").value;
        var b = document.getElementById("conPW").value;
        if (a!==b) {
            alert("Passwords do not match");
            var Id= "<?php echo $_GET['Id']; ?>";
            location = 'editPW.php?Id='+ Id;
            return false;
        }
        else return true;
    }

    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Changed!', {
            body: 'Password change for the account for <?php echo $_POST['stfName']; ?>  was successful',
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

<?php if($_REQUEST['btnSub'])
{
        $checkSQL = "SELECT * FROM tbllogin WHERE Password = '" . strtoupper(md5(trim($_POST['oldPW']))) . "'";
        $checkSQLResult = mysqli_query($Link, $checkSQL);

        if(mysqli_num_rows($checkSQLResult) > 0)
        {
            $SQL = "UPDATE tbllogin SET Password = '".strtoupper(md5(trim($_POST['newPW'])))."'WHERE IC = '".strtoupper(trim($_GET['Id']))."'";
            $SQLResult = mysqli_query($Link, $SQL);

            ?>
            <script>checkers();</script>
            <?php
        }
        else echo "<script>alert('You have entered a wrong password');</script>";

}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tbllogin WHERE IC = '".$_GET['Id']."'";
    $SQLResult = mysqli_query($Link, $SQL);
    $RowInfo = mysqli_fetch_array($SQLResult);
}
?>
<title>Change Password</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<body onsubmit= "return validate();">
<div class="container">
    <h3>*Mandatory</h3>
    <form method="post" action="">
        <div align="center">
            <table>
                <caption>Change Password</caption>
                <tr>
                    <td class="move"><label for="stfName">Username: </label></td>
                    <td><input type="text" name="stfName" id="stfName" value="<?php echo $RowInfo['Username']; ?>" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="oldPW">*Old Password: </label></td>
                    <td><input type="password" name="oldPW" id="oldPW" pattern=".{8,}"   required title="8 characters minimum" onkeypress="callfunction(this)"></td>
                </tr>
                <tr>
                    <td class="move"><label for="newPW">*New Password: </label></td>
                    <td><input type="password" name="newPW" id="newPW" pattern=".{8,}"   required title="8 characters minimum" onkeypress="callfunction(this)"></td>
                </tr>
                <tr>
                    <td class="move"><label for="conPW">*Re-confirm Password: </label></td>
                    <td><input type="password" name="conPW" id="conPW" pattern=".{8,}"   required title="8 characters minimum" onkeypress="callfunction(this)"> </td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="btnSub" value="Change Password" class="button">
        </div>
    </form>
</div>
</body>