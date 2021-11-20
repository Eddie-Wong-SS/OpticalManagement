<?php
/**
 Creates a reorder form for ordering items from a supplier
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
include ('Email.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script type="text/javascript">

    Push.Permission.request();
    function checkers()
    {
        Push.create('Successfully Sent!', {
            body: 'Reorder form to supplier <?php echo $_POST['supName']; ?> has been successfully emailed',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function totalPrice(i)
    {
        var sum = document.getElementById("Price" + i).value;
        var quan = document.getElementById("orQ"+ i).value;
        document.getElementById("tPrice" + i).value = sum * quan;
    }

    function setHid()
    {
        var add = document.createElement('input');
        add.type = "hidden";
        add.name = "yay";
        add.id = "yay";
        add.value="2";

        var z = document.getElementById('fForm');
        z.appendChild(add);
    }
</script>
<head>
<title>Order From Supplier</title>
</head>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_POST['yay'])
{
    $Check = "SELECT reCode FROM tblreorder WHERE reCode = '".strtoupper(trim($_POST['reCode']))."'";
    $CResult = mysqli_query($Link, $Check);
    if(mysqli_num_rows($CResult) > 0)
    {
        echo "<script>alert('The reorder code you are using is already used in another form');</script>";
    }
    else
    {
        $Reorder = "<html><body>
    <h1>Reorder Form</h1>
    <br />
        <div align=\"center\">
        <form action=\"PDF.php\">
            <table border=\"0\">
                <caption>Supplier Details</caption>
                <tr>
                    <td class=\"move\"><label for=\"supId\">Supplier ID: </label></td>
                    <td><input type=\"text\" name=\"supId\" id=\"supId\" value =  '".strtoupper(trim($_GET['Id']))."'></td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"supName\">Supplier Name: </label></td>
                    <td><input type=\"text\" name=\"supName\" id=\"supName\" value='".strtoupper(trim($_POST['supName']))."'></td>
                </tr>
                <tr>
                    <td class='move'><label for='supEm'>Email To: </label></td>
                    <td><input type=\"email\" name=\"conEm\" id=\"conEm\" size='50' value='".strtoupper(trim($_POST['conEm']))."'></td>
                </tr>
            </table>
            <br /><br />
            <table>
                <caption>Reorder Code</caption>
                <tr>
                    <td class=\"move\"><label for=\"reCode\">*Reorder Form Code: </label></td>
                    <td><input type=\"text\" name=\"reCode\" id=\"reCode\" maxlength=\"25\" size=\"27\" value='".strtoupper(trim($_POST['reCode']))."'> </td>
                </tr>
            </table>
            <br/>
            <table cellpadding=\"6\">
                <caption>Supplied Item(s)</caption>
                <tr>
                    <td></td>
                    <td >Item Name</td>
                    <td >Estimated Arrival Time(Days)</td>
                    <td >Price(RM) Per Unit</td>
                    <td >Quantity To Order</td>
                    <td >Total Price(RM)</td>
                </tr>";
        $count = 1;
        for($i = 0, $count = 1; $i < $_POST['cSupplied']; ++$i)
        {
            if($_POST['tPrice'.$i] == 0) continue;


            $AddReorderSQL = "INSERT INTO tblreorder(reCode, SuppId, SuppName, ItemName, ETA, Price, totalPrice, Quantity) VALUES (
                            '".strtoupper(trim($_POST['reCode']))."',
                            '".strtoupper(trim($_GET['Id']))."',
                            '".strtoupper(trim($_POST['supName']))."',
                            '".strtoupper(trim($_POST['Code'.$i]))."',
                            '".strtoupper(trim($_POST['ETA'.$i]))."',
                            '".strtoupper(trim($_POST['Price'.$i]))."',
                            '".strtoupper(trim($_POST['tPrice'.$i]))."',
                            '".strtoupper(trim($_POST['orQ'.$i]))."')";

            $AResult = mysqli_query($Link, $AddReorderSQL);

            $Reorder .= "<tr>
                            <td><input type=\"text\" value='".$count."' </td>
                            <td><input type=\"text\" value='".strtoupper(trim($_POST['Code'.$i]))."'/> </td>
                            <td><input type=\"text\" value='".strtoupper(trim($_POST['ETA'.$i]))."'/> </td>
                            <td><input type=\"text\" value='".strtoupper(trim($_POST['Price'.$i]))."'/> </td>
                            <td><input type=\"text\" value='".strtoupper(trim($_POST['orQ'.$i]))."'/> </td>
                            <td><input type=\"text\" value='".strtoupper(trim($_POST['tPrice'.$i]))."'/> </td>
                         </tr>";
            ++$count;
        }

        $Reorder .= "</table>
                     <br />  
                     </div>
                     </form>                
                     </body>
                     </html>";
        sendEmail("Reorder Form", $Reorder, $_POST['conEm']);
        {?>
            <script>
                printIt();
                checkers();
            </script>
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
    }

}
?>

<body onload="setHid()">
<div class="container" style="width: 90%">
    <div id="Printing" class="Printing">
    <h1>Reorder Form</h1>
    <h3>*Mandatory</h3>
    <br />
    <form method="post" action="" name="fForm" id="fForm">
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
                    <td><input type="text" name="conNum" id="conNum" value="<?php echo $Row['ContactNo']; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');"  style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="conEm">Email: </label></td>
                    <td><input type="email" name="conEm" id="conEm" value="<?php echo $Row['ContactEmail']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Reorder Code</caption>
                <tr>
                    <td class="move"><label for="reCode">*Reorder Form Code: </label></td>
                    <td><input type="text" name="reCode" id="reCode" maxlength="25" size="27" placeholder="RF01" required> </td>
                </tr>
            </table>
            <br/>
            <table cellpadding="6" style="width: 90% !important">
                <caption>Supplied Item(s)</caption>
                <tr>
                    <td ></td>
                    <td >Item Name</td>
                    <td >Estimated Arrival Time(Days)</td>
                    <td >Price(RM) Per Unit</td>
                    <td >Additional Remarks</td>
                    <td >*Quantity To Order</td>
                    <td >Total Price(RM)</td>
                </tr>
                <?php $SQLI = "SELECT * FROM tblsupplies WHERE SuppId = '".$_GET['Id']."'";
                $SQLIResult = mysqli_query($Link, $SQLI);
                if(mysqli_num_rows($SQLIResult) > 0);
                {
                    for($i = 0; $i < mysqli_num_rows($SQLIResult); ++$i)
                    {
                        $Row = mysqli_fetch_array($SQLIResult);
                        $cSupp = $i + 1;?>
                        <tr>
                            <td style="width: 5px"><?php echo ($i+1); ?></td>
                            <td style="width: 5px"><input type="text" name="<?php echo 'Code'.$i;?>" id="<?php echo 'Code'.$i;?>" size="27" maxlength="25" value="<?php echo $Row['ItemName']; ?>" readonly /></td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'ETA'.$i;?>" id="<?php echo 'ETA'.$i;?>" step="1" min="0" value="<?php echo $Row['ETA'] ?>" style="background-color: lightgray" readonly /></td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'Price'.$i;?>" id="<?php echo 'Price'.$i;?>" step="0.01" min="0" value="<?php echo $Row['Price'] ?>" readonly /></td>
                            <td style="width: 5px"><textarea name="<?php echo 'Remark'.$i;?>" id="<?php echo 'Remark'.$i;?>" cols="25" rows="5" maxlength="500" style="background-color: lightgray" readonly><?php echo $Row['Remark'] ?></textarea></td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'orQ'.$i;?>" id="<?php echo 'orQ'.$i;?>" step="1" min="0" onblur="totalPrice(<?php echo $i; ?>)" value="0"> </td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'tPrice'.$i;?>" id="<?php echo 'tPrice'.$i;?>" step="0.01" min="0" style="background-color: lightgray" value="0" readonly> </td>
                        </tr>
                        <input type="hidden" name="<?php echo 'ID'.$i;?>" value="<?php echo $Row['sID'] ?>">
                    <?php    }
                }
                ?>
                <input type="hidden" name="cSupplied" value="<?php echo $cSupp ?>">
            </table>
            <br/><br/>
            <input type="submit" name="btnSub" id="btnSub" class="button" value="Submit Form"  style="text-align: center">
    </form>
    </div>
</div>
</body>
