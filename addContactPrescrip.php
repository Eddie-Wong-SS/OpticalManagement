<?php
/**
 Allows the adding of contact prescription
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    var count = 1;
    var count2 = 1;

    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Added!', {
            body: 'Registration of the contact prescription for patient <?php echo $_GET['Id']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

</script>

<title>Add Contact Prescription</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSub'])
{
    $SQL = "SELECT StaffName FROM tblstaff WHERE StaffName LIKE '%".trim($_POST['cDoc'])."%' AND Position = 'OPTOMETRIST' ";
    $Result = mysqli_query($Link, $SQL);

    if (mysqli_num_rows($Result) > 0)
    {
        $addContactSQL = "INSERT INTO tblconmedrec(CustIC, CCode, checkDate, checkBy, Eye, Pwr, BC, Dia, Cyl, Axis, addPwr, expireDate, Status) VALUES(
                      '" . strtoupper(trim($_GET['Id'])) . "',
                      '" . strtoupper(trim($_POST['cCode'])) . "',
                      '" . strtoupper(trim($_POST['cCheck'])) . "',
                      '" . strtoupper(trim($_POST['cDoc'])) . "',
                      'OD',
                      '" . strtoupper(trim($_POST['cdPWR'])) . "',
                      '" . strtoupper(trim($_POST['cdBC'])) . "',
                      '" . strtoupper(trim($_POST['cdD'])) . "',
                      '" . strtoupper(trim($_POST['cdYL'])) . "',
                      '" . strtoupper(trim($_POST['cdA'])) . "',
                      '" . strtoupper(trim($_POST['cdADD'])) . "',
                      '" . strtoupper(trim($_POST['cEx'])) . "',
                      'A')";
        $addContactSQLResult = mysqli_query($Link, $addContactSQL);

        $addContactSQL = "INSERT INTO tblconmedrec(CustIC, CCode, checkDate, checkBy, Eye, Pwr, BC, Dia, Cyl, Axis, addPwr, expireDate, Status) VALUES(
                      '" . strtoupper(trim($_GET['Id'])) . "',
                      '" . strtoupper(trim($_POST['cCode'])) . "',
                      '" . strtoupper(trim($_POST['cCheck'])) . "',
                      '" . strtoupper(trim($_POST['cDoc'])) . "',
                      'OS',
                      '" . strtoupper(trim($_POST['crPWR'])) . "',
                      '" . strtoupper(trim($_POST['crBC'])) . "',
                      '" . strtoupper(trim($_POST['crD'])) . "',
                      '" . strtoupper(trim($_POST['crYL'])) . "',
                      '" . strtoupper(trim($_POST['crA'])) . "',
                      '" . strtoupper(trim($_POST['crADD'])) . "',
                      '" . strtoupper(trim($_POST['cEx'])) . "',
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
    <h1>Add Contact Prescription</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <div align="center">
            <table>
                <caption>General Details</caption>
                <tr>
                    <td class="move"><label for="cCode">*Record Code: </label></td>
                    <td><input type="text" name="cCode" id="cCode" maxlength="10" size="12" title="Maximum length of 10 characters" placeholder="C00" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cName">Patient: </label></td>
                    <td><input type="text" name="cName" id="cName" value="<?php echo $Row['CustName'];?>" style="background-color: lightgray" size="52" readonly </td>
                </tr>
                <tr>
                    <td class="move"><label for="cDoc">*Prescribed By: </label></td>
                    <td><input type="text" name="cDoc" id="cDoc" maxlength="50" size="52" placeholder="Enter optometrist's name" required ></td>
                </tr>
                <tr>
                    <td class="move"><label for="cCheck">*Check Date: </label></td>
                    <td><input type="date" name="cCheck" id="cCheck" max="<?php echo $Mtime; ?>" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="cEx">*Expiration Date: </label></td>
                    <td><input type="date" name="cEx" id="cEx" min="<?php echo $Mtime; ?>" required></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Dexter</caption>
                <tr>
                    <td class="move"><label for="cdPWR">*PWR(Power): </label></td>
                    <td><input type="number" name="cdPWR" id="cdPWR" step="0.01" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdBC">*BC(Base Curve): </label></td>
                    <td><input type="number" name="cdBC" id="cdBC" step="0.1" min="0" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdD">*DIA(Diameter): </label></td>
                    <td><input type="number" name="cdD" id="cdD" step="0.1" min="0" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdYL">*CYL(Cylinder): </label></td>
                    <td><input type="number" name="cdYL" id="cdYL" step="0.01" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdA">*Axis(In Degrees): </label></td>
                    <td><input type="number" name="cdA" id="cdA" min="0" step="1" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdADD">*ADD(Add Power): </label></td>
                    <td><input type="number" name="cdADD" id="cdADD" step="0.01" required> </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Sinister</caption>
                <tr>
                    <td class="move"><label for="crPWR">*PWR(Power): </label></td>
                    <td><input type="number" name="crPWR" id="crPWR" step="0.01" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crBC">*BC(Base Curve): </label></td>
                    <td><input type="number" name="crBC" id="crBC" step="0.1" min="0" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crD">*DIA(Diameter): </label></td>
                    <td><input type="number" name="crD" id="crD" step="0.1" min="0" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crYL">*CYL(Cylinder): </label></td>
                    <td><input type="number" name="crYL" id="crYL" step="0.01" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crA">*Axis(In Degrees): </label></td>
                    <td><input type="number" name="crA" id="crA" min="0" step="1" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crADD">*ADD(Add Power): </label></td>
                    <td><input type="number" name="crADD" id="crADD" step="0.01" required> </td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="btnSub" value="Register" class="button">
        </div>
    </form>
</div>
</body>
