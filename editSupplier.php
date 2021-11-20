<?php
/** Allows the editing of a supplier and viewing of all info */
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
            body: 'Modification of the supplier <?php echo $_POST['supName']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Supplier</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnedit'])
{
        $EditSupplierRecord = "UPDATE tblsupplier SET SuppId = '".strtoupper(trim($_POST['supId']))."', 
                                                    SuppName = '".strtoupper(trim($_POST['supName']))."',
                                                    Address = '".strtoupper(trim($_POST['supAdd']))."',
                                                    Email = '".strtoupper(trim($_POST['supEm']))."',
                                                    SuppNo = '".strtoupper(trim($_POST['supNum']))."',
                                                    ContactPerson = '".strtoupper(trim($_POST['supCon']))."',
                                                    ContactNo = '".strtoupper(trim($_POST['conNum']))."',
                                                    ContactEmail = '".strtoupper(trim($_POST['conEm']))."'
                                                    WHERE SuppId = '".strtoupper(trim($_POST['supId']))."'
                                                    ";
        $editSupplier = mysqli_query($Link,$EditSupplierRecord);
        if(!$editSupplier)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
        else
        {?>
            <script>checkers();</script>
        <?php
        }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblsupplier WHERE SuppId = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $conNo = $Row['ContactNo'];
    }
}
?>

<body>
<div class="container">
    <h1>Editing Supplier</h1>
    <h3>*Mandatory</h3>
    <br />
    <form method="post" action="">
        <div align="center">
            <table border="0">
                <caption>Supplier Details</caption>
                <tr>
                    <td class="move"><label for="supId">*Supplier ID: </label></td>
                    <td><input type="text" name="supId" id="supId" value = "<?php echo $Row['SuppId']; ?>" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="supName">*Supplier Name: </label></td>
                    <td><input type="text" name="supName" id="supName" value="<?php echo $Row['SuppName']; ?> " maxlength="50" size="52" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="supAdd">*Supplier Location: </label></td>
                    <td><textarea name="supAdd" id="supAdd" maxlength="250" cols="45" rows="5" required><?php echo $Row['Address']; ?></textarea> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supNum">*Supplier Contact Number: </label></td>
                    <td><input type="text" name="supNum" id="supNum" min="0" step="1" value="<?php echo $Row['SuppNo']; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supEm">*Supplier Email: </label></td>
                    <td><input type="email" name="supEm" id="supEm" value="<?php echo $Row['Email']; ?> " maxlength="40" size="42" required> </td>
                </tr>
            </table>
        <br /><br />

        <table border="0">
            <caption>Person-in-charge Details</caption>
            <tr>
                <td class="move"><label for="supCon">*Person-in-charge: </label></td>
                <td><input type="text" name="supCon" id="supCon" value="<?php echo $Row['ContactPerson']; ?> " pattern="[A-Za-z]{3,50}" title="Only characters and a minimum length of 3" maxlength="50" size="52" required></td>
            </tr>
            <tr>
                <td class="move"><label for="conNum">*Contact Number: </label> </td>
                <td><input type="text" name="conNum" id="conNum" min="0" step="1" value="<?php echo $conNo; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');"  required></td>
            </tr>
            <tr>
                <td class="move"><label for="conEm">*Email: </label></td>
                <td><input type="email" name="conEm" id="conEm" value="<?php echo $Row['ContactEmail']; ?> " maxlength="50" size="52" required></td>
            </tr>
        </table>
        <br />
        <input type="submit" name="btnedit" class="button" value="Edit" style="text-align: center">
        </div>
    </form>
</div>
</body>