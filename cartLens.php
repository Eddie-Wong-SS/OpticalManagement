<?php
/**
 Adds this Frame to the cart
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
<title>Add Lens to Cart</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $SQL = "SELECT * FROM tblinventory, tbllens, tblsellprice, tblcurrentstock WHERE 
            tblinventory.Code = tbllens.lensCode AND tblinventory.ItemId = tblsellprice.ItemId AND tblcurrentstock.ItemId = tblinventory.ItemId
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
                if($aResult || $uResult)
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
    $SQL = "SELECT * FROM tblinventory, tbllens, tblsellprice, tblcurrentstock WHERE 
            tblinventory.Code = tbllens.lensCode AND tblinventory.ItemId = tblsellprice.ItemId AND tblcurrentstock.ItemId = tblinventory.ItemId
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
        $defBase = $Row['Base'];
        if($defBase == "UP")
        {
            $ndefBase = "DOWN";

        }
        else{
            $ndefBase = "UP";

        }

    }
}
?>

<body>
<div class="container">
    <h1>Add Lens to Cart</h1>
    <h3>* Mandatory</h3>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="iID" id="iID" value="<?php echo $Row['ItemId']; ?>">
        <table>
            <caption>General Details</caption>
            <tr>
                <td class="move"><label for="iCode">Item Code: </label></td>
                <td><?php echo $Row['Code'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="iName">Item Name: </label></td>
                <td><?php echo $Row['ItemName'] ?></td>
                <td rowspan="3" align="center"><img src="<?php echo $default ?>" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
            </tr>
            <tr>
                <td class="move"><label for="iDescrip">Item Description</label></td>
                <td><?php echo $Row['ItemDesc'] ?> </td>
            </tr>
            <tr>
                <td class="move"><label for="iType">Item Type: </label></td>
                <td><?php echo $Row['ItemType'] ?></td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Lens Details</caption>
            <tr>
                <td class="move"><label for="lCat">Category: </label></td>
                <td><?php echo $Row['lensCategory'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lType">Lens Type: </label></td>
                <td><?php echo $Row['lensType'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lMat">Lens Material: </label></td>
                <td><?php echo $Row['lensMaterial'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lColor">*Lens Color: </label></td>
                <td><?php echo $Row['lensColor'] ?></td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>RX Details</caption>
            <tr>
                <td class="move"><label for="lPWR">Sphere: </label></td>
                <td><?php echo $Row['Sphere'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lCYL">Cylinder: </label></td>
                <td><?php echo $Row['Cylinder'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lA">Axis(1-180): </label></td>
                <td><?php echo $Row['Axis'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lP">Prism: </label></td>
                <td><?php echo $Row['Prism'] ?></td>
            </tr>
            <tr>
                <td class="move"><label for="lB">Base</label></td>
                <td><?php echo $defBase ?> </td>
            </tr>
            <tr>
                <td class="move"><label for="lAP">ADD(Magnifying Power): </label></td>
                <td><?php echo $Row['addPwr'] ?> </td>
            </tr>
            <tr>
                <td class="move"><label for="lTreat">Treatment: </label></td>
                <td><?php echo $Row['Treatment'] ?></td>
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
                <td><input type="number" name="ltPrice" id="ltPrice" value="0" style="background-color: lightgray" readonly> </td>
            </tr>
        </table>
        <input type="hidden" name="qCheck" id="qCheck" value="<?php echo $Row['CurQuan']; ?>"
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Add Item to Cart" class="button">&nbsp;&nbsp;&nbsp;</td>
                <td><input type="button" name="btnBack" value="Back" class="button" onclick="back()"> </td>
            </tr>
        </table>
    </form>
</div>
</body>

