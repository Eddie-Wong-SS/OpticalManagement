<?php
/**
 Allows for the filtering of reorder forms
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Reorders</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<body>
<div class="container">
    <h1>Filter Search Results</h1>
    <form method="post" action="">
        <table >
            <caption>Filter</caption>
            <tr>
                <td><label for="rCode">Reorder Code: </label></td>
                <td><input type="text" name="rCode" id="rCode"> </td>
            </tr>
            <tr>
                <td><label for="rSupp">Supplier Name: </label></td>
                <td><input type="text" name="rSupp" id="rSupp"> </td>
            </tr>
            <tr>
                <td><label for="rIName">Item Name: </label></td>
                <td><input type="text" name="rIName" id="rIName"> </td>
            </tr>
            <tr>
                <td colspan="100%">Order Date: </td>
            </tr>
            <tr>
                <td><label for="fDate">From: </label></td>
                <td><input type="date" name="fDate" id="fDate"> </td>
            </tr>
            <tr>
                <td><label for="tDate">To: </label></td>
                <td><input type="date" name="tDate" id="tDate"> </td>
            </tr>
            <tr>
                <td colspan="100%" align="center"><input type="submit" name="btnSub" id="btnSub" value="Search" class="button"> </td>
            </tr>
        </table>
    </form>
</div>
</body>
<?php
if($_REQUEST['btnSub'])
{
    $SQL = "SELECT * FROM tblreorder";
    $SQL .= " WHERE ";

    if(trim($_POST['rCode'])) $SQL .= "AND reCode LIKE '%".trim($_POST['rCode'])."%' ";
    if(trim($_POST['rSupp'])) $SQL .= "AND SuppName LIKE '%".trim($_POST['rSupp'])."%' ";
    if(trim($_POST['rIName'])) $SQL .= "AND ItemName LIKE '%".trim($_POST['rIName'])."%' ";
    if(trim($_POST['fDate'])) $SQL .= "AND OrderDate >= '".trim($_POST['fDate'])."' ";
    if(trim($_POST['tDate'])) $SQL .= "AND OrderDate <= '".trim($_POST['tDate'])."' ";

    $SQL .= "ORDER BY SuppName";
    $SQL = str_replace("WHERE AND", "WHERE", $SQL);
    $SQL = str_replace("WHERE ORDER","ORDER", $SQL);

    $_SESSION['SQL'] = $SQL;
    echo "<script>location='viewReordersResult.php?page=1'</script>";
}

