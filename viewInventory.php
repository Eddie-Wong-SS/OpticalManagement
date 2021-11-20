<?php
/**
 Allows the filtering of inventory for viewing
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Inventory</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Inventory</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="iCode">Item Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode"> </td>
                </tr>
                <tr>
                    <td><label for="iName">Item Name: </label></td>
                    <td><input type="text" name="iName" id="iName"> </td>
                </tr>
                <tr>
                    <td><label for="iType">Item Type: </label></td>
                    <td><select name="iType" id="iType">
                            <option selected="selected"></option>
                            <option value="Lens">Lens</option>
                            <option value="Frame">Frame</option>
                            <option value="Contact">Contacts</option>
                            <option value="Solution">Contact Fluid</option>
                        </select> </td>
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

        $SQL = "SELECT * FROM tblinventory";
        $SQL .= " WHERE ";

        if(trim($_POST['iCode'])) $SQL .= "AND Code LIKE '%".trim($_POST['iCode'])."%' ";
        if(trim($_POST['iName'])) $SQL .= "AND ItemName LIKE '%".trim($_POST['iName'])."%' ";
        if(trim($_POST['iType'])) $SQL .= "AND ItemType LIKE '%".trim($_POST['iType'])."%' ";

        $SQL .= "AND tblinventory.Status = 'A'";
        $SQl .= "ORDER BY ItemType";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['id'];
        echo "<script>location='viewInventoryResult.php?Id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>