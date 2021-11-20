<?php
/**
 Allows the viewing and editing of contact prescription
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
            body: 'Modification of the patient with IC  <?php echo $_POST['cIC']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Contact Prescription</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $EditContactRecord = "UPDATE tblconmedrec SET CustIC = '".strtoupper(trim($_POST['cIC']))."',
                                                    checkDate = '".strtoupper(trim($_POST['cCheck']))."',
                                                    checkBy = '".strtoupper(trim($_POST['cDoc']))."',
                                                    Eye = 'OD',
                                                    Pwr = '".strtoupper(trim($_POST['cdPWR']))."',
                                                    BC = '".strtoupper(trim($_POST['cdBC']))."',
                                                    Dia = '".strtoupper(trim($_POST['cdD']))."',
                                                    Cyl = '".strtoupper(trim($_POST['cdYL']))."',
                                                    Axis = '".strtoupper(trim($_POST['cdA']))."',
                                                    addPwr = '".strtoupper(trim($_POST['cdADD']))."',
                                                    expiredate = '".strtoupper(trim($_POST['cEx']))."'
                                                    WHERE CustIC = '".strtoupper(trim($_GET['Id']))."' AND Eye = 'OD' AND checkDate = '".strtoupper(trim($_GET['date']))."'
                                                    ";
    $editPatient = mysqli_query($Link,$EditContactRecord);

    $EditContactRecord = "UPDATE tblconmedrec SET CustIC = '".strtoupper(trim($_POST['cIC']))."',
                                                    checkDate = '".strtoupper(trim($_POST['cCheck']))."',
                                                    checkBy = '".strtoupper(trim($_POST['cDoc']))."',
                                                    Eye = 'OS',
                                                    Pwr = '".strtoupper(trim($_POST['crPWR']))."',
                                                    BC = '".strtoupper(trim($_POST['crBC']))."',
                                                    Dia = '".strtoupper(trim($_POST['crD']))."',
                                                    Cyl = '".strtoupper(trim($_POST['crYL']))."',
                                                    Axis = '".strtoupper(trim($_POST['crA']))."',
                                                    addPwr = '".strtoupper(trim($_POST['crADD']))."',
                                                    expiredate = '".strtoupper(trim($_POST['cEx']))."'
                                                    WHERE CustIC = '".strtoupper(trim($_GET['Id']))."' AND Eye = 'OS' AND checkDate = '".strtoupper(trim($_GET['date']))."'
                                                    ";
    $editPatient = mysqli_query($Link,$EditContactRecord);
    if(!$editPatient)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
    else
    {?>
        <script>checkers();</script>
        <?php
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblconmedrec WHERE CustIC = '".$_GET['Id']."' AND checkDate = '".$_GET['date']."' AND Eye = 'OD'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
    }

    $SQL = "SELECT * FROM tblconmedrec WHERE CustIC = '".$_GET['Id']."' AND checkDate = '".$_GET['date']."' AND Eye = 'OS'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $RowInfo = mysqli_fetch_array($Result);
    }
}
?>

<body>
<div class="container">
    <?php if($_SESSION['log'] == 'a' || $_SESSION['log'] == 's')
        {?>
    <h1>Edit Contact Prescription</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <div align="center">
            <table>
                <caption>General Details</caption>
                <tr>
                    <td class="move"><label for="cCode">Record Code: </label></td>
                    <td><input type="text" name="cCode" id="cCode" size="12" style="background-color: lightgray" value="<?php echo $Row['CCode'];?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cIC">Patient IC: </label></td>
                    <td><input type="text" name="cIC" id="cIC" value="<?php echo $Row['CustIC'];?>" style="background-color: lightgray" size="52" readonly </td>
                </tr>
                <tr>
                    <td class="move"><label for="cDoc">*Prescribed By: </label></td>
                    <td><input type="text" name="cDoc" id="cDoc" value="<?php echo $Row['checkBy'];?>" maxlength="50" size="52" placeholder="Enter optometrist's name" required ></td>
                </tr>
                <tr>
                    <td class="move"><label for="cCheck">*Check Date: </label></td>
                    <td><input type="date" name="cCheck" id="cCheck" value="<?php echo $Row['checkDate'];?>" max="<?php echo $Mtime; ?>" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="cEx">*Expiration Date: </label></td>
                    <td><input type="date" name="cEx" id="cEx" value="<?php echo $Row['expireDate'];?>" min="<?php echo $Mtime; ?>" required></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Dexter</caption>
                <tr>
                    <td class="move"><label for="cdPWR">*PWR(Power): </label></td>
                    <td><input type="number" name="cdPWR" id="cdPWR" step="0.01" value="<?php echo $Row['Pwr'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdBC">*BC(Base Curve): </label></td>
                    <td><input type="number" name="cdBC" id="cdBC" step="0.1" min="0" value="<?php echo $Row['BC'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdD">*DIA(Diameter): </label></td>
                    <td><input type="number" name="cdD" id="cdD" step="0.1" min="0" value="<?php echo $Row['Dia'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdYL">*CYL(Cylinder): </label></td>
                    <td><input type="number" name="cdYL" id="cdYL" step="0.01" value="<?php echo $Row['Cyl'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdA">*Axis(In Degrees): </label></td>
                    <td><input type="number" name="cdA" id="cdA" min="0" step="1" value="<?php echo $Row['Axis'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="cdADD">*ADD(Add Power): </label></td>
                    <td><input type="number" name="cdADD" id="cdADD" step="0.01" value="<?php echo $Row['addPwr'];?>" required> </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Sinister</caption>
                <tr>
                    <td class="move"><label for="crPWR">*PWR(Power): </label></td>
                    <td><input type="number" name="crPWR" id="crPWR" step="0.01" value="<?php echo $RowInfo['Pwr'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crBC">*BC(Base Curve): </label></td>
                    <td><input type="number" name="crBC" id="crBC" step="0.1" min="0" value="<?php echo $RowInfo['BC'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crD">*DIA(Diameter): </label></td>
                    <td><input type="number" name="crD" id="crD" step="0.1" min="0" value="<?php echo $RowInfo['Dia'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crYL">*CYL(Cylinder): </label></td>
                    <td><input type="number" name="crYL" id="crYL" step="0.01" value="<?php echo $RowInfo['Cyl'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crA">*Axis(In Degrees): </label></td>
                    <td><input type="number" name="crA" id="crA" min="0" step="1" value="<?php echo $RowInfo['Axis'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="crADD">*ADD(Add Power): </label></td>
                    <td><input type="number" name="crADD" id="crADD" step="0.01" value="<?php echo $RowInfo['addPwr'];?>" required> </td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="btnSub" value="Edit" class="button">
        </div>
    </form>
    <?php }
    else
        { ?>
    <div align="center">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="cCode">Record Code: </label></td>
                <td><input type="text" name="cCode" id="cCode" size="12" style="background-color: lightgray" value="<?php echo $Row['CCode'];?>" readonly> </td>
            </tr>
            <tr>
                <td class="move"><label for="cIC">Patient IC: </label></td>
                <td><input type="text" name="cIC" id="cIC" value="<?php echo $Row['CustIC'];?>" style="background-color: lightgray" size="52" readonly </td>
            </tr>
            <tr>
                <td class="move"><label for="cDoc">*Prescribed By: </label></td>
                <td><?php echo $Row['checkBy'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cCheck">*Check Date: </label></td>
                <td><?php echo $Row['checkDate'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cEx">*Expiration Date: </label></td>
                <td><?php echo $Row['expireDate'];?></td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Oculus Dexter</caption>
            <tr>
                <td style="width: 65%" class="move"><label for="cdPWR">*PWR(Power): </label></td>
                <td><?php echo $Row['Pwr'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdBC">*BC(Base Curve): </label></td>
                <td><?php echo $Row['BC'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdD">*DIA(Diameter): </label></td>
                <td><?php echo $Row['Dia'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdYL">*CYL(Cylinder): </label></td>
                <td><?php echo $Row['Cyl'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdA">*Axis(In Degrees): </label></td>
                <td><?php echo $Row['Axis']; ?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdADD">*ADD(Add Power): </label></td>
                <td><?php echo $Row['addPwr'];?></td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Oculus Sinister</caption>
            <tr>
                <td class="move" style="width: 65%"><label for="crPWR">*PWR(Power): </label></td>
                <td><?php echo $RowInfo['Pwr'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crBC">*BC(Base Curve): </label></td>
                <td><?php echo $RowInfo['BC'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crD">*DIA(Diameter): </label></td>
                <td><?php echo $RowInfo['Dia'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crYL">*CYL(Cylinder): </label></td>
                <td><?php echo $RowInfo['Cyl'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crA">*Axis(In Degrees): </label></td>
                <td><?php echo $RowInfo['Axis'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crADD">*ADD(Add Power): </label></td>
                <td><?php echo $RowInfo['addPwr'];?> </td>
            </tr>
        </table>
    </div>
    <?php    }  ?>
</div>
</body>
