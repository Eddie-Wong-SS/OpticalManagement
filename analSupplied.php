<?php
/**
 Selects the suppliers for the selected item
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");

if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblsupplies, tblsupplier WHERE tblsupplies.SuppId = tblsupplier.SuppId AND ItemName = '".$_GET['Id']."' ORDER BY tblsupplies.SuppId";
    $_SESSION['SQL'] = $SQL;
    echo "<script>location='viewSuppliedResult.php?page=1'</script>";
}