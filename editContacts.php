<?php
/**
 Allows the viewing and editing of contacts in detail
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
        Push.create('Successfully Edited!', {
            body: 'Modification of the contact record <?php echo $_POST['iCode']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Contact Record</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $imgSQL = "SELECT * FROM tblinventory WHERE  Code = '".$_GET['Id']."'";
    $imgSQLResult = mysqli_query($Link, $imgSQL);
    if(mysqli_num_rows($imgSQLResult) > 0) {
        $Rows = mysqli_fetch_array($imgSQLResult);
    }
    if($_FILES['image']['size'] != 0)
    {
        $target_path = "Images/";
        $target_path = $target_path . "Contact".$_POST['iCode'].".png";
        move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
    }
    else $target_path = $Rows['imageLoc'];

    $EditInventoryRecord = "UPDATE tblinventory SET Code = '".strtoupper(trim($_POST['iCode']))."',
                                                    ItemName = '".strtoupper(trim($_POST['iName']))."',
                                                    ItemDesc = '".strtoupper(trim($_POST['iDescrip']))."',
                                                    ItemType = '".strtoupper(trim($_POST['iType']))."',                                                                                             
                                                    imageLoc = '$target_path'
                                                    WHERE Code = '".strtoupper(trim($_GET['Id']))."'
                                                    ";
    $editInventory = mysqli_query($Link,$EditInventoryRecord);

    $EditContactRecord = "UPDATE tblcontacts SET contactCode = '".strtoupper(trim($_POST['iCode']))."',
                                          conCategory = '".strtoupper(trim($_POST['cCat']))."',
                                          conType = '".strtoupper(trim($_POST['cType']))."',
                                          conMaterial = '".strtoupper(trim($_POST['cMat']))."',
                                          conColor = '".strtoupper(trim($_POST['cColor']))."',
                                          Sphere = '".strtoupper(trim($_POST['cPWR']))."',
                                          BC = '".strtoupper(trim($_POST['cBC']))."',
                                          Dia = '".strtoupper(trim($_POST['cD']))."',
                                          Cyl = '".strtoupper(trim($_POST['cYL']))."',
                                          Axis = '".strtoupper(trim($_POST['cA']))."',
                                          addPwr = '".strtoupper(trim($_POST['cADD']))."',
                                          ExpireDateC = '".strtoupper(trim($_POST['cEX']))."'
                                          WHERE contactCode = '".strtoupper(trim($_GET['Id']))."'
                                          ";
    $editContact = mysqli_query($Link, $EditContactRecord);
    if(!$editInventory || ! $editContact)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
    else
    {?>
        <script>checkers();</script>
        <?php
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblinventory, tblcontacts WHERE tblinventory.Code = tblcontacts.contactCode AND Code = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);

    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $default = $Row['imageLoc'];
        if($default == "")
        {
            $default = "Images/no image available.png";
        }
    }
}
?>

<body>
<div class="container">
    <h1>Edit Contact Lens Record</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="iCode">*Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" value="<?php echo $Row['Code'] ?>" style="background-color: lightgray" readonly> </td>
                <td><label for="image">*Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button"></td>
            </tr>
            <tr>
                <td class="move"><label for="iName">*Item Name: </label></td>
                <td><input type="text" name="iName" id="iName" maxlength="50" size="52" value="<?php echo $Row['ItemName'] ?>" required> </td>
                <td rowspan="3" align="center"><img src="<?php echo $default ?>" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
            </tr>
            <tr>
                <td class="move"><label for="iDescrip">*Item Description</label></td>
                <td><textarea name="iDescrip" id="iDescrip" cols="30" rows="5" maxlength="100" title="Maximum 50 characters" required><?php echo $Row['ItemDesc'] ?></textarea> </td>
            </tr>
            <tr>
                <td class="move"><label for="iType">Item Type: </label></td>
                <td><input type="text" name="iType" id="iType" value="<?php echo $Row['ItemType'] ?>" size="7" style="background-color: lightgray" readonly> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Contact Details</caption>
            <tr>
                <td class="move"><label for="cCat">*Category: </label></td>
                <td><input type="text" name="cCat" id="cCat" maxlength="25" size="27" value="<?php echo $Row['conCategory'] ?>" required></td>
            </tr>
            <tr>
                <td class="move"><label for="cType">*Contact Type: </label></td>
                <td><input type="text" name="cType" id="cType" maxlength="25" size="27" value="<?php echo $Row['conType'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cMat">*Contact Material: </label></td>
                <td><input type="text" name="cMat" id="cMat" maxlength="25" size="27" value="<?php echo $Row['conMaterial'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cColor">*Contact Color: </label></td>
                <td><input type="color" name="cColor" id="cColor" value="<?php echo $Row['conColor'] ?>" required> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>RX Details</caption>
            <tr>
                <td class="move"><label for="cPWR">*PWR(Power): </label></td>
                <td><input type="number" name="cPWR" id="cPWR" step="0.01" value="<?php echo $Row['Sphere'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cBC">*BC(Base Curve): </label></td>
                <td><input type="number" name="cBC" id="cBC" step="0.1" min="0" value="<?php echo $Row['BC'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cD">*DIA(Diameter): </label></td>
                <td><input type="number" name="cD" id="cD" step="0.1" min="0" value="<?php echo $Row['Dia'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cYL">*CYL(Cylinder): </label></td>
                <td><input type="number" name="cYL" id="cYL" step="0.01" value="<?php echo $Row['Cyl'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cA">*Axis(In Degrees): </label></td>
                <td><input type="number" name="cA" id="cA" min="0" step="1" value="<?php echo $Row['Axis'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cADD">*ADD(Add Power): </label></td>
                <td><input type="number" name="cADD" id="cADD" step="0.01" value="<?php echo $Row['addPwr'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cEX">*Expiry Date: </label></td>
                <td><input type="date" name="cEX" id="cEX" value="<?php echo $Row['ExpireDateC'] ?>"  required> </td>
            </tr>
        </table>
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Edit Item" class="button"></td>
            </tr>
        </table>
    </form>
</div>
</body>