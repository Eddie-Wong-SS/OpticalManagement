<?php
/**
Allows the filtering of suppliers supplying inventory
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
$flag = 0;
?>
<title>Filter Supplies</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Supplier Items</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="iCode">Item Name: </label></td>
                    <td><input type="text" name="iName" id="iCode"> </td>
                </tr>
                <tr>
                    <td><label for="iName">Supplier Name: </label></td>
                    <td><input type="text" name="iName" id="iName"> </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="btnSearch" class="button"> </td>
                </tr>
            </table>
        </div>
    </form>
    <?php
    if($_REQUEST['btnSearch'])
    {
        if(trim($_POST['iName']))
        {
            $Select = "SELECT SuppId FROM tblsupplier WHERE SuppName LIKE '%".trim($_POST['iName'])."%' ";
            $SelectResult = mysqli_query($Link, $Select);
            if(mysqli_num_rows($SelectResult))
            {
                $flag = 1;
                $Row = mysqli_fetch_array($SelectResult);
            }
        }

        $SQL = "SELECT * FROM tblsupplies, tblsupplier";
        $SQL .= " WHERE tblsupplies.SuppId = tblsupplier.SuppId ";

        if(trim($_POST['iName'])) $SQL .= "AND ItemName LIKE '%".trim($_POST['iName'])."%' ";
        if($flag == 1) $SQL .= "AND tblsupplies.SuppId = ".$Row['SuppId']." ";

        $SQL .= "AND tblsupplies.Status = 'A' ";
        $SQL .= "ORDER BY tblsupplier.SuppId ";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['id'];
        echo "<script>location='viewSuppliedResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>