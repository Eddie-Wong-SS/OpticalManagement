<?php
/**
 Allows for the adding of a contact fluid record to the database
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Add Soulution</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Added!', {
            body: 'Registration for the item <?php echo $_POST['iCode']; ?>  was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function readURL()
    {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("image").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    }
</script>
<?php
if($_REQUEST['btnSub'])
{
    $checkSQL = "SELECT Code FROM tblinventory WHERE Code = '" . strtoupper(trim($_POST['iCode'])) . "'";
    $checkSQLResult = mysqli_query($Link, $checkSQL);

    if(mysqli_num_rows($checkSQLResult) > 0)
    {
        echo "<script>alert('This item already exists within the database')</script>";
    }
    else
    {
        $target_path = "Images/";
        $target_path = $target_path . "Solution".$_POST['iCode'].".png";
        if(!move_uploaded_file($_FILES['image']['tmp_name'], $target_path))
        {
            echo "<script>alert('You have not yet uploaded a profile picture, please do so first');</script>";
        }
        else
        {
            $iSQL = "INSERT INTO tblinventory(Code, ItemName, ItemDesc, ItemType, imageLoc, Status) VALUES(
                    '" . strtoupper(trim($_POST['iCode'])) . "',
                    '" . strtoupper(trim($_POST['iName'])) . "',
                    '" . strtoupper(trim($_POST['iDescrip'])) . "',
                    '" . strtoupper(trim($_POST['iType'])) . "',
                    '$target_path',
                    'A')";
            $iSQLResult = mysqli_query($Link, $iSQL);

            $lSQL = "INSERT INTO tblsolution(solutionCode, Type, forcontact, ExpireDateS) VALUES(
                    '" . strtoupper(trim($_POST['iCode'])) . "',
                    '" . strtoupper(trim($_POST['sType'])) . "',
                    '" . strtoupper(trim($_POST['sFC'])) . "',
                    '" . strtoupper(trim($_POST['sEX'])) . "')";
            $lSQLResult = mysqli_query($Link, $lSQL);
            ?>
            <script>checkers();</script>

            <?php
        }
    }
}
?>
<body>
<div class="container">
    <h1>Add Solution Record</h1>
    <h3>* Mandatory</h3>
    <h3>For adding price and quantity, please head to the Stock module after inserting this record</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="iCode">*Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" placeholder="S01" required> </td>
                <td><label for="image">*Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button" required></td>
            </tr>
            <tr>
                <td class="move"><label for="iName">*Item Name: </label></td>
                <td><input type="text" name="iName" id="iCode" maxlength="50" size="52" required> </td>
                <td rowspan="3" align="center"><img src="Images/no%20image%20selected.gif" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
            </tr>
            <tr>
                <td class="move"><label for="iDescrip">*Item Description</label></td>
                <td><textarea name="iDescrip" id="iDescrip" cols="30" rows="5" maxlength="100" title="Maximum 50 characters" required></textarea> </td>
            </tr>
            <tr>
                <td class="move"><label for="iType">Item Type: </label></td>
                <td><input type="text" name="iType" id="iType" value="Solution" size="7" style="background-color: lightgray" readonly> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Solution Details</caption>
            <tr>
                <td class="move"><label for="sType">*Type: </label></td>
                <td><input type="text" name="sType" id="sType" maxlength="15" size="17" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="sFC">*For Contact Type: </label></td>
                <td><input type="text" name="sFC" id="sFC" size="22" maxlength="20" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="sEX">*Epiry Date: </label></td>
                <td><input type="date" name="sEX" id="sEX" required></td>
            </tr>
        </table>
        <br/>
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Add Item" class="button"></td>
            </tr>
        </table>
    </form>
</div>
</body>
