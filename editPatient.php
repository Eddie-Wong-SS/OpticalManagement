<?php
/**
 Allows the editing of the patient records
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
            body: 'Modification of the patient <?php echo $_POST['pName']; ?> into the database was successful',
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
<title>Edit Patient</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $imgSQL = "SELECT * FROM tblcustomer WHERE  CustIC = '".$_GET['Id']."'";
    $imgSQLResult = mysqli_query($Link, $imgSQL);
    if(mysqli_num_rows($imgSQLResult) > 0) {
        $Rows = mysqli_fetch_array($imgSQLResult);
    }
    if($_FILES['image']['size'] != 0)
    {
        $target_path = "Images/";
        $target_path = $target_path . "patient".$_POST['pIC'].".png";
         move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
    }
    else  $target_path = $Rows['imageLoc'];

    $EditPatientRecord = "UPDATE tblcustomer SET CustName = '".strtoupper(trim($_POST['pName']))."',
                                                    Acctype = '".strtoupper(trim($_POST['pType']))."',
                                                    Gender = '".strtoupper(trim($_POST['pGender']))."',
                                                    Address = '".strtoupper(trim($_POST['pAdd']))."',
                                                    Phone = '".strtoupper(trim($_POST['pCNO']))."',
                                                    DOB = '".strtoupper(trim($_POST['pDOB']))."',
                                                    Email = '".strtoupper(trim($_POST['pEm']))."',
                                                    imageLoc = '$target_path'
                                                    WHERE CustIC = '".strtoupper(trim($_GET['Id']))."'
                                                    ";
    $editPatient = mysqli_query($Link,$EditPatientRecord);
    if(!$editPatient)echo '<script type="text/javascript">alert("Cannot connect to database");</script>';
    else
    {?>
        <script>checkers();</script>
        <?php
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblcustomer WHERE CustIC = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $default = $Row['imageLoc'];
        if($default == "")
        {
            $default = "Images/no image available.png";
        }
        $defGen = $Row['Gender'];
        if($defGen == "M")
        {
            $ndefGen = "FEMALE";
            $ncdefGen = "F";
            $cdefGen = "MALE";
        }
        else{
            $ndefGen = "MALE";
            $ncdefGen = "M";
            $cdefGen = "FEMALE";
        }

    }
}
?>

<body>
<div class="container">
    <?php if($_GET['type'] == 'C')
    { ?>
    <body>
    <div class="container">
        <h1>Your Profile</h1>
        <br/>
        <div align="center">
            <table>
                <caption>Your Details</caption>
                <tr>
                    <td class="move"><label for="pName">*Patient Name: </label></td>
                    <td><?php echo $Row['CustName']; ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="pIC">*Patient IC: </label></td>
                    <td><?php echo $Row['CustIC']; ?></td>
                    <td rowspan="4" align="center"><img src="<?php echo $default; ?>" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
                </tr>
                <tr>
                    <td class="move"><label for="pType">*Account Type: </label></td>
                    <td><?php echo $Row['AccType']; ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="pGender">Gender: </label></td>
                    <td><?php echo $cdefGen; ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="pDOB">*Date of Birth: </label></td>
                    <td><?php echo $Row['DOB']; ?></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Contact Details</caption>
                <tr>
                    <td class="move"><label for="pCNO">*Contact Number: </label></td>
                    <td><?php echo $Row['Phone']; ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="pAdd">*Address: </label></td>
                    <td><?php echo $Row['Address']; ?></td>
                </tr>
                <tr>
                    <td class="move"><label for="pEm">*Email: </label></td>
                    <td><?php echo $Row['Email']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    </body>
<?php }
    else
    { ?>
    <body>
    <div class="container">
        <h1>Patient Record</h1>
        <h3>*Mandatory</h3>
        <h3>Picture is optional</h3>
        <br/>
        <form method="post" action="" enctype="multipart/form-data">
            <div align="center">
                <table>
                    <caption>Patient Details</caption>
                    <tr>
                        <td class="move"><label for="pName">*Patient Name: </label></td>
                        <td><input type="text" name="pName" id="pName" value="<?php echo $Row['CustName']; ?>" maxlength="50" size="52" pattern="[A-Za-z]{3,50}" title="Only characters and a minimum length of 3" required> </td>
                        <td><label for="image">Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button"></td>
                    </tr>
                    <tr>
                        <td class="move"><label for="pIC">*Patient IC: </label></td>
                        <td><input type="text" name="pIC" id="pIC" value="<?php echo $Row['CustIC']; ?>" maxlength="14" size="16" style="background-color: lightgray" readonly> </td>
                        <td rowspan="4" align="center"><img src="<?php echo $default; ?>" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
                    </tr>
                    <tr>
                        <td class="move"><label for="pType">*Account Type: </label></td>
                        <td><input type="text" name="pType" id="pType" style="background-color: lightgray" value="<?php echo $Row['AccType']; ?>" size="10" readonly> </td>
                    </tr>
                    <tr>
                        <td class="move"><label for="pGender">Gender: </label></td>
                        <td><select name="pGender" id="pGender">
                                <option selected="selected" value="<?php echo $defGen; ?>"><?php echo $cdefGen; ?></option>
                                <option value="<?php echo $ncdefGen; ?>"><?php echo $ndefGen; ?></option>
                            </select> </td>
                    </tr>
                    <tr>
                        <td class="move"><label for="pDOB">*Date of Birth: </label></td>
                        <td><input type="date" name="pDOB" id="pDOB" value="<?php echo $Row['DOB']; ?>" max="<?php echo $Mtime; ?>" required> </td>
                    </tr>
                </table>
                <br/>
                <table>
                    <caption>Contact Details</caption>
                    <tr>
                        <td class="move"><label for="pCNO">*Contact Number: </label></td>
                        <td><input type="number" name="pCNO" id="pCNO" min="0" step="1" value="<?php echo $Row['Phone']; ?>" required> </td>
                    </tr>
                    <tr>
                        <td class="move"><label for="pAdd">*Address: </label></td>
                        <td><textarea name="pAdd" id="pAdd" maxlength="250" cols="45" rows="5" required><?php echo $Row['Address']; ?></textarea> </td>
                    </tr>
                    <tr>
                        <td class="move"><label for="pEm">*Email: </label></td>
                        <td><input type="email" name="pEm" id="pEm" value="<?php echo $Row['Email']; ?>" maxlength="40" size="42" required> </td>
                    </tr>
                </table>
                <br/>
                <input type="submit" name="btnSub" value="Edit" class="button">
            </div>
        </form>
    </div>
    </body>
<?php }
