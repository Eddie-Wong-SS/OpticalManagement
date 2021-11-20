<?php
/**
 Allows the adding of patients into the database
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
        Push.create('Successfully Registered!', {
            body: 'Registration of the patient <?php echo $_POST['pName']; ?> into the database was successful',
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
<title>Add Patient</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSub'])
{

    $SQL = "SELECT * FROM tblcustomer WHERE CustIC='" . strtoupper(trim($_POST['pIC'])) . "'";
    $Result = mysqli_query($Link, $SQL);

    if (mysqli_num_rows($Result) > 0) {
        echo "<script>alert('The inputted IC already exists in the database');</script>";
    }
    else
    {
        if($_FILES['image']['size'] != 0)
        {
            $target_path = "Images/";
            $target_path = $target_path . "patient".$_POST['pIC'].".png";
            move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
        }
        else $target_path = "";

        $addCustSQL = "INSERT INTO tblcustomer(CustIC, CustName, AccType, Gender, Address, Phone, DOB, Email, imageLoc, Status) VALUES(
                      '" . strtoupper(trim($_POST['pIC'])) . "',
                      '" . strtoupper(trim($_POST['pName'])) . "',
                      '" . strtoupper(trim($_POST['pType'])) . "',
                      '" . strtoupper(trim($_POST['pGender'])) . "',
                      '" . strtoupper(trim($_POST['pAdd'])) . "',
                      '" . strtoupper(trim($_POST['pCNO'])) . "',
                      '" . strtoupper(trim($_POST['pDOB'])) . "',
                      '" . strtoupper(trim($_POST['pEm'])) . "',
                      '$target_path',
                      'A')";
        $addCustSQLResult = mysqli_query($Link, $addCustSQL);
        ?>
        <script>checkers();</script>

        <?php
    }
}
?>

<body>
<div class="container">
    <h1>Patient Registration</h1>
    <h3>*Mandatory</h3>
    <h3>Picture is optional</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <div align="center">
            <table>
                <caption>Patient Details</caption>
                <tr>
                    <td class="move"><label for="pName">*Patient Name: </label></td>
                    <td><input type="text" name="pName" id="pName" maxlength="50" size="52" pattern="[A-Za-z]{3,50}" title="Only characters and a minimum length of 3" required> </td>
                    <td><label for="image">Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button"></td>
                </tr>
                <tr>
                    <td class="move"><label for="pIC">*Patient IC: </label></td>
                    <td><input type="text" name="pIC" id="pIC" title="Numbers only" pattern="\d*" minlength="14" maxlength="14" size = "16" required> </td>
                    <td rowspan="4" align="center"><img src="Images/no%20image%20selected.gif" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
                </tr>
                <tr>
                    <td class="move"><label for="pType">Account Type: </label></td>
                    <td><input type="text" name="pType" id="pType" value="Customer" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pGender">Gender: </label></td>
                    <td><select name="pGender" id="pGender">
                            <option selected="selected" value="M">Male</option>
                            <option value="F">Female</option>
                        </select> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pDOB">*Date of Birth: </label></td>
                    <td><input type="date" name="pDOB" id="pDOB" max="<?php echo $Mtime; ?>" required> </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Contact Details</caption>
                <tr>
                    <td class="move"><label for="pCNO">*Contact Number: </label></td>
                    <td><input type="number" name="pCNO" id="pCNO" min="0" step="1" required> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pAdd">*Address: </label></td>
                    <td><textarea name="pAdd" id="pAdd" maxlength="250" cols="45" rows="5" required></textarea> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pEm">*Email: </label></td>
                    <td><input type="email" name="pEm" id="pEm" maxlength="40" size="42" required> </td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="btnSub" value="Register" class="button">
        </div>
    </form>
</div>
</body>
