<?php
/**
 Allows for the adding of glassses prescription
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    var count = 1;

    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Added!', {
            body: 'Registration of the glasses prescription for patient <?php echo $_GET['Id']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

</script>

<title>Add Glass Prescription</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSub'])
{
    $SQL = "SELECT StaffName FROM tblstaff WHERE StaffName LIKE '%".trim($_POST['cDoc'])."%' AND Position = 'OPTOMETRIST' ";
    $Result = mysqli_query($Link, $SQL);

    if (mysqli_num_rows($Result) > 0) {
        $addContactSQL = "INSERT INTO tblglassmedrec(CustIC, GCode, checkDate, checkBy, Eye, Sphere, Cylinder, Axis, Prism, Base, addPwr, expireDate, Status) VALUES(
                      '" . strtoupper(trim($_GET['Id'])) . "',
                      '" . strtoupper(trim($_POST['gCode'])) . "',
                      '" . strtoupper(trim($_POST['gCheck'])) . "',
                      '" . strtoupper(trim($_POST['gDoc'])) . "',
                      'OD',
                      '" . strtoupper(trim($_POST['gdPWR'])) . "',
                      '" . strtoupper(trim($_POST['gdYL'])) . "',
                      '" . strtoupper(trim($_POST['gdA'])) . "',
                      '" . strtoupper(trim($_POST['gdADD'])) . "',
                      '" . strtoupper(trim($_POST['gdB'])) . "',
                      '" . strtoupper(trim($_POST['gdAP'])) . "',
                      '" . strtoupper(trim($_POST['gEx'])) . "',
                      'A')";
        $addContactSQLResult = mysqli_query($Link, $addContactSQL);

        $addContactSQL = "INSERT INTO tblglassmedrec(CustIC, GCode, checkDate, checkBy, Eye, Sphere, Cylinder, Axis, Prism, Base, addPwr, expireDate, Remark, Status) VALUES(
                      '" . strtoupper(trim($_GET['Id'])) . "',
                      '" . strtoupper(trim($_POST['gCode'])) . "',
                      '" . strtoupper(trim($_POST['gCheck'])) . "',
                      '" . strtoupper(trim($_POST['gDoc'])) . "',
                      'OS',
                      '" . strtoupper(trim($_POST['gsPWR'])) . "',
                      '" . strtoupper(trim($_POST['gsYL'])) . "',
                      '" . strtoupper(trim($_POST['gsA'])) . "',
                      '" . strtoupper(trim($_POST['gsADD'])) . "',
                      '" . strtoupper(trim($_POST['gsB'])) . "',
                      '" . strtoupper(trim($_POST['gsAP'])) . "',
                      '" . strtoupper(trim($_POST['gEx'])) . "',
                      '" . strtoupper(trim($_POST['gRem'])) . "',
                      'A')";
        $addContactSQLResult = mysqli_query($Link, $addContactSQL);

        ?>
        <script>checkers();</script>

        <?php
    }
    else{
        echo "<script>alert('You have entered the name of a staff member who is not an optometrist in Prescribed By');</script>";
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT CustName FROM tblcustomer WHERE CustIC = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    $Row = mysqli_fetch_array($Result);
}
?>

<body>
<div class="container">
    <h1>Add Glass Prescription</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <div align="center">
            <table>
                <caption>General Details</caption>
                <tr>
                    <td class="move"><label for="gCode">*Record Code: </label></td>
                    <td><input type="text" name="gCode" id="gCode" maxlength="10" size="12" title="Maximum length of 10 characters" placeholder="G00" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gName">Patient: </label></td>
                    <td><input type="text" name="gName" id="gName" value="<?php echo $Row['CustName'];?>" style="background-color: lightgray" size="52" readonly </td>
                </tr>
                <tr>
                    <td class="move"><label for="gDoc">*Prescribed By: </label></td>
                    <td><input type="text" name="gDoc" id="gDoc" maxlength="50" size="52" placeholder="Enter optometrist's name" required ></td>
                </tr>
                <tr>
                    <td class="move"><label for="gCheck">*Check Date: </label></td>
                    <td><input type="date" name="gCheck" id="gCheck" max="<?php echo $Mtime; ?>" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="gEx">*Expiration Date: </label></td>
                    <td><input type="date" name="gEx" id="gEx" min="<?php echo $Mtime; ?>" required></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Dexter</caption>
                <tr>
                    <td class="move"><label for="gdPWR">*Sphere: </label></td>
                    <td><input type="number" name="gdPWR" id="gdPWR" step="0.01" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdYL">Cylinder: </label></td>
                    <td><input type="number" name="gdYL" id="gdYL" step="0.01"> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdA">Axis(1-180): </label></td>
                    <td><input type="number" name="gdA" id="gdA" min="1" max="180" step="1"> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdADD">*Prism: </label></td>
                    <td><input type="number" name="gdADD" id="gdADD" step="0.1" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdB">Base</label></td>
                    <td><select name="gdB" id="gdB">
                            <option selected="selected" value="Up">Up</option>
                            <option value="Down">Down</option>
                        </select> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdAP">ADD(Magnifying Power): </label></td>
                    <td><input type="number" name="gdAP" id="gdAP" min="0.75" max="3.00" step="0.01" </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Sinister</caption>
                <tr>
                    <td class="move"><label for="gsPWR">*Sphere: </label></td>
                    <td><input type="number" name="gsPWR" id="gsPWR" step="0.01" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsYL">Cylinder: </label></td>
                    <td><input type="number" name="gsYL" id="gsYL" step="0.01" > </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsA">Axis(1-180): </label></td>
                    <td><input type="number" name="gsA" id="gsA" min="1" max="180" step="1"> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsADD">*Prism: </label></td>
                    <td><input type="number" name="gsADD" id="gsADD" step="0.1" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsB">Base</label></td>
                    <td><select name="gsB" id="gsB">
                            <option selected="selected" value="Up">Up</option>
                            <option value="Down">Down</option>
                        </select> </td>
                </tr>
                <tr class="move">
                    <td><label for="gsAP">ADD(Magnifying Power): </label></td>
                    <td><input type="number" name="gsAP" id="gsAP" min="0.75" max="3.00" step="0.01" </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Comments(Optional)</caption>
                <tr>
                    <td class="move" width="50%"><label for="gRem">Remarks: </label></td>
                    <td><textarea name="gRem" id="gRem" rows="5" cols="52" maxlength="500"></textarea> </td>
                </tr>
            </table>
            <input type="submit" name="btnSub" value="Register" class="button">
        </div>
    </form>
</div>
</body>