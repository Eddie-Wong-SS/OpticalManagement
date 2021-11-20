<?php
/**
Allows the viewing and editing of details regarding the items supplied by a supplier
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
        Push.create('Successfully Modified!', {
            body: 'Modification of the details regarding items for supplier <?php echo $_POST['supName']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function toggle(MaxCheck) {
        var i = 0;
        if(document.getElementById('chkAll').checked === true)
        {
            for( i = 0; i <= MaxCheck; i++)
            {
                document.getElementById('Rec' + i).checked = true;
            }
        }

        if(document.getElementById('chkAll').checked === false)
        {
            for( i = 0; i <= MaxCheck; i++)
            {
                document.getElementById('Rec' + i).checked = false;
            }
        }
    }
</script>
<title>Edit Supplier's Items</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['btnDel'])
{
    // while(list($key,$val) = each($_POST)) each function is deprecated
    foreach($_POST['Supp'] as $key)
    {
        if($key != "chkAll" && $key != "btnDel")
        {
            $DelEmpSQL = "DELETE FROM tblsupplies WHERE sID = '".$key."'";
            $DelEmpResult = mysqli_query($Link, $DelEmpSQL);
        }
    }
    if($DelEmpResult)
    {
        $id = $_GET['Id'];
        echo "<script>alert('Selected record(s) has been deleted');location='editSuppliedItems.php?Id=$id';</script>";
    }
}
else if($_REQUEST['btnSub'])
{
    for($i = 0; $i < $_POST['cSupplied']; ++$i)
    {
        if($_POST['Name'.$i] == "" || $_POST['ETA'.$i] == "" || $_POST['Price'.$i] == "")
        {
            echo "<script>alert('Please ensure all Item Names, ETA and Price fields are properly filled');</script>";
        }
        else
        {
            $Check = "SELECT * FROM tblinventory WHERE ItemName = '".$_POST['Name'.$i]."'";
            $CheckResult = mysqli_query($Link, $Check);
            if(mysqli_num_rows($CheckResult) < 1 ) echo "<script>alert('You have not entered the correct name of one of the listed items');</script>";
            else
            {
                $UpSQL = "UPDATE tblsupplies SET
                  ItemName = '" . strtoupper(trim($_POST['Name'.$i])) . "',
                  ETA = '" . strtoupper(trim($_POST['ETA'.$i])) . "',
                  Price = '" . strtoupper(trim($_POST['Price'.$i])) . "',
                  Remark = '" . strtoupper(trim($_POST['Remark'.$i])) . "'
                  WHERE sID = '" . strtoupper(trim($_POST['ID'.$i])) . "'";
                $UpSQLResult = mysqli_query($Link, $UpSQL);
                if($UpSQLResult)
                {?>
                    <script>
                        checkers();
                    </script>
                <?php }
            }
        }
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT SuppName,SuppId FROM tblsupplier WHERE SuppId  = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if($Result)
    {
        $Row = mysqli_fetch_array($Result);
    }
}
?>

<body>
<div class="container">
    <h1>Edit Details for Supplied Items</h1>
    <h3>* Mandatory</h3>

    <form method="post" action="">
        Supplier Name: <input type="text" name="sName" id="sName" value="<?php echo $Row['SuppName']; ?>" style="background-color: lightgray" readonly>
        <br/><br/>
        <?php $SQLI = "SELECT * FROM tblsupplies WHERE SuppId = '".$_GET['Id']."'";
        $SQLIResult = mysqli_query($Link, $SQLI); ?>
        <table cellpadding="6" >
            <caption>Supplied Item(s)</caption>
            <tr>
                <td style="width: 10px"></td>
                <?php $count = mysqli_num_rows($SQLIResult);
                echo "<th style='width: 20px'><input type='checkbox' name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>"; ?>
                <td style="width: 10px">*Item Name</td>
                <td style="width: 10px">*Estimated Arrival Time(Days)</td>
                <td style="width: 10px">*Price(RM) Per Unit</td>
                <td style="width: 10px">Additional Remarks</td>
            </tr>
              <?php if(mysqli_num_rows($SQLIResult) > 0);
              {
                  for($i = 0; $i < mysqli_num_rows($SQLIResult); ++$i)
                  {
                      $Row = mysqli_fetch_array($SQLIResult);
                      $cSupp = $i + 1;?>
                      <tr>
                          <td><?php echo ($i+1); ?></td>
                          <?php echo "<td style='text-align: center'><input type=\"checkbox\" name=\"Supp[]\" id=\"Rec".($i)."\" value=\"".$Row['sID']."\"></td>"; ?>
                          <td><input type="text" name="<?php echo 'Name'.$i;?>" id="<?php echo 'Name'.$i;?>" size="27" maxlength="25" value="<?php echo $Row['ItemName']; ?>" required/></td>
                          <td><input type="number" name="<?php echo 'ETA'.$i;?>" id="<?php echo 'ETA'.$i;?>" step="1" min="0" value="<?php echo $Row['ETA'] ?>" required /></td>
                          <td><input type="number" name="<?php echo 'Price'.$i;?>" id="<?php echo 'Price'.$i;?>" step="0.01" min="0" value="<?php echo $Row['Price'] ?>"  required/></td>
                          <td><textarea name="<?php echo 'Remark'.$i;?>" id="<?php echo 'Remark'.$i;?>" cols="25" rows="5" maxlength="500"><?php echo $Row['Remark'] ?></textarea></td>
                      </tr>
                      <input type="hidden" name="<?php echo 'ID'.$i;?>" value="<?php echo $Row['sID'] ?>">
              <?php    }
                  echo "<tr>";
                  echo "<td></td><td></td>";
                  echo "<td><input type='submit' name='btnDel' id='btnDel' value='Delete Checked Records' class='button'></td>";
                  echo "</tr>";
              }
        ?>
            <input type="hidden" name="cSupplied" value="<?php echo $cSupp ?>">
        </table>
        <table>
            <tr>
                <td><input type="submit" name="btnSub" value="Edit" class="button site"></td>
            </tr>
        </table>
        </form>
</div>
</body>
