<?php
/**
 Allows the editing of the item's price
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
        Push.create('Successfully Changed!', {
            body: 'Changing of the price for item code <?php echo $_GET['Id']; ?> was successful',
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
    $Select = "SELECT ItemId FROM tblinventory WHERE Code = '".$_GET['Id']."'";
    $SelectResult = mysqli_query($Link, $Select);
    if($SelectResult)
    {
        $RowInfo = mysqli_fetch_array($SelectResult);
        $id = $RowInfo['ItemId'];

        $SQL = "UPDATE tblsellprice SET Price = '".strtoupper(trim($_POST['iPrice']))."' WHERE ItemId = '".$id."'";
        $Result = mysqli_query($Link, $SQL);
        if($Result)
        {?>
            <script>checkers();</script>

            <?php
        }
    }
}
else if($_GET['Id'] != "")
{
    $SQLM = "SELECT Price FROM tblsupplies WHERE Price = (SELECT MAX(Price) FROM tblsupplies WHERE ItemName = '".$_GET['Id']."' AND STATUS = 'A')";
    $ResultM = mysqli_query($Link, $SQLM);

    $SQL = "SELECT Price FROM tblsellprice, tblinventory WHERE tblinventory.ItemId = tblsellprice.ItemId AND tblsellprice.ID = '".$_GET['num']."' AND Code = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if($Result || $ResultM)
    {
        $Row = mysqli_fetch_array($Result);
        $Rows = mysqli_fetch_array($ResultM);
        $Price = $Rows['Price'];
        $CPrice = $Row['Price'];
        if ($Price < $CPrice) $Price = $CPrice;
    }
    else
    {
        $Price = 0.00;
    }
}
?>

<body>
<div class="container">
    <h1>Set Price</h1>
    <h3>* Mandatory</h3>

    <form method="post" action="">
        <table>
            <caption>Set Price(Per Unit)</caption>
            <tr>
                <td><label for="iCode">Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="27" value="<?php echo $_GET['Id']; ?>" style="background-color: lightgray" readonly> </td>
            </tr>
            <tr>
                <td><label for="iPrice">*Price(RM): </label></td>
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
