<?php
/**
 Allows the filtering of glasses and contacts prescriptions
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
    <h1>View Prescriptions</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="pFrom">From Date: </label></td>
                    <td><input type="date" name="pFrom" id="pFrom"> </td>
                </tr>
                <tr>
                    <td><label for="pTo">To Date: </label></td>
                    <td><input type="date" name="pTo" id="pTo" > </td>
                </tr>
                <tr>
                    <td><label for="pBy">Checked By: </label></td>
                    <td><input type="text" name="pBy" id="pBy"></td>
                </tr>
                <tr>
                    <td><label for="pWear">Eyewear: </label></td>
                    <td><select name="pWear" id="pWear">
                            <option selected="selected"></option>
                            <option value="G">Glass</option>
                            <option value="C">Contacts</option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="pEx">Expire Date: </label></td>
                    <td><input type="date" name="pEx" id="pEx"> </td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="pRecent" id="pRecent" >Latest Only? </td>
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
        if(isset($_POST['pRecent']))
        {
            $SQL = "";
            $SQLI = "";
            if($_POST['pWear'] != "G")
            {
                $SQL = "SELECT * FROM tblconmedrec WHERE Eye = 'OD' AND checkDate = (SELECT MAX(checkDate) FROM tblconmedrec WHERE CustIC = '".$_GET['Id']."' AND Status = 'A') ";
            }

            if($_POST['pWear'] != "C")
            {
                $SQLI = "SELECT * FROM tblglassmedrec WHERE Eye = 'OD' AND checkDate = (SELECT MAX(checkDate) FROM tblglassmedrec WHERE CustIC = '".$_GET['Id']."' AND Status = 'A')";
            }

            $_SESSION['SQL'] = $SQL;
            $_SESSION['SQLI'] = $SQLI;
            $id = $_GET['Id'];
            if($_GET['type'] == "PE") $type = "E";
            else if($_GET['type'] == "PD") $type = "D";
            echo "<script>location='viewPrescripResult.php?page=1'</script>";
        }
        else
        {
            $SQL = "";
            $SQLI = "";
            $_SESSION['SQL'] = $SQL;
            $_SESSION['SQLI'] = $SQLI;
            if($_POST['pWear'] != "G")
            {
                $SQL = "SELECT * FROM tblconmedrec";
                $SQL .= " WHERE Eye = 'OD' ";

                if(trim($_POST['pFrom'])) $SQL .= "AND checkDate >= '".trim($_POST['pFrom']). "' ";
                if(trim($_POST['pTo'])) $SQL .= "AND checkDate <= '".trim($_POST['pTo']). "' ";
                if(trim($_POST['pBy'])) $SQL .= "AND checkBy LIKE '%".trim($_POST['pBy'])."%' ";
                if(trim($_POST['pEx'])) $SQL .= "AND expireDate = ".trim($_POST['pEx']). " ";

                $SQL .= "AND Status = 'A' ";
                $SQL .= "AND CustIC =  '".$_GET['Id']."'";
                $SQL .= " ORDER BY CCode";
                $SQL = str_replace("WHERE AND", "WHERE", $SQL);
                $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
            }

            if($_POST['pWear'] != "C")
            {
                $SQLI = "SELECT * FROM tblglassmedrec";
                $SQLI .= " WHERE Eye = 'OD' ";
                if(trim($_POST['pFrom'])) $SQLI .= "AND checkDate >= '".trim($_POST['pFrom']). "' ";
                if(trim($_POST['pTo'])) $SQLI .= "AND checkDate <= '".trim($_POST['pTo']). "' ";
                if(trim($_POST['pBy'])) $SQLI .= "AND checkBy LIKE '%".trim($_POST['pBy'])."%' ";
                if(trim($_POST['pEx'])) $SQLI .= "AND expireDate = ".trim($_POST['pEx']). " ";

                $SQLI .= "AND Status = 'A' ";
                $SQLI .= "AND CustIC =  '".$_GET['Id']."'";
                $SQLI .= " ORDER BY GCode";
                $SQLI = str_replace("WHERE AND", "WHERE", $SQLI);
                $SQLI = str_replace("WHERE ORDER","ORDER", $SQLI);
            }

            $_SESSION['SQL'] = $SQL;
            $_SESSION['SQLI'] = $SQLI;
            $id = $_GET['Id'];
            if($_GET['type'] == "PE") $type = "E";
            else if($_GET['type'] == "PD") $type = "D";
            echo "<script>location='viewPrescripResult.php?id=".$id."&page=1&type=".$type."'</script>";
        }
    }
    ?>
</div>
</body>