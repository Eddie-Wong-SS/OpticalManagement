<?php
/**
Shows the customer whose sales is being recorded's details
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Sales Process</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSub'])
{
    $Check = "SELECT invoiceCode FROm tblinvoice WHERE invoiceCode = '".$_POST['iCode']."'";
    $CResult = mysqli_query($Link, $Check);
    if(mysqli_num_rows($CResult) > 0) $error = "<h3>Sorry, this code is already taken</h3>";
    else
    {
        $_SESSION['date'] = $_POST['iDate'];
        $_SESSION['inCode'] = $_POST['iCode'];
        $_SESSION['Buyer'] = $_POST['pIC'];
        echo "<script>location='addCart.php?page=1'</script>";
    }
}
else if($_GET['Id'] != "")
{
    $flag1 = 0;
    $flag2 = 0;
    $flag3 = 0;

    if($_GET['Reset'] != "NO")
    {
        $_SESSION['Buyer'] = "";
        $_SESSION['iDate'] = "";
        $_SESSION['inCode'] = "";
        $_SESSION['page'] = "";

        $Reset = "SELECT * FROM tblitemsold WHERE NOT EXISTS(SELECT 1 FROM tblinvoice WHERE tblinvoice.invoiceCode = tblitemsold.invoiceCode)";
        $rResult = mysqli_query($Link, $Reset);
        if(mysqli_num_rows($rResult) > 0)
        {
            $Remove = "DELETE FROM tblitemsold WHERE NOT EXISTS(SELECT 1 FROM tblinvoice WHERE tblinvoice.invoiceCode = tblitemsold.invoiceCode)";
            $remResult = mysqli_query($Link, $Remove);
        }
    }

    $SQL = "SELECT * FROM tblcustomer WHERE CustIC = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);

    $cdSQL = "SELECT * FROM tblconmedrec WHERE Eye = 'OD' AND checkDate = (SELECT MAX(checkDate) FROM tblconmedrec WHERE NOW() < expireDate AND CustIC = '".$_GET['Id']."' AND Status = 'A') ";
    $cdResult = mysqli_query($Link, $cdSQL);


    $csSQL = "SELECT * FROM tblconmedrec WHERE Eye = 'OS' AND checkDate = (SELECT MAX(checkDate) FROM tblconmedrec WHERE NOW() < expireDate AND CustIC = '".$_GET['Id']."' AND Status = 'A') ";
    $csResult = mysqli_query($Link, $csSQL);


    $gdSQL = "SELECT * FROM tblglassmedrec WHERE Eye = 'OD' AND checkDate = (SELECT MAX(checkDate) FROM tblglassmedrec WHERE NOW() < expireDate AND CustIC = '".$_GET['Id']."' AND Status = 'A')";
    $gdResult = mysqli_query($Link, $gdSQL);

    $gsSQL = "SELECT * FROM tblglassmedrec WHERE Eye = 'OS' AND checkDate = (SELECT MAX(checkDate) FROM tblglassmedrec WHERE NOW() < expireDate AND CustIC = '".$_GET['Id']."' AND Status = 'A')";
    $gsResult = mysqli_query($Link, $gsSQL);

    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        if($Row['Gender'] == 'M') $Gend = 'Male';
        else $Gend = 'Female';
        if(mysqli_num_rows($cdResult) > 0 && mysqli_num_rows($csResult) > 0)
        {
            $cError = "";
            $cdRow = mysqli_fetch_array($cdResult);
            $csRow = mysqli_fetch_array($csResult);
        }
        else
        {
            $flag3 = 1;
            $cError = "This customer does not have a contacts prescription, or the most recent one is outdated!";
        }
        if(mysqli_num_rows($gdResult) > 0 && mysqli_num_rows($gsResult) > 0)
        {
            $gError = "";
            $gdRow = mysqli_fetch_array($gdResult);
            $gsRow = mysqli_fetch_array($gsResult);

        }else
        {
            $flag2 = 1;
            $gError = "This customer does not have a glass prescription, or the most recent one is outdated!";
        }
    }
    else
    {
        $flag1 = 0;
    }

}
?>

<body>
<div class="container">
    <h1>Add Sales</h1>

    <form method="post">
    <label><?php echo $error; ?></label>
    <table>
        <caption>Invoice Details</caption>
        <tr>
            <td><label for="iCode">*Invoice Code: </label></td>
            <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" placeholder="I01" required> </td>
        </tr>
        <tr>
            <td><label for="iDate">*Date Sold: </label></td>
            <td><input type="date" name="iDate" id="iDate" max="<?php echo $Mtime; ?>" required> </td>
        </tr>
    </table>
    <br/>
    <table>
        <caption>Patient Details</caption>
        <tr>
            <td class="move"><label for="pIC">Patient IC: </label></td>
            <td><input type="text" name="pIC" id="pIC" value="<?php echo $Row['CustIC']; ?>" style="background-color: lightgray" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="pName">Patient Name: </label></td>
            <td><input type="text" name="pName" id="pName" value="<?php echo $Row['CustName']; ?>" style="background-color: lightgray" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="pGen">Gender: </label></td>
            <td><input type="text" name="pGen" id="pGen" value="<?php echo $Gend; ?>" style="background-color: lightgray" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="pCNo">Contact NO: </label></td>
            <td><input type="text" name="pCNo" id="pCNo" value="<?php echo $Row['Phone']; ?>" style="background-color: lightgray" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="pEm">Email: </label></td>
            <td><input type="text" name="pEm" id="pEm" value="<?php echo $Row['Email']; ?>" style="background-color: lightgray" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="pAcc">Account Type: </label></td>
            <td><input type="text" name="pAcc" id="pAcc" value="<?php echo $Row['AccType']; ?>" style="background-color: lightgray" readonly> </td>
        </tr>
    </table>
    <br />
    <div class="side">
        <fieldset>
        <legend>Contacts Record</legend>
            <h3 style="color: red"><?php echo $cError; ?></h3>
        <table >
            <caption>Oculus Dexter</caption>
            <tr>
                <td style="width: 65%" class="move"><label for="cdPWR">*PWR(Power): </label></td>
                <td><?php echo $cdRow['Pwr'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdBC">*BC(Base Curve): </label></td>
                <td><?php echo $cdRow['BC'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdD">*DIA(Diameter): </label></td>
                <td><?php echo $cdRow['Dia'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdYL">*CYL(Cylinder): </label></td>
                <td><?php echo $cdRow['Cyl'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdA">*Axis(In Degrees): </label></td>
                <td><?php echo $cdRow['Axis']; ?></td>
            </tr>
            <tr>
                <td class="move"><label for="cdADD">*ADD(Add Power): </label></td>
                <td><?php echo $cdRow['addPwr'];?></td>
            </tr>
        </table>
        <br/>
        <table >
            <caption>Oculus Sinister</caption>
            <tr>
                <td class="move" style="width: 65%"><label for="crPWR">*PWR(Power): </label></td>
                <td><?php echo $csRow['Pwr'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crBC">*BC(Base Curve): </label></td>
                <td><?php echo $csRow['BC'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crD">*DIA(Diameter): </label></td>
                <td><?php echo $csRow['Dia'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crYL">*CYL(Cylinder): </label></td>
                <td><?php echo $csRow['Cyl'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crA">*Axis(In Degrees): </label></td>
                <td><?php echo $csRow['Axis'];?></td>
            </tr>
            <tr>
                <td class="move"><label for="crADD">*ADD(Add Power): </label></td>
                <td><?php echo $csRow['addPwr'];?> </td>
            </tr>
        </table>
    </fieldset>
    &nbsp;&nbsp;
    <fieldset>
    <legend>Glasses Record</legend>
        <h3 style="color: red"><?php echo $gError; ?></h3>
    <table >
        <caption>Oculus Dexter</caption>
        <tr>
            <td class="move" style="width: 65%;"><label for="gdSphere">*Sphere: </label></td>
            <td><?php echo $gdRow['Sphere'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gdYL">Cylinder: </label></td>
            <td><?php echo $gdRow['Cylinder'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gdA">Axis(1-180): </label></td>
            <td><?php echo $gdRow['Axis'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gdP">*Prism: </label></td>
            <td><?php echo $gdRow['Prism'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gdB">Base</label></td>
            <td><?php echo $gdRow ?></td>
        </tr>
        <tr>
            <td class="move"><label for="gdAP">ADD(Magnifying Power): </label></td>
            <td><?php echo $gdRow['addPwr'];?></td>
        </tr>
    </table>
    <br/>
    <table >
        <caption>Oculus Sinister</caption>
        <tr>
            <td class="move" style="width: 65%;"><label for="gsSphere">*Sphere: </label></td>
            <td><?php echo $gsRow['Sphere'];?> </td>
        </tr>
        <tr>
            <td class="move"><label for="gsYL">Cylinder: </label></td>
            <td><?php echo $gsRow['Cylinder'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gsA">Axis(1-180): </label></td>
            <td><?php echo $gsRow['Axis'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gsP">*Prism: </label></td>
            <td><?php echo $gsRow['Prism'];?></td>
        </tr>
        <tr>
            <td class="move"><label for="gsB">Base</label></td>
            <td><?php echo $gsRow ?></td>
        </tr>
        <tr>
            <td class="move"><label for="gsAP">ADD(Magnifying Power): </label></td>
            <td><?php echo $gsRow['addPwr'];?></td>
        </tr>
    </table>
    <br/>
    <table align="center"  border = "0">
        <caption>Comments(Optional)</caption>
        <tr>
            <td class="move" width="55%">Remarks</td>
            <td><?php echo $gsRow['Remark']; ?></td>
        </tr>
    </table>
    </fieldset>
    </div>
    <br/>
    <table>
        <tr>
            <?php if($flag1 == 0 && ($flag2 == 0 || $flag3 == 0))
            {
                echo "<td><input type=\"submit\" name=\"btnSub\" id=\"btnSub\" value=\"Add Items to Cart\" class=\"button\"> </td>";
            }?>
        </tr>
    </table>
    </form>
</div>
</body>
