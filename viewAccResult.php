<?php
/**
 Shows the staff account results
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<script language="javascript">
    function toggle(MaxCheck)
    {
        var i = 1;
        if(document.getElementById('chkAll').checked === true)
        {
            for( i = 1; i <= MaxCheck; i++)
            {
                document.getElementById('Rec' + i).checked = true;
            }
        }

        if(document.getElementById('chkAll').checked === false)
        {
            for( i = 1; i <= MaxCheck; i++)
            {
                document.getElementById('Rec' + i).checked = false;
            }
        }
    }

</script>
<title>Account Search Result</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<br />
<div class="container" align="center">
    <?php
    if($_REQUEST['btnDelete'])
    {
        // while(list($key,$val) = each($_POST)) Each function is deprecated
        foreach($_POST as $key => $val)
        {
            if($key != "chkAll" && $key != "btnDelete")
            {
                $DelEmpSQL = "UPDATE tbllogin SET Status = 'I' WHERE IC = '".$key."'";
                $DelEmpResult = mysqli_query($Link, $DelEmpSQL);

                $DelSQL = "UPDATE tblcustomer SET AccType = 'CUSTOMER' WHERE CustIC = '".$key."'";
                $DelSQLResult = mysqli_query($Link, $DelSQL);
            }
        }
        if($DelEmpResult) echo "<script>alert('Selected record(s) has been deleted');location='viewAccResult.php?page=1';</script>";
    }
    else{
        $SQL = $_SESSION['SQL'];
        $Result = mysqli_query($Link, $SQL);
        if($Result)
        {
            if(mysqli_num_rows($Result) > 0)
            {
                $page = $_GET['page'];
                $maxRec = mysqli_num_rows($Result);
                $maxPage = ($maxRec / 25) + 1;
                settype($maxPage, "integer");
                $maxLim = $page * 25;
                $minLim = $maxLim - 24;
                $span = 13;
                if($maxLim > $maxRec) $maxLim = $maxRec;
                ?>
                <form method="post" action="">
                    <?php if($maxPage > 1 && $page != $maxPage)
                        echo "<button><a style='font-size: 21px'  href='viewAccResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='viewAccResult.php?page=".($page-1)."'>&#60;</a> </button>"?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col">No</th>
                            <?php $count = mysqli_num_rows($Result);
                            echo "<th><input type='checkbox' name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>"; ?>
                            <th scope="col">Username</th>
                            <th scope="col">IC</th>
                            <th scope="col">Account Type</th>
                            <th colspan="100%">Actions</th>
                        </tr>
                        <?php
                        for($i = $minLim ; $i <= $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);
                            echo "<tr>";
                            echo "<td>".($i)."</td>";
                            echo "<td style='text-align: center'><input type=\"checkbox\" name=\"".$RowInfo['IC']."\" id=\"Rec".($i)."\"></td>";
                            echo "<td>".$RowInfo['Username']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['IC']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['AccType']."</td>";
                            if($RowInfo['Status'] == 'A')
                                echo "<td><a class='hoverme' href=\"editPW.php?Id=".$RowInfo['IC']."\">Change Password</a></td>";
                            if($RowInfo['Status'] == 'N')
                                echo "<td><a class='hoverme' href=\"viewAccDetails.php?Id=".$RowInfo['IC']."\">Print New Account Details</a></td>";
                            echo "</tr>";
                        }
                            echo "<tr>";
                            echo "<td></td>";
                            echo "<th style='background-color: initial'></td>";
                            echo "<td align=\"center\" colspan=\"100%\"><input type=\"submit\" name=\"btnDelete\" value=\"Delete checked items\" onclick='return confirm(\"This will delete the selected records. Proceed?\");' id=\"Delete\" class='button'></td>";
                            echo "</tr>";
                        ?>
                    </table>
                </form>
            <?php }
        }
    }
    ?>
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
