<?php
/**
 Allows the setting of a price for an item
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
        Push.create('Successfully Set!', {
            body: 'Setting of the price for item code <?php echo $_GET['Id']; ?> was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Set Item Price</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSub'])
{
    $Select = "SELECT ItemId FROM tblinventory WHERE ItemName = '".$_GET['Id']."'";
    $SelectResult = mysqli_query($Link, $Select);
    if(mysqli_num_rows($SelectResult) > 0)
    {
        $RowInfo = mysqli_fetch_array($SelectResult);
        $id = $RowInfo['ItemId'];

        $SQL = "SELECT * FROM tblsellprice WHERE ItemId = '".$id."'";
        $Checks = mysqli_query($Link, $SQL);
        if(mysqli_num_rows($Checks) > 0)
        {
            $SQL = "UPDATE tblsellprice SET Price = '" . strtoupper(trim($_POST['iPrice'])) . "' WHERE ItemId = '".$id."'";
            $Result = mysqli_query($Link, $SQL);
        }
        else
        {
            $SQL = "INSERT INTO tblsellprice(ItemId, Price) VALUES (
                '$id',
                '" . strtoupper(trim($_POST['iPrice'])) . "')";
            $Result = mysqli_query($Link, $SQL);
        }
        if(mysqli_num_rows($Result) > 0)
        {?>
            <script>checkers();</script>

            <?php
        }
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT Price FROM tblsupplies WHERE Price = (SELECT MAX(Price) FROM tblsupplies WHERE ItemName = '".$_GET['Id']."' AND STATUS = 'A')";
    $Result = mysqli_query($Link, $SQL);

    $PSQL = "SELECT Price FROM tblsellprice WHERE Price = (SELECT MAX(Price) FROM tblsellprice WHERE ItemId = '".$_GET['Code']."') ";
    $PResult = mysqli_query($Link, $PSQL);
    if(mysqli_num_rows($Result) > 0 || mysqli_num_rows($PResult) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        $Rows = mysqli_fetch_array($PResult);
        $Price = $Row['Price'];
        $CPrice = $Rows['Price'];
        if ($Price < $CPrice) $Price = $CPrice;
    }
    else
    {
        $Price = 0.00;
    }
    $GET = "SELECT * FROM tblinventory WHERE ItemName = '".$_GET['Id']."'";
    $gResult = mysqli_query($Link, $GET);
    if(mysqli_num_rows($gResult) > 0)
    {
        $gRow = mysqli_fetch_array($gResult);
    }
}
?>

<body>
<br/>
<div class="container">
    <h1>Set Price</h1>
    <h3>* Mandatory</h3>
    <h3>Price shown is recommended, you may change it</h3>

    <form method="post" action="">
        <table>
            <caption>Set Price(Per Unit)</caption>
            <tr>
                <td class="move"><label for="iCode">Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="27" value="<?php echo $gRow['Code']; ?>" style="background-color: lightgray" readonly> </td>
            </tr>
            <tr>
                <td class="move"><label for="iPrice">*Price per Unit(RM): </label></td>
                <td><input type="number" name="iPrice" id="iPrice" min="0" step="0.01" value="<?php echo $Price; ?>" required> </td>
            </tr>
        </table>
        <br/>
        <table>
            <tr>
                <td><input type="submit" name="btnSub" class="button" value="Set Price"> </td>
            </tr>
        </table>
    </form>
</div>
</body>
