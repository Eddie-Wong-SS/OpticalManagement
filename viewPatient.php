<?php
/**
 Allows for the filtering of specific patient results
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Patient</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Patient</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="pName">Patient Name: </label></td>
                    <td><input type="text" name="pName" id="pName"> </td>
                </tr>
                <tr>
                    <td><label for="pGender">Gender: </label></td>
                    <td><select name="pGender" id="pGender">
                            <option selected="selected"></option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select></td>
                </tr>
                <?php if($_GET['id'] != 'A')
                {?>
                <tr>
                    <td><label for="pType">Account Type: </label></td>
                    <td><select name="pType" id="pType">
                            <option selected="selected"></option>
                            <option value="Customer">Customer</option>
                            <option value="Member">Member</option>
                        </select></td>
                </tr>
                <?php }
                ?>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="btnSearch" class="button"></td>
                </tr>
            </table>
        </div>
    </form>
    <?php
    if($_REQUEST['btnSearch'])
    {

        $SQL = "SELECT * FROM tblcustomer";
        $SQL .= " WHERE ";

        if(trim($_POST['pName'])) $SQL .= "AND CustName LIKE '%".trim($_POST['pName'])."%' ";
        if(trim($_POST['pGender'])) $SQL .= "AND Gender LIKE '%".trim($_POST['pGender'])."%' ";
        if($_GET['id'] != 'A'){ if(trim($_POST['pType'])) $SQL .= "AND AccType LIKE '%".trim($_POST['pType'])."%' ";}
        else $SQL .= "AND AccType = 'CUSTOMER' ";

        $SQL .= "AND Status = 'A'";
        $SQl .= "ORDER BY CustName";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['id'];
        echo "<script>location='viewPatientResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>