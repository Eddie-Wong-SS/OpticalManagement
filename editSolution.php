<?php
/**
 Allows the viewing and editing of contact fluid record
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
            body: 'Modification of the contact fluid record <?php echo $_POST['iCode']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Solution Record</title>
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
        $target_path = $target_path . "Solution".$_POST['iCode'].".png";
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

    $EditSolutionRecord = "UPDATE tblsolution SET solutionCode = '".strtoupper(trim($_POST['iCode']))."',
                                          Type = '".strtoupper(trim($_POST['sType']))."',
                                          forcontact = '".strtoupper(trim($_POST['sFC']))."',
                                          ExpireDateS = '".strtoupper(trim($_POST['sEX']))."'
                                          WHERE solutionCode = '".strtoupper(trim($_GET['Id']))."'
                                          ";
    echo $EditSolutionRecord;
    $editSolution = mysqli_query($Link, $EditSolutionRecord);
    if(!$editInventory || ! $editSolution)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
    else
    {?>
        <script>checkers();</script>
        <?php
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblinventory, tblsolution WHERE tblinventory.Code = tblsolution.solutionCode AND Code = '".$_GET['Id']."'";
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
    <h1>Edit Solution Record</h1>
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
            <caption>Solution Details</caption>
            <tr>
                <td class="move"><label for="sType">*Type: </label></td>
                <td><input type="text" name="sType" id="sType" maxlength="15" size="17" value="<?php echo $Row['Type'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="sFC">*For Contact Type: </label></td>
                <td><input type="text" name="sFC" id="sFC" size="22" maxlength="20" value="<?php echo $Row['forcontact'] ?>" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="sEX">*Epiry Date: </label></td>
                <td><input type="date" name="sEX" id="sEX" value="<?php echo $Row['ExpireDateS'] ?>" required></td>
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
