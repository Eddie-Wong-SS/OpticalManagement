<?php
/**
 Adds a contact solution to the cart
 */
error_reporting(1);
session_start();
include("database.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Added!', {
            body: 'Adding of item <?php echo $_POST['iCode']; ?> to the cart was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function total()
    {
        var price = document.getElementById('lPrice').value;
        var quan = document.getElementById('lQuan').value;
        document.getElementById('ltPrice').value = price * quan;

    }

    function check()
    {
        var x = document.getElementById('lQuan').value;
        var y = document.getElementById('qCheck').value;
        y = parseInt(y);
        if(x > y)
        {
            document.getElementById('lQuan').style = "border-color: red";
            alert('The current quantity has exceeded the available quantity!');
        }
        else
        {
            document.getElementById('lQuan').style = "";
        }
    }

    function back()
    {
        var loc = "<?php echo $_SESSION['page']; ?>";
        location = 'addCartResult.php?page='+loc;
    }
</script>
<title>Add Solution to Cart</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $SQL = "SELECT * FROM tblinventory, tblsolution, tblsellprice, tblcurrentstock WHERE 
            tblinventory.Code = tblsolution.solutionCode AND tblinventory.ItemId = tblsellprice.ItemId AND tblcurrentstock.ItemId = tblinventory.ItemId
             AND Code = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Check = mysqli_fetch_array($Result);
        if($Check['CurQuan'] < $_POST['lQuan']) echo "<script>alert('The selected quantity has exceeded the available quantity!');</script>";
        else
        {
            $Total = "SELECT CurQuan, Quantity FROm tblcurrentstock, tblitemsold WHERE tblcurrentstock.ItemId = tblitemsold.itemId AND tblcurrentstock.ItemId = '" . strtoupper(trim($_POST['iID'])) . "'
                        AND tblitemsold.invoiceCode = '".$_SESSION['inCode']."'";
            $TResult = mysqli_query($Link, $Total);
            if(mysqli_num_rows($TResult) > 0)
            {
                $Totals = mysqli_fetch_array($TResult);
                if(($Totals['Quantity'] + $_POST['lQuan']) > $Totals['CurQuan'])
                {
                    echo "<script>alert('The total amount of items selected from previous orders exceeds the current amount when combined with the amount from here')</script>";
                }
                else
                {
                    if(($Check['CurQuan'] - $_POST['lQuan'] - $Totals['Quantity']) < $Check['ReorderLim'])
                    {
                        $Deact = "UPDATE tblinventory SET Status = 'I' WHERE Code = '".$_GET['Id']."'";
                        $DResult = mysqli_query($Link, $Deact);
                        echo $Deact;
                    }
                    $Check = "SELECT * FROM tblitemsold WHERE invoiceCode = '" . strtoupper(trim($_SESSION['inCode'])) . "' AND itemId = '" . strtoupper(trim($_POST['iID'])) . "'";
                    $cResult = mysqli_query($Link, $Check);
                    if(mysqli_num_rows($cResult) > 0)
                    {
                        $Rows = mysqli_fetch_array($cResult);
                        $quan = $Rows['Quantity'] + floatval(strtoupper(trim($_POST['lQuan'])));
                        $total = $Rows['fullPrice'] + floatval(strtoupper(trim($_POST['ltPrice'])));
                        $Update = "UPDATE tblitemsold SET Quantity = '" . $quan . "', fullPrice = '" . $total . "' 
                    WHERE itemId = '" . strtoupper(trim($_POST['iID'])) . "' AND invoiceCode = '" . strtoupper(trim($_SESSION['inCode'])) . "'";
                        $uResult = mysqli_query($Link,$Update);
                    }
                    else
                    {
                        $Add = "INSERT INTO tblitemsold(invoiceCode, itemId, Quantity, Price, fullPrice) VALUES (
                        '" . strtoupper(trim($_SESSION['inCode'])) . "',
                        '" . strtoupper(trim($_POST['iID'])) . "',
                        '" . strtoupper(trim($_POST['lQuan'])) . "',
                        '" . strtoupper(trim($_POST['lPrice'])) . "',
                        '" . strtoupper(trim($_POST['ltPrice'])) . "')";
                        $aResult = mysqli_query($Link, $Add);
                    }
                    if($aResult || $cResult)
                    {
                        ?>
                        <script>
                            checkers();
                            var loc = "<?php echo $_SESSION['page']; ?>";
                            location = 'addCartResult.php?page='+loc;</script>
                        <?php
                    }
                }
            }
            else
            {
                if(($Check['CurQuan'] - $_POST['lQuan']) < $Check['ReorderLim'])
                {
                    $Deact = "UPDATE tblinventory SET Status = 'I' WHERE Code = '".$_GET['Id']."'";
                    $DResult = mysqli_query($Link, $Deact);
                }
                $Check = "SELECT * FROM tblitemsold WHERE invoiceCode = '" . strtoupper(trim($_SESSION['inCode'])) . "' AND itemId = '" . strtoupper(trim($_POST['iID'])) . "'";
                $cResult = mysqli_query($Link, $Check);
                if(mysqli_num_rows($cResult) > 0)
                {
                    $Rows = mysqli_fetch_array($cResult);
                    $quan = $Rows['Quantity'] + floatval(strtoupper(trim($_POST['lQuan'])));
                    $total = $Rows['fullPrice'] + floatval(strtoupper(trim($_POST['ltPrice'])));
                    $Update = "UPDATE tblitemsold SET Quantity = '" . $quan . "', fullPrice = '" . $total . "' 
                    WHERE itemId = '" . strtoupper(trim($_POST['iID'])) . "' AND invoiceCode = '" . strtoupper(trim($_SESSION['inCode'])) . "'";
                    $uResult = mysqli_query($Link,$Update);
                }
                else
                {
                    $Add = "INSERT INTO tblitemsold(invoiceCode, itemId, Quantity, Price, fullPrice) VALUES (
                        '" . strtoupper(trim($_SESSION['inCode'])) . "',
                        '" . strtoupper(trim($_POST['iID'])) . "',
                        '" . strtoupper(trim($_POST['lQuan'])) . "',
                        '" . strtoupper(trim($_POST['lPrice'])) . "',
                        '" . strtoupper(trim($_POST['ltPrice'])) . "')";
                    $aResult = mysqli_query($Link, $Add);
                }
                if($aResult || $cResult)
                {
                    ?>
                    <script>
                        checkers();
                        var loc = "<?php echo $_SESSION['page']; ?>";
                        location = 'addCartResult.php?page='+loc;</script>
                    <?php
                }
            }
        }
    }

}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblinventory, tblsolution, tblsellprice, tblcurrentstock WHERE 
            tblinventory.Code = tblsolution.solutionCode AND tblinventory.ItemId = tblsellprice.ItemId AND tblcurrentstock.ItemId = tblinventory.ItemId
             AND Code = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);


    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $default = $Row['imageLoc'];
        if($default == "")
        {
            $default = "Images/no image available.png";
        }
    }
}
?>

