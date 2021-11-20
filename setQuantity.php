<?php
/**
 Allows the user to set the quantity of the selected item
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
            body: 'Setting of the quantity and reorder limit for item code <?php echo $_GET['Id']; ?> was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }
</script>
<title>Set Item Quantity and Limit</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnSub'])
{
    if($_POST['iQuan'] == 0)
    {
        $UpSQL = "UPDATE tblinventory SET Status = 'I' WHERE Code = '".$_GET['Id']."'";
        $UpSQLResult = mysqli_query($Link, $UpSQL);
    }
    else if($_POST['flag'] == 0)
    {
        $Select = "SELECT ItemId FROM tblinventory WHERE Code = '".$_GET['Id']."'";
        $SelectResult = mysqli_query($Link, $Select);

        if(mysqli_num_rows($SelectResult) > 0)
        {
            $RowInfo = mysqli_fetch_array($SelectResult);
            $id = $RowInfo['ItemId'];

            $SQL = "INSERT INTO tblcurrentstock(ItemId, CurQuan) VALUES (
             '$id',
             '" . strtoupper(trim($_POST['iQuan'])) . "'   )";
            $Result = mysqli_query($Link, $SQL);

            $Update = "UPDATE tblinventory SET ReorderLim = '".strtoupper(trim($_POST['iLim']))."' WHERE Code = '".$_GET['Id']."'";
            $UpdateResult = mysqli_query($Link, $Update);
            if($Result && $UpdateResult)
            {?>
                <script>checkers();</script>

                <?php
            }
        }
    }
    else
    {
        $Select = "SELECT ItemId FROM tblinventory WHERE Code = '".$_GET['Id']."'";
        $SelectResult = mysqli_query($Link, $Select);

        if($SelectResult)
        {
            $RowInfo = mysqli_fetch_array($SelectResult);
            $id = $RowInfo['ItemId'];

            $SQL = "UPDATE tblcurrentstock SET CurQuan = '" . strtoupper(trim($_POST['iQuan'])) . "' WHERE ItemId = $id";
            $Result = mysqli_query($Link, $SQL);

            $Update = "UPDATE tblinventory SET ReorderLim = '" . strtoupper(trim($_POST['iLim'])) . "' WHERE Code = '" . $_GET['Id'] . "'";
            $UpdateResult = mysqli_query($Link, $Update);
            if ($Result && $UpdateResult) {
                ?>
                <script>checkers();</script>

                <?php
            }
        }
    }
}
else if($_GET['Id'] != "")
{
    $ReSQL = "SELECT tblitemsold.itemId, Quantity FROM tblitemsold, tblinventory WHERE tblitemsold.itemId = tblinventory.ItemId AND tblinventory.Code = '".$_GET['Id']."'";
    $ReSQLResult = mysqli_query($Link, $ReSQL);
    if(mysqli_num_rows($ReSQLResult) > 0)
    {
        $Total = 0;
        for($i = 0; $i < mysqli_num_rows($ReSQLResult); ++$i)
        {
            $Rowt = mysqli_fetch_array($ReSQLResult);
            $Total += $Rowt['Quantity'];
        }
        $Limit = 0.05 * $Total;
        $Limit = ceil($Limit);
    }
    else $Limit = 10;

    $QSQL = "SELECT CurQuan FROM tblcurrentstock, tblinventory WHERE tblcurrentstock.ItemId = tblinventory.ItemId AND tblinventory.Code = '".$_GET['Id']."'";
    $QSQLResult = mysqli_query($Link, $QSQL);
    if(mysqli_num_rows($QSQLResult) > 0)
    {
        $RowInfo = mysqli_fetch_array($QSQLResult);
        $Quan = $RowInfo['CurQuan'];
        $flag = 1;
    }
    else
    {
        $Quan = 0;
        $flag = 0;
    }
}
?>

<body>
<div class="container">
    <h1>Set Quantity and Reorder Limit</h1>
    <h3>* Mandatory</h3>

    <form method="post" action="">
        <table>
            <caption>Current Quantity</caption>
            <tr>
                <td class="move"><label for="iCode">Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="27" value="<?php echo $_GET['Id']; ?>" style="background-color: lightgray" readonly> </td>
            </tr>
            <tr>
                <td class="move"><label for="iQuan">*Current Quantity: </label></td>
                <td><input type="number" name="iQuan" id="iQuan" value="<?php echo $Quan; ?>" step="1" min="0" required> </td>
            </tr>
        </table>
        <br/>
        <table>
            <caption>Reorder Limit</caption>
            <tr>
                <td class="move"><label for="iCode">Item Code: </label></td>
                <td><input type="text" name="iCode" id="iCode" maxlength="27" value="<?php echo $_GET['Id']; ?>" style="background-color: lightgray" readonly> </td>
            </tr>
            <tr>
                <td class="move"><label for="iLim">*Reorder Limit: </label></td>
                <td><input type="number" name="iLim" id="iLim" value="<?php echo $Limit; ?>" step="1" min="1" title="Minimum of 1 must be selected" required> </td>
            </tr>
        </table>
        <br/>
        <input type="hidden" name="flag" value="<?php echo $flag; ?>">
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Set Quantity" class="button"> </td>
            </tr>
        </table>
    </form>
</div>
</body>
