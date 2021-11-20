<?php
/**
 Creates a purchase form to order new items from a supplier
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
include ('Email.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    count = 0;

    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Sent!', {
            body: 'Purchase form to supplier <?php echo $_POST['supName']; ?> has been successfully emailed',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function totalPrice(obtain)
    {
        var array = obtain.split("n");
        var i = array[1];
        var sum = document.getElementById("Price" + i).value;
        var quan = document.getElementById("Quan"+ i).value;
        document.getElementById("tPrice" + i).value = sum * quan;
    }

    function plusPurchase()
    {
        var table = document.getElementById("tblPurchase");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        cell1.cellSpacing = 1;
        cell2.cellSpacing = 1;
        cell3.cellSpacing = 1;
        cell4.cellSpacing = 1;
        ++count;
        cell1.innerHTML = count + "." ;
        cell2.innerHTML = "<input type='text' name='Name" + count + "' id='Name" + count + "' size='15' maxlength='50' required/>";
        cell3.innerHTML = "<textarea name='Descp" + count + "' id='Descp" + count + "' cols='35' rows='5'></textarea>";
        cell4.innerHTML = "<input type='number' name='tPrice" + count + "' id='tPrice" + count + "' value='0' style='background-color: lightgray;' readonly/>";

        document.getElementById("counterPurchase").value = count;
    }

    function minusPurchase()
    {
        var table = document.getElementById("tblPurchase");
        var row = table.deleteRow(-1);

        if ( count !== 0 )
            count--;

        document.getElementById("counterPurchase").value = count;
    }

</script>
<title>Purchase From Supplier</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $Check = "SELECT purCode FROM tblpurchase WHERE purCode = '".strtoupper(trim($_POST['purCode']))."'";
    $CResult = mysqli_query($Link, $Check);
    if(mysqli_num_rows($CResult) > 0)
    {
        echo "<script>alert('The purchase code you are using is already used in another form');</script>";
    }
    else
    {
        $Reorder = "<body>
<div class=\"container\" style=\"width: 90%\">
    <h1>Purchase Form</h1>
    <br />
    <form method=\"post\" action=\"\">
        <div align=\"center\">
            <table border=\"0\">
                <caption>Supplier Details</caption>
                <tr>
                    <td class=\"move\"><label for=\"supId\">Supplier ID: </label></td>
                    <td><input type=\"text\" name=\"supId\" id=\"supId\" value =  '".strtoupper(trim($_GET['Id']))."' style=\"background-color: lightgray\" readonly></td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"supName\">Supplier Name: </label></td>
                    <td><input type=\"text\" name=\"supName\" id=\"supName\" value='".strtoupper(trim($_POST['supName']))."' maxlength=\"50\" size=\"52\" style=\"background-color: lightgray\" readonly></td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"supAdd\">Supplier Location: </label></td>
                    <td><textarea name=\"supAdd\" id=\"supAdd\" maxlength=\"250\" cols=\"45\" rows=\"5\" style=\"background-color: lightgray\" readonly>'".strtoupper(trim($_POST['supAdd']))."'</textarea> </td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"supNum\">Supplier Contact Number: </label></td>
                    <td><input type=\"text\" name=\"supNum\" id=\"supNum\" value='".strtoupper(trim($_POST['supNum']))."' oninput=\"this.value=this.value.replace(/[^0-9]/g,'');\" style=\"background-color: lightgray\" readonly> </td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"supEm\">Supplier Email: </label></td>
                    <td><input type=\"email\" name=\"supEm\" id=\"supEm\" value='".strtoupper(trim($_POST['supEm']))."' maxlength=\"40\" size=\"42\" style=\"background-color: lightgray\" readonly> </td>
                </tr>
            </table>
            <br /><br />

            <table border=\"0\" >
                <caption>Person-in-charge Details</caption>
                <tr>
                    <td class=\"move\"><label for=\"supCon\">Person-in-charge: </label></td>
                    <td><input type=\"text\" name=\"supCon\" id=\"supCon\" value='".strtoupper(trim($_POST['supCon']))."' maxlength=\"50\" size=\"52\" style=\"background-color: lightgray\" readonly></td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"conNum\">Contact Number: </label> </td>
                    <td><input type=\"text\" name=\"conNum\" id=\"conNum\" value='".strtoupper(trim($_POST['conNum']))."' oninput=\"this.value=this.value.replace(/[^0-9]/g,'');\"  style=\"background-color: lightgray\" readonly></td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"conEm\">Email: </label></td>
                    <td><input type=\"email\" name=\"conEm\" id=\"conEm\" value='".strtoupper(trim($_POST['conEm']))."' maxlength=\"50\" size=\"52\" style=\"background-color: lightgray\" readonly></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Purchase Code</caption>
                <tr>
                    <td class=\"move\"><label for=\"reCode\">*Reorder Form Code: </label></td>
                    <td><input type=\"text\" name=\"reCode\" id=\"reCode\" maxlength=\"25\" size=\"27\" value='".strtoupper(trim($_POST['purCode']))."' required> </td>
                </tr>
            </table>
            <br/>
            <table cellpadding=\"6\">
                <caption>Supplied Item(s)</caption>
                <tr>
                    <td></td>
                    <td >Item Name</td>
                    <td >Estimated Arrival Time(Days)</td>
                    <td >Quantity To Order</td>
                </tr>
            ";
        $count = 1;
        for($i = 1; $i <= $_POST['counterPurchase']; ++$i)
        {
            if($_POST['tPrice'.$i] == 0) continue;

            $AddReorderSQL = "INSERT INTO tblpurchase(purCode, SuppId, SuppName, ItemName, ItemDesc, Quantity) VALUES (
                            '".strtoupper(trim($_POST['purCode']))."',
                            '".strtoupper(trim($_GET['Id']))."',
                            '".strtoupper(trim($_POST['supName']))."',
                            '".strtoupper(trim($_POST['Name'.$i]))."',
                            '".strtoupper(trim($_POST['Descp'.$i]))."',                                               
                            '".strtoupper(trim($_POST['Quan'.$i]))."')";

            $AResult = mysqli_query($Link, $AddReorderSQL);

            $Reorder .= "<tr>
                            <td><input type='text' value='".$count."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['Name'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['Descp'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['Quan'.$i]))."' </td>
                         </tr>
                         ";
            ++$count;
        }

        $Reorder .= "</table>
                     <br />";

        sendEmail("Purchase Form", $Reorder, $_POST['conEm']);

        {?>
            <script>checkers();</script>
            <?php
        }
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblsupplier WHERE SuppId = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $conNo = $Row['ContactNo'];
    }

}
?>

<body>
<div class="container" style="width: 90%">
    <h1>Purchase Form</h1>
    <h3>*Mandatory</h3>
    <br />
    <form method="post" action="">
        <div align="center">
            <table border="0">
                <caption>Supplier Details</caption>
                <tr>
                    <td class="move"><label for="supId">Supplier ID: </label></td>
                    <td><input type="text" name="supId" id="supId" value = "<?php echo $Row['SuppId']; ?>" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="supName">Supplier Name: </label></td>
                    <td><input type="text" name="supName" id="supName" value="<?php echo $Row['SuppName']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="supAdd">Supplier Location: </label></td>
                    <td><textarea name="supAdd" id="supAdd" maxlength="250" cols="45" rows="5" style="background-color: lightgray" readonly><?php echo $Row['Address']; ?></textarea> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supNum">Supplier Contact Number: </label></td>
                    <td><input type="text" name="supNum" id="supNum" value="<?php echo $Row['SuppNo']; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supEm">Supplier Email: </label></td>
                    <td><input type="email" name="supEm" id="supEm" value="<?php echo $Row['Email']; ?> " maxlength="40" size="42" style="background-color: lightgray" readonly> </td>
                </tr>
            </table>
            <br /><br />

            <table border="0" >
                <caption>Person-in-charge Details</caption>
                <tr>
                    <td class="move"><label for="supCon">Person-in-charge: </label></td>
                    <td><input type="text" name="supCon" id="supCon" value="<?php echo $Row['ContactPerson']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="conNum">Contact Number: </label> </td>
                    <td><input type="text" name="conNum" id="conNum" value="<?php echo $conNo; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');"  style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="conEm">Email: </label></td>
                    <td><input type="email" name="conEm" id="conEm" value="<?php echo $Row['ContactEmail']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Purchase Code</caption>
                <tr>
                    <td class="move"><label for="purCode">*Purchase Form Code: </label></td>
                    <td><input type="text" name="purCode" id="purCode" maxlength="25" size="27" placeholder="PF01" required> </td>
                </tr>
            </table>
            <br/>
            <table cellpadding="6" style="width: 90%" id="tblPurchase">
                <caption>Supplied Item(s)</caption>
                <tr>
                    <td ></td>
                    <td >*Item Name</td>
                    <td >Item Description</td>
                    <td >Total Price(RM)</td>
                </tr>
            </table>
            <table style="width:90%">
                <tr>
                    <td colspan="6" style="text-align:center">
                        <input type="button" name="plus" id="plus" value="+" onclick="plusPurchase()"  class="button site"/>
                        <input type="button" name="minus" id="minus" value="-" onclick="minusPurchase()" class="button site"/>
                    </td>
                </tr>
            </table>
            <br/><br/>
            <input type="hidden" name="counterPurchase" id="counterPurchase" value="0"/>
            <input type="submit" name="btnSub" class="button" value="Submit Form" style="text-align: center">
        </div>
    </form>
</div>
</body>
