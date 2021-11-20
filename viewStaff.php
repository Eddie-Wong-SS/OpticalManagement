<?php
/**
 Allows the viewing of staff with filter options
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Staff</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Staff</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="stfName">Staff Name: </label></td>
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
                    <td><label for="stfPos">Position: </label></td>
                    <td><select name="stfPos" id="stfPos">
                            <option selected="selected"></option>
                            <option value="Clerk">Clerk</option>
                            <option value="Optometrist">Optometrist</option>
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

        $SQL = "SELECT * FROM tblstaff";
        $SQL .= " WHERE ";

        if(trim($_POST['stfName'])) $SQL .= "AND StaffName LIKE '%".trim($_POST['stfName'])."%' ";
        if(trim($_POST['stfGender'])) $SQL .= "AND Gender LIKE '%".trim($_POST['stfGender'])."%' ";
        if(trim($_POST['stfPos'])) $SQL .= "AND Position LIKE '%".trim($_POST['stfPos'])."%' ";

        $SQL .= "AND Status = 'A'";
        $SQl .= "ORDER BY StaffName";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['id'];
        echo "<script>location='viewStaffResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>
