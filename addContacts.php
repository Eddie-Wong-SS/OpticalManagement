<?php
/**
 Allows the creation of a new contact lens record in the database
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Add Contacts</title>
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
        $target_path = $target_path . "Contact".$_POST['iCode'].".png";
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

            $lSQL = "INSERT INTO tblcontacts(contactCode, conCategory, conType, conMaterial, conColor, Sphere, BC, Dia, Cyl, Axis, addPwr, ExpireDateC) VALUES(
                    '" . strtoupper(trim($_POST['iCode'])) . "',
                    '" . strtoupper(trim($_POST['cCat'])) . "',
                    '" . strtoupper(trim($_POST['cType'])) . "',
                    '" . strtoupper(trim($_POST['cMat'])) . "',
                    '" . strtoupper(trim($_POST['cColor'])) . "',
                    '" . strtoupper(trim($_POST['cPWR'])) . "',
                    '" . strtoupper(trim($_POST['cBC'])) . "',
                    '" . strtoupper(trim($_POST['cD'])) . "',
                    '" . strtoupper(trim($_POST['cYL'])) . "',
                    '" . strtoupper(trim($_POST['cA'])) . "',
                    '" . strtoupper(trim($_POST['cADD'])) . "',
                    '" . strtoupper(trim($_POST['cEX'])) . "')";
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
    <h1>Add Contact Lens Record</h1>
    <h3>* Mandatory</h3>
    <h3>For adding price and quantity, please head to the Stock module after inserting this record</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="iCode">*Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" placeholder="C01" required> </td>
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
                <td><input type="text" name="iType" id="iType" value="Contact" size="7" style="background-color: lightgray" readonly> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Contact Details</caption>
            <tr>
                <td class="move"><label for="cCat">*Category: </label></td>
                <td><input type="text" name="cCat" id="cCat" maxlength="25" size="27" required></td>
            </tr>
            <tr>
                <td class="move"><label for="cType">*Contact Type: </label></td>
                <td><input type="text" name="cType" id="cType" maxlength="25" size="27" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cMat">*Contact Material: </label></td>
                <td><input type="text" name="cMat" id="cMat" maxlength="25" size="27" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cColor">*Contact Color: </label></td>
                <td><input type="color" name="cColor" id="cColor" required> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>RX Details</caption>
            <tr>
                <td class="move"><label for="cPWR">*PWR(Power): </label></td>
                <td><input type="number" name="cPWR" id="cPWR" step="0.01" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cBC">*BC(Base Curve): </label></td>
                <td><input type="number" name="cBC" id="cBC" step="0.1" min="0" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cD">*DIA(Diameter): </label></td>
                <td><input type="number" name="cD" id="cD" step="0.1" min="0" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cYL">*CYL(Cylinder): </label></td>
                <td><input type="number" name="cYL" id="cYL" step="0.01" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cA">*Axis(In Degrees): </label></td>
                <td><input type="number" name="cA" id="cA" min="0" step="1" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cADD">*ADD(Add Power): </label></td>
                <td><input type="number" name="cADD" id="cADD" step="0.01" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="cEX">*Expiry Date: </label></td>
                <td><input type="date" name="cEX" id="cEX"  required> </td>
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