<body>
<div class="container">
    <h1>Add Solution to Cart</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="iID" id="iID" value="<?php echo $Row['ItemId']; ?>">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="iCode">*Item Code: </label></td>
                <td><?php echo $Row['Code'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="iName">*Item Name: </label></td>
                <td><?php echo $Row['ItemName'] ?></td>
                <td rowspan="3" align="center"><img src="<?php echo $default ?>" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
            </tr>
            <tr>
                <td class="move"><label for="iDescrip">*Item Description</label></td>
                <td><?php echo $Row['ItemDesc'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="iType">Item Type: </label></td>
                <td><?php echo $Row['ItemType'] ?></td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Solution Details</caption>
            <tr>
                <td class="move"><label for="sType">*Type: </label></td>
                <td><?php echo $Row['Type'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="sFC">*For Contact Type: </label></td>
                <td><?php echo $Row['forcontact'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="sEX">*Epiry Date: </label></td>
                <td><?php echo $Row['ExpireDateS'] ?></td>
            </tr>
        </table>
        <br />
        <table>
            <caption>Select Amount</caption>
            <tr>
                <td><label for="lPrice">Price Per Unit(RM): </label></td>
                <td><input type="text" name="lPrice" id="lPrice" value="<?php echo $Row['Price']; ?>" style="background-color: lightgray" readonly> </td>
            </tr>
            <tr>
                <td><label for="lQuan">*Quantity to Order: </label></td>
                <td><input type="number" name="lQuan" id="lQuan" min="0" step="1" onblur="total(); check()" required> </td>
            </tr>
            <tr>
                <td><label for="mQuan">Maximum Quantity: </label></td>
                <td><input type="text" name="mQuan" id="mQuan" value="<?php echo $Row['CurQuan']; ?>" style="background-color: lightgray" readonly> </td>
            </tr>
            <tr>
                <td><label for="ltPrice">Total Price(RM): </label></td>
                <td><input type="number" name="ltPrice" id="ltPrice" style="background-color: lightgray" readonly required> </td>
            </tr>
        </table>
        <input type="hidden" name="qCheck" id="qCheck" value="<?php echo $Row['CurQuan']; ?>"
        <br/>
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Add Item to Cart" class="button"></td>
                <td><input type="button" name="btnBack" value="Back" class="button" onclick="back()"> </td>
            </tr>
        </table>
    </form>
</div>
</body>

