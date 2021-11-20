<?php
/**
 Allows for the filtering of supplier results
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Supplier</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Suppliers</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
        <table border="0" style="table-layout: fixed">
            <caption>Search Filter</caption>
            <tr>
                <td><label for="supName">Supplier Name: </label></td>
                <td><input type="text" name="supName" id="supName"> </td>
            </tr>
            <tr>
                <td><label for="supCon">Contact Person: </label></td>
                <td><Input type="text" name="supCon" id="supName" </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="btnSearch" class="button"> </td>
            </tr>
        </table>
        </div>
    </form>
<?php
    if($_REQUEST['btnSearch'])
    {
        $SQL = "SELECT * FROM tblsupplier";
        $SQL .= " WHERE ";

        if(trim($_POST['supName'])) $SQL .= "AND SuppName LIKE '%".trim($_POST['supName'])."%' ";
        if(trim($_POST['supCon'])) $SQL .= "AND ContactPerson LIKE '%".trim($_POST['supCon'])."%' ";

        $SQL .= "AND Status = 'A'";
        $SQl .= "ORDER BY SuppName";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['id'];
        echo "<script>location='viewSuppResult.php?id=".$id."&page=1'</script>";
    }
?>
</div>
</body>
