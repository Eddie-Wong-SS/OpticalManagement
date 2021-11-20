<?php
/**
 Allows the editing and viewing of glasses prescription
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
            body: 'Modification of the patient with IC  <?php echo $_POST['gIC']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Edit Glass Prescription</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{

    $EditContactRecord = "UPDATE tblglassmedrec SET CustIC = '".strtoupper(trim($_POST['gIC']))."',
                                                    checkDate = '".strtoupper(trim($_POST['gCheck']))."',
                                                    checkBy = '".strtoupper(trim($_POST['gDoc']))."',
                                                    Eye = 'OD',
                                                    Sphere = '".strtoupper(trim($_POST['gdSphere']))."',
                                                    Cylinder = '".strtoupper(trim($_POST['gdYL']))."',
                                                    Axis = '".strtoupper(trim($_POST['gdA']))."',
                                                    Prism = '".strtoupper(trim($_POST['gdP']))."',
                                                    Base = '".strtoupper(trim($_POST['gdB']))."',
                                                    addPwr = '".strtoupper(trim($_POST['gdAP']))."',
                                                    expiredate = '".strtoupper(trim($_POST['gEx']))."',
                                                    Remark = '".strtoupper(trim($_POST['gRem']))."'
                                                    WHERE CustIC = '".strtoupper(trim($_GET['Id']))."' AND Eye = 'OD' AND checkDate = '".strtoupper(trim($_GET['date']))."'
                                                    ";
    $editPatient = mysqli_query($Link,$EditContactRecord);

    $EditContactRecord = "UPDATE tblglassmedrec SET CustIC = '".strtoupper(trim($_POST['gIC']))."',
                                                    checkDate = '".strtoupper(trim($_POST['gCheck']))."',
                                                    checkBy = '".strtoupper(trim($_POST['gDoc']))."',
                                                    Eye = 'OS',
                                                    Sphere = '".strtoupper(trim($_POST['gsSphere']))."',
                                                    Cylinder = '".strtoupper(trim($_POST['gsYL']))."',
                                                    Axis = '".strtoupper(trim($_POST['gsA']))."',
                                                    Prism = '".strtoupper(trim($_POST['gsP']))."',
                                                    Base = '".strtoupper(trim($_POST['gsB']))."',
                                                    addPwr = '".strtoupper(trim($_POST['gsAP']))."',
                                                    expiredate = '".strtoupper(trim($_POST['gEx']))."',
                                                    Remark = '".strtoupper(trim($_POST['gRem']))."'
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
    $SQL = "SELECT * FROM tblglassmedrec WHERE CustIC = '".$_GET['Id']."' AND checkDate = '".$_GET['date']."' AND Eye = 'OD'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $ddef = $Row['Base'];
        if($ddef != "UP")
        {
            $ddef = "DOWN";
            $dndef = "UP";
        }
        else $dndef = "DOWN";
    }

    $SQL = "SELECT * FROM tblglassmedrec WHERE CustIC = '".$_GET['Id']."' AND checkDate = '".$_GET['date']."' AND Eye = 'OS'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $RowInfo = mysqli_fetch_array($Result);
        $rdef = $RowInfo['Base'];
        if($rdef != "UP")
        {
            $rdef = "DOWN";
            $rndef = "UP";
        }
        else $rndef = "DOWN";

    }

}
?>

<body>
<div class="container">
    <?php if($_SESSION['log'] == 'a' || $_SESSION['log'] == 's')
        {?>
    <h1>Edit Glass Prescription</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <div align="center">
            <table>
                <caption>General Details</caption>
                <tr>
                    <td class="move"><label for="gCode">Record Code: </label></td>
                    <td><input type="text" name="gCode" id="gCode" size="12" style="background-color: lightgray" value="<?php echo $Row['GCode'];?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gIC">Patient IC: </label></td>
                    <td><input type="text" name="gIC" id="gIC" value="<?php echo $Row['CustIC'];?>" style="background-color: lightgray" size="52" readonly ></td>
                </tr>
                <tr>
                    <td class="move"><label for="gDoc">*Prescribed By: </label></td>
                    <td><input type="text" name="gDoc" id="gDoc" maxlength="50" size="52" value="<?php echo $Row['checkBy'];?>" placeholder="Enter optometrist's name" required ></td>
                </tr>
                <tr>
                    <td class="move"><label for="gCheck">*Check Date: </label></td>
                    <td><input type="date" name="gCheck" id="gCheck" value="<?php echo $Row['checkDate'];?>" max="<?php echo $Mtime; ?>" required></td>
                </tr>
                <tr>
                    <td class="move"><label for="gEx">*Expiration Date: </label></td>
                    <td><input type="date" name="gEx" id="gEx" value="<?php echo $Row['expireDate'];?>" min="<?php echo $Mtime; ?>" required></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Dexter</caption>
                <tr>
                    <td class="move"><label for="gdSphere">*Sphere: </label></td>
                    <td><input type="number" name="gdSphere" id="gdSphere" step="0.01" value="<?php echo $Row['Sphere'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdYL">Cylinder: </label></td>
                    <td><input type="number" name="gdYL" id="gdYL" step="0.01" value="<?php echo $Row['Cylinder'];?>"> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdA">Axis(1-180): </label></td>
                    <td><input type="number" name="gdA" id="gdA" min="1" max="180" step="1" value="<?php echo $Row['Axis'];?>"> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdP">*Prism: </label></td>
                    <td><input type="number" name="gdP" id="gdP" step="0.1" value="<?php echo $Row['Prism'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdB">Base</label></td>
                    <td><select name="gdB" id="gdB">
                            <option selected="selected" value="<?php echo $ddef ?>"><?php echo $ddef ?></option>
                            <option value="<?php echo $dndef ?>"><?php echo $dndef ?></option>
                        </select> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gdAP">ADD(Magnifying Power): </label></td>
                    <td><input type="number" name="gdAP" id="gdAP" min="0.75" max="3.00" step="0.01" value="<?php echo $Row['addPwr'];?>"> </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Sinister</caption>
                <tr>
                    <td class="move"><label for="gsSphere">*Sphere: </label></td>
                    <td><input type="number" name="gsSphere" id="gsSphere" step="0.01" value="<?php echo $RowInfo['Sphere'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsYL">Cylinder: </label></td>
                    <td><input type="number" name="gsYL" id="gsYL" step="0.01" value="<?php echo $RowInfo['Cylinder'];?>" > </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsA">Axis(1-180): </label></td>
                    <td><input type="number" name="gsA" id="gsA" min="1" max="180" step="1" value="<?php echo $RowInfo['Axis'];?>"> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsP">*Prism: </label></td>
                    <td><input type="number" name="gsP" id="gsP" step="0.1" value="<?php echo $RowInfo['Prism'];?>" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsB">Base</label></td>
                    <td><select name="gsB" id="gsB">
                            <option selected="selected" value="<?php echo $rdef ?>"><?php echo $rdef ?></option>
                            <option value="<?php echo $rndef ?>"><?php echo $rndef ?></option>
                        </select> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsAP">ADD(Magnifying Power): </label></td>
                    <td><input type="number" name="gsAP" id="gsAP" min="0.75" max="3.00" step="0.01" value="<?php echo $RowInfo['addPwr'];?>"> </td>
                </tr>
            </table>
            <br/>
            <table align="center" cellpadding="6" border = "0">
                <caption>Comments(Optional)</caption>
                <tr>
                    <td class="move" width="55%">Remarks</td>
                    <td><textarea name="gRem" id="gRem" rows="5" cols="52" maxlength="500"><?php echo $RowInfo['Remark'] ?></textarea> </td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="btnSub" value="Edit" class="button">
        </div>
    </form>
    <?php    }
    else {?>
        <div align="center">
            <table>
                <caption>General Details</caption>
                <tr>
                    <td class="move"><label for="gCode">Record Code: </label></td>
                    <td><input type="text" name="gCode" id="gCode" size="12" style="background-color: lightgray" value="<?php echo $Row['GCode'];?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move" style="width: 65%"><label for="gIC">Patient IC: </label></td>
                    <td><?php echo $Row['CustIC'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gDoc">*Prescribed By: </label></td>
                    <td><?php echo $Row['checkBy'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gCheck">*Check Date: </label></td>
                    <td><?php echo $Row['checkDate'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gEx">*Expiration Date: </label></td>
                    <td><?php echo $Row['expireDate'];?></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Dexter</caption>
                <tr>
                    <td class="move" style="width: 65%;"><label for="gdSphere">*Sphere: </label></td>
                    <td><?php echo $Row['Sphere'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gdYL">Cylinder: </label></td>
                    <td><?php echo $Row['Cylinder'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gdA">Axis(1-180): </label></td>
                    <td><?php echo $Row['Axis'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gdP">*Prism: </label></td>
                    <td><?php echo $Row['Prism'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gdB">Base</label></td>
                    <td><?php echo $ddef ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gdAP">ADD(Magnifying Power): </label></td>
                    <td><?php echo $Row['addPwr'];?></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Oculus Sinister</caption>
                <tr>
                    <td class="move" style="width: 65%;"><label for="gsSphere">*Sphere: </label></td>
                    <td><?php echo $RowInfo['Sphere'];?> </td>
                </tr>
                <tr>
                    <td class="move"><label for="gsYL">Cylinder: </label></td>
                    <td><?php echo $RowInfo['Cylinder'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gsA">Axis(1-180): </label></td>
                    <td><?php echo $RowInfo['Axis'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gsP">*Prism: </label></td>
                    <td><?php echo $RowInfo['Prism'];?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gsB">Base</label></td>
                    <td><?php echo $rdef ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="gsAP">ADD(Magnifying Power): </label></td>
                    <td><?php echo $RowInfo['addPwr'];?></td>
                </tr>
            </table>
            <br/>
            <table align="center" cellpadding="6" border = "0">
                <caption>Comments(Optional)</caption>
                <tr>
                    <td class="move" width="55%">Remarks</td>
                    <td><?php echo $RowInfo['Remark']; ?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
</div>
</body>