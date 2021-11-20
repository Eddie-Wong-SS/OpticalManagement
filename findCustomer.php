<?php
/**
 Allows a customer to find their record in the database for various functions
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
if($_SESSION['Login'] == true)
{
    $previous = "javascript:history.go(-1)";
    if(isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }
    echo "<script>alert('You already have an account, you do not need this page');</script>";
    echo "<script>location = '". $previous ."'; </script>";
}
?>
<title>Find Your Record</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<body>
<div class="container">
    <form method="post" action="">
        <div align="center">
            <h1>Find Record</h1>
            <h3>Enter your IC number to find your record</h3>

            <table>
                <caption>Find Account</caption>
                <tr>
                    <td><label for="pIC">Enter Your IC: </label></td>
                    <td><input type="text" name="pIC" id="pIC" maxlength="14" title="Numbers Only" pattern="\d*"> </td>
                </tr>
                <?php
                if($_REQUEST['btnSub'])
                {
                    $flag = 0;
                    $SQL = "SELECT CustIC, CustName FROM tblcustomer WHERE CustIC = '".trim($_POST['pIC'])."'";
                    $Result = mysqli_query($Link, $SQL);

                    if(mysqli_num_rows($Result))
                    {
                        $RowInfo = mysqli_fetch_array($Result);
                        echo "<tr>";
                        echo "<td><label for='pFound'>Name: </label></td>";
                        echo "<td style='color: green'>".$RowInfo['CustName']."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><label for='pFIC'>IC: </label></td>";
                        echo "<td style='color:green'>".$RowInfo['CustIC']."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        if($_GET['id'] == 'E')
                        {
                            echo "<td colspan = '2' align='center'><a class='hoverme' href=\"editPatient.php?Id=".$RowInfo['CustIC']."&type=C\">View Your Record</a></td>";
                        }
                        else if($_GET['id'] == 'M')
                        {
                            echo "<td colspan = '2' align='center'><a class='hoverme' href=\"viewPrescrip.php?Id=".$RowInfo['CustIC']."&type=PE\" >View Your Prescription</a ></td >";
                        }
                        else if($_GET['id'] == 'S')
                        {
                            $SQL = "SELECT IC FROM tbllogin WHERE IC = ".$RowInfo['CustIC'];
                            $Result = mysqli_query($Link, $SQL);
                            if (mysqli_num_rows($Result) > 0) echo "<h1 style='color: red'> You already have an account!</h1>";
                            else echo "<td colspan = '2' align='center'><a class='hoverme' href=\"addMemberAcc.php?Id=".$RowInfo['CustIC']."&type=PE\" >Sign Up</a ></td >";
                        }
                        else if($_GET['id'] == "B")
                        {
                            echo "<td colspan = '2' align='center'><a class='hoverme' href=\"viewSales.php?type=R&name=".$RowInfo['CustIC']."\">View Your Buy History</a></td>";
                        }
                        else if($_GET['id'] == "P")
                        {
                            echo "<td colspan = '2' align='center'><a class='hoverme' href=\"viewPayment.php?name=".$RowInfo['CustIC']."\">View Your Payment History</a></td>";
                        }
                        echo "</tr>";
                        $flag = 1;
                    }
                    else{
                        echo "<tr>";
                        echo "<td colspan='2' style='font-weight: bold; color: red' align='center'>Sorry, the IC you typed in does not exist in the database, contact a staff member to add you in</td>";
                        echo "</tr>";
                    }
                }
                ?>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="btnSub" id="btnSub" value="Check IC" class="button" <?php if($flag == 1) echo "readonly"; ?>> </td>
                </tr>
            </table>
        </div>
    </form>
</div>
</body>
<script type="text/javascript">
    var allLinks = document.getElementsByTagName('a');
    for(var i=0; i < allLinks.length; ++i) {
        if(allLinks[i].getAttribute('class') === "hoverme") {
            allLinks[i].onmouseover = function () {
                this.parentNode.parentNode.style.background = 'linear-gradient(#ADD8E6,#4169E1)';
                this.style.color = 'red';
            };
            allLinks[i].onmouseout = function () {
                this.parentNode.parentNode.style.background= '';
                this.style.color = 'blue';
            };
        }
    }
</script>
