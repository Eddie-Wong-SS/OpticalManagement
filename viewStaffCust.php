<?php
/**
 Allows viewing of staff and customer accounts
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Registers</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Registers</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="stfName">Name: </label></td>
                    <td><input type="text" name="stfName" id="stfName"> </td>
                </tr>
                <tr>
                    <td><label for="stfGender">Gender: </label></td>
                    <td><select name="stfGender" id="stfGender">
                            <option selected="selected"></option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="stfPos">Account Type: </label></td>
                    <td><select name="stfPos" id="stfPos">
                            <option selected="selected"></option>
                            <option value="Staff">Staff</option>
                            <option value="Customer">Customer</option>
                        </select></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="btnSearch" class="button"></td>
                </tr>
            </table>
        </div>
    </form>
    <?php
    if($_REQUEST['btnSearch'])
    {
        $SQL = "";
        $SQLI = "";
        $_SESSION['SQL'] = $SQL;
        $_SESSION['SQLI'] = $SQLI;
        if($_POST['stfPos'] != "Customer")
        {
            $SQL = "SELECT * FROM tblstaff WHERE tblstaff.StaffIC NOT IN (SELECT IC FROM tbllogin WHERE AccType = 'STAFF') ";

            if(trim($_POST['stfName'])) $SQL .= "AND StaffName LIKE '%".trim($_POST['stfName'])."%' ";
            if(trim($_POST['stfGender'])) $SQL .= "AND Gender LIKE '%".trim($_POST['stfGender'])."%' ";

            $SQL .= "AND tblstaff.Status = 'A' ";
            $SQL .= "ORDER BY StaffName";
        }

        if($_POST['stfPos'] != "Staff")
        {
            $SQLI = "SELECT * FROM tblcustomer";
            $SQLI .= " WHERE ";

            if(trim($_POST['stfName'])) $SQLI .= "AND CustName LIKE '%".trim($_POST['stfName'])."%' ";
            if(trim($_POST['stfGender'])) $SQLI .= "AND Gender LIKE '%".trim($_POST['stfGender'])."%' ";
            $SQLI .= "AND AccType = 'CUSTOMER' ";

            $SQLI .= "AND Status = 'A' ";
            $SQLI .= " ORDER BY CustName";
            $SQLI = str_replace("WHERE AND", "WHERE", $SQLI);
            $SQLI = str_replace("WHERE ORDER","ORDER", $SQLI);
        }

        $_SESSION['SQL'] = $SQL;
        $_SESSION['SQLI'] = $SQLI;
        $id = $_GET['id'];
        echo "<script>location='viewStaffCustResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>
