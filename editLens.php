<?php
/**
 Allows the user to view and edit lens records in detail
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
            body: 'Modification of the lens record <?php echo $_POST['iCode']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Lens Record</title>
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
        $target_path = $target_path . "Lens".$_POST['iCode'].".png";
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

    $EditLensRecord = "UPDATE tbllens SET lensCode = '".strtoupper(trim($_POST['iCode']))."',
                                          lensCategory = '".strtoupper(trim($_POST['lCat']))."',
                                          lensType = '".strtoupper(trim($_POST['lType']))."',
                                          lensMaterial = '".strtoupper(trim($_POST['lMat']))."',
                                          lensColor = '".strtoupper(trim($_POST['lColor']))."',
                                          Sphere = '".strtoupper(trim($_POST['lPWR']))."',
                                          Cylinder = '".strtoupper(trim($_POST['lCYL']))."',
                                          Axis = '".strtoupper(trim($_POST['lA']))."',
                                          Prism = '".strtoupper(trim($_POST['lP']))."',
                                          Base = '".strtoupper(trim($_POST['lB']))."',
                                          addPwr = '".strtoupper(trim($_POST['lAP']))."',
                                          Treatment = '".strtoupper(trim($_POST['lTreat']))."'
                                          WHERE lensCode = '".strtoupper(trim($_GET['Id']))."'
                                          ";
    $editLens = mysqli_query($Link, $EditLensRecord);
    if(!$editInventory || ! $editLens)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
    else
    {?>
        <script>checkers();</script>
        <?php
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblinventory, tbllens WHERE tblinventory.Code = tbllens.lensCode AND Code = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);

    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $default = $Row['imageLoc'];
        if($default == "")
        {
            $default = "Images/no image available.png";
        }
        $defBase = $Row['Base'];
        if($defBase == "UP")
        {
            $ndefBase = "DOWN";

        }
        else{
            $ndefBase = "UP";

        }

    }
}
?>

<body>
<div class="container">
    <h1>Edit Lens Record</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="iCode">*Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" value="<?php echo $Row['Code'] ?>" style="background-color: lightgray" readonly> </td>
                <td><label for="image">*Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button" ></td>
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
            <caption>Lens Details</caption>
            <tr>
                <td class="move"><label for="lCat">*Category: </label></td>
                <td><input type="text" name="lCat" id="lCat" value="<?php echo $Row['lensCategory'] ?>" maxlength="25" size="27" required></td>
            </tr>
            <tr>
                <td class="move"><label for="lType">*Lens Type: </label></td>
                <td><input type="text" name="lType" id="lType" value="<?php echo $Row['lensType'] ?>" maxlength="25" size="27" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="lMat">*Lens Material: </label></td>
                <td><input type="text" name="lMat" id="lMat" value="<?php echo $Row['lensMaterial'] ?>" maxlength="25" size="27" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="lColor">*Lens Color: </label></td>
                <td><input type="color" name="lColor" id="lColor" value="<?php echo $Row['lensColor'] ?>" required> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>RX Details</caption>
            <tr>
                <td class="move"><label for="lPWR">*Sphere: </label></td>
                <td><input type="number" name="lPWR" id="lPWR" step="0.01" value="<?php echo $Row['Sphere'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="lCYL">Cylinder: </label></td>
                <td><input type="number" name="lCYL" id="lCYL" step="0.01" value="<?php echo $Row['Cylinder'] ?>" > </td>
            </tr>
            <tr>
                <td class="move"><label for="lA">Axis(1-180): </label></td>
                <td><input type="number" name="lA" id="lA" min="1" max="180" step="1" value="<?php echo $Row['Axis'] ?>"> </td>
            </tr>
            <tr>
                <td class="move"><label for="lP">*Prism: </label></td>
                <td><input type="number" name="lP" id="lP" step="0.1" value="<?php echo $Row['Prism'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="lB">Base</label></td>
                <td><select name="lB" id="lB">
                        <option selected="selected" value="<?php echo $defBase ?>"><?php echo $defBase ?></option>
                        <option value="<?php echo $ndefBase ?>"><?php echo $ndefBase ?></option>
                    </select> </td>
            </tr>
            <tr>
                <td class="move"><label for="lAP">ADD(Magnifying Power): </label></td>
                <td><input type="number" name="lAP" id="lAP" min="0.75" max="3.00" step="0.01" value="<?php echo $Row['addPwr'] ?>"> </td>
            </tr>
            <tr>
                <td class="move"><label for="lTreat">Treatment: </label></td>
                <td><textarea name="lTreat" id="lTreat" cols="30" rows="5" maxlength="250" required><?php echo $Row['Treatment'] ?></textarea> </td>
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

