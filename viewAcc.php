<?php
/**
 Allows for filtering of accounts
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Accounts</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td class="move" ><label for="IC">IC: </label></td>
                    <td><input type="text" name="IC" id="IC"></td>
                </tr>
                <tr>
                    <td class="move" ><label for="Name">Name: </label></td>
                    <td><input type="text" name="Name" id="Name"> </td>
                </tr>
                <tr>
                    <td class="move" ><label for="Acc">Account Type: </label></td>
                    <td><select name="Acc" id="Acc">
                            <option selected="selected"></option>
                            <option value="STAFF">Staff</option>
                            <option value="MEMBER">Member</option>
                        </select></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="btnSearch" class="button"></td>
                </tr>
            </table>
    </form>
    <?php
    if($_REQUEST['btnSearch'])
    {

        $SQL = "SELECT * FROM tbllogin";
        $SQL .= " WHERE AccType <> 'ADMIN' ";

        if(trim($_POST['IC'])) $SQL .= "AND IC LIKE '%".trim($_POST['IC'])."%' ";
        if(trim($_POST['Name'])) $SQL .= "AND Username LIKE '%".trim($_POST['Name'])."%' ";
        if(trim($_POST['Acc'])) $SQL .= "AND AccType LIKE '%".trim($_POST['Acc'])."%' ";

        if($_GET['type'] == 'M') $SQL .= "AND AccType = 'MEMBER' AND AccType <> 'STAFF' ";
		$SQL .= "AND (Status = 'A' OR Status = 'N') ";
        $SQL .= "ORDER BY Username";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['Id'];
        $type = $_GET['type'];
        echo "<script>location='viewAccResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>