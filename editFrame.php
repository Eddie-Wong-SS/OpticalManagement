<?php
/**
 Allows the viewing and editing of frame record in detail
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
            body: 'Modification of the frame record <?php echo $_POST['iCode']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Frame Record</title>
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
        $target_path = $target_path . "Frame".$_POST['iCode'].".png";
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

    $EditFrameRecord = "UPDATE tblframes SET frameCode = '".strtoupper(trim($_POST['iCode']))."',
                                          frameMaterial = '".strtoupper(trim($_POST['fMat']))."',
                                          Color = '".strtoupper(trim($_POST['fColor']))."',
                                          Shape = '".strtoupper(trim($_POST['fShape']))."',
                                          Hinge = '".strtoupper(trim($_POST['fHinge']))."'
                                          WHERE frameCode = '".strtoupper(trim($_GET['Id']))."'
                                          ";
    echo $EditFrameRecord;
    $editFrame = mysqli_query($Link, $EditFrameRecord);
    if(!$editInventory || ! $editFrame)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
    else
    {?>
        <script>checkers();</script>
        <?php
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblinventory, tblframes WHERE tblinventory.Code = tblframes.frameCode AND Code = '".$_GET['Id']."'";
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
    <h1>Add Frame Record</h1>
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
            <caption>Frame Details</caption>
            <tr>
                <td class="move"><label for="fMat">*Frame Material: </label></td>
                <td><input type="text" name="fMat" id="fMat" maxlength="25" size="27" value="<?php echo $Row['frameMaterial'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="fColor">*Frame Color: </label></td>
                <td><input type="color" name="fColor" id="fColor" value="<?php echo $Row['Color'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="fShape">*Frame Shape: </label></td>
                <td><input type="text" name="fShape" id="fShape" size="12" maxlength="10" value="<?php echo $Row['Shape'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="fHinge">*Hinge: </label></td>
                <td><input type="text" name="fHinge" id="fHinge" size="22" maxlength="20" value="<?php echo $Row['Hinge'] ?>" required></td>
            </tr>
        </table>
        <br/>
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Edit Item" class="button"></td>
            </tr>
        </table>
    </form>
</div>
</body>

