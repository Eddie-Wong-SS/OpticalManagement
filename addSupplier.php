<?php
/**Adds a supplier into the system
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Registered!', {
            body: 'Registration of the supplier <?php echo $_POST['supName']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Add Supplier</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSubmit'])
{
    $CheckSupSQL = "SELECT * FROM tblsupplier WHERE SuppName = '".strtoupper(trim($_POST['supName']))."'";
    $CheckSupSQLResult = mysqli_query($Link, $CheckSupSQL);

    if(!mysqli_num_rows($CheckSupSQLResult))
    {
        $AddSup = "INSERT INTO tblsupplier(SuppName, Address, Email, SuppNo, ContactPerson, ContactNo, ContactEmail, Status) VALUES (
                    '".strtoupper(trim($_POST['supName']))."',
                    '".strtoupper(trim($_POST['supAdd']))."',
                    '".strtoupper(trim($_POST['supEm']))."',
                    '".strtoupper(trim($_POST['supNum']))."',
                    '".strtoupper(trim($_POST['supCon']))."',
                    '".strtoupper(trim($_POST['conNum']))."',
                    '".strtoupper(trim($_POST['conEm']))."',
                    'A')";
        $AddSupResult = mysqli_query($Link, $AddSup);?>
        <script>checkers();</script>
<?php
    }
    else{
        echo "<script>alert('A supplier by this name already exists'); </script>";
    }
}
?>

<body>
<div class="container">
    <h1>Add Supplier</h1>
    <h3>*Mandatory</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" >
                <caption>Supplier Details</caption>
                <tr>
                    <td class="move"><label for="supName">*Supplier Name: </label></td>
                    <td ><input type="text" name="supName" id="supName" maxlength="50" size="52" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="supAdd">*Supplier Location: </label></td>
                    <td><textarea name="supAdd" id="supAdd" maxlength="250" cols="45" rows="5" required></textarea> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supNum">*Supplier Contact Number: </label></td>
                    <td><input type="number" name="supNum" id="supNum" min="0" step="1" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supEm">*Supplier Email: </label></td>
                    <td><input type="email" name="supEm" id="supEm" maxlength="40" size="42" required> </td>
                </tr>
            </table>
            <br /><br />

            <table border="0">
                <caption>Person-in-charge Details</caption>
                <tr>
                    <td class="move"><label for="supCon">*Person-in-charge: </label></td>
                    <td><input type="text" name="supCon" id="supCon" maxlength="50" size="52" pattern="[A-Za-z]{3,50}" title="Only characters and a minimum length of 3" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="conNum">*Contact Number: </label> </td>
                    <td><input type="number" name="conNum" id="conNum" maxlength="50" size="52" min="0" step="1" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="conEm">*Email: </label></td>
                    <td><input type="email" name="conEm" id="conEm" maxlength="50" size="52" required></td>
                </tr>
            </table>
            <br />
    <input type="submit" name="btnSubmit" class="button" value="Register" style="text-align: center">
        </div>
    </form>
</div>
</body>

