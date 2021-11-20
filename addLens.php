<?php
/**
Allows the creation of a new lens record in the database
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Add Lens</title>
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
        $target_path = $target_path . "Lens".$_POST['iCode'].".png";
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

            $lSQL = "INSERT INTO tbllens(lensCode, lensCategory, lensType, lensMaterial, lensColor, Sphere, Cylinder, Axis, Prism, Base, addPwr, Treatment) VALUES(
                    '" . strtoupper(trim($_POST['iCode'])) . "',
                    '" . strtoupper(trim($_POST['lCat'])) . "',
                    '" . strtoupper(trim($_POST['lType'])) . "',
                    '" . strtoupper(trim($_POST['lMat'])) . "',
                    '" . strtoupper(trim($_POST['lColor'])) . "',
                    '" . strtoupper(trim($_POST['lPWR'])) . "',
                    '" . strtoupper(trim($_POST['lCYL'])) . "',
                    '" . strtoupper(trim($_POST['lA'])) . "',
                    '" . strtoupper(trim($_POST['lP'])) . "',
                    '" . strtoupper(trim($_POST['lB'])) . "',
                    '" . strtoupper(trim($_POST['lAP'])) . "',
                    '" . strtoupper(trim($_POST['lTreat'])) . "')";
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
    <h1>Add Lens Record</h1>
    <h3>* Mandatory</h3>
    <h3>For adding price and quantity, please head to the Stock module after inserting this record</h3>
    <form method="post" action="" enctype="multipart/form-data">
    <table>
        <caption>General Details</caption>
        <tr>
            <td class="move"><label for="iCode">*Item Code: </label></td>
            <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" placeholder="L01" required> </td>
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
            <td><input type="text" name="iType" id="iType" value="Lens" size="7" style="background-color: lightgray" readonly> </td>
        </tr>
    </table>
    <br/>
    <table>
        <caption>Lens Details</caption>
        <tr>
            <td class="move"><label for="lCat">*Category: </label></td>
            <td><input type="text" name="lCat" id="lCat" maxlength="25" size="27" required></td>
        </tr>
        <tr>
            <td class="move"><label for="lType">*Lens Type: </label></td>
            <td><input type="text" name="lType" id="lType" maxlength="25" size="27" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="lMat">*Lens Material: </label></td>
            <td><input type="text" name="lMat" id="lMat" maxlength="25" size="27" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="lColor">*Lens Color: </label></td>
            <td><input type="color" name="lColor" id="lColor" required> </td>
        </tr>
    </table>
    <br/>
    <table>
        <caption>RX Details</caption>
        <tr>
            <td class="move"><label for="lPWR">*Sphere: </label></td>
            <td><input type="number" name="lPWR" id="lPWR" step="0.01" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="lCYL">Cylinder: </label></td>
            <td><input type="number" name="lCYL" id="lCYL" step="0.01"> </td>
        </tr>
        <tr>
            <td class="move"><label for="lA">Axis(1-180): </label></td>
            <td><input type="number" name="lA" id="lA" min="1" max="180" step="1"> </td>
        </tr>
        <tr>
            <td class="move"><label for="lP">*Prism: </label></td>
            <td><input type="number" name="lP" id="lP" step="0.1" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="lB">Base</label></td>
            <td><select name="lB" id="lB">
                    <option selected="selected" value="Up">Up</option>
                    <option value="Down">Down</option>
                </select> </td>
        </tr>
        <tr>
            <td class="move"><label for="lAP">ADD(Magnifying Power): </label></td>
            <td><input type="number" name="lAP" id="lAP" min="0.75" max="3.00" step="0.01"> </td>
        </tr>
        <tr>
            <td class="move"><label for="lTreat">Treatment: </label></td>
            <td><textarea name="lTreat" id="lTreat" cols="30" rows="5" maxlength="250" required></textarea> </td>
        </tr>
    </table>
    <table>
    <tr>
        <td><input type="submit" name="btnSub" value="Add Item" class="button"></td>
    </tr>
    </table>
    </form>
</div>
</body>
