<?php
/**
 Shows the prescription result
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
    <script language="javascript">
        function toggle(MaxCheck) {
            var i = 0;
            if(document.getElementById('chkAll').checked === true)
            {
                for( i = 0; i <= MaxCheck; ++i)
                {
                        if(document.getElementById('Rec' + i) == null) continue;
                        document.getElementById('Rec' + i).checked = true;
                }
            }

            if(document.getElementById('chkAll').checked === false)
            {
                for( i = 0; i <= MaxCheck; ++i)
                {
                    if(document.getElementById('Rec' + i) == null) continue;
                    document.getElementById('Rec' + i).checked = false;
                }
            }
        }

</script>
<title>Prescription Search Result</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<br/>
<div class="container" align="center">
    <?php
    if($_REQUEST['btnDel'])
    {
        // while(list($key,$val) = each($_POST)) Each function is deprecated
        foreach($_POST as $key => $val)
        {
            if($key != "chkAll" && $key != "btnDel") {
                $id = explode('_', $key);

                if($id[1] == "glass")
                {
                    $DelEmpSQL = "UPDATE tblglassmedrec SET Status = 'I' WHERE gID = '" . $id[0] . "'";
                    $DelEmpResult = mysqli_query($Link, $DelEmpSQL);
                }
                else if($id[1] == "contact")
                {
                    $DelEmpSQL = "UPDATE tblconmedrec SET Status = 'I' WHERE cID = '".$id[0]."'";
                    $DelEmpResult = mysqli_query($Link, $DelEmpSQL);
                }
                $type = $_GET['type'];
                $id = $_GET['id'];
                echo "<script>location='viewPrescripResult.php?page=1&type=".$type."';</script>";
            }
        }
    }
    else{
        $SQL = $_SESSION['SQL'];
        $SQLI = $_SESSION['SQLI'];
        $Result = mysqli_query($Link, $SQL);
        $ResultI = mysqli_query($Link, $SQLI);
        if($Result || $ResultI)
        {
            if(mysqli_num_rows($Result) > 0 || mysqli_num_rows($ResultI) > 0)
            {
                $page = $_GET['page'];
                $type = $_GET['type'];
                if($type = 'E') $act = "View History";
                else $act = "Edit";
                $maxRec = mysqli_num_rows($Result);
                $maxRec += mysqli_num_rows($ResultI);
                $maxPage = ($maxRec / 25) + 1;
                settype($maxPage, "integer");
                $maxLim = $page * 25;
                $minLim = $maxLim - 24;
                $span = 13;
                if($maxLim > $maxRec) $maxLim = $maxRec;
                ?>
                <form method="post" action="">
                    <?php if($maxPage > 1 && $page != $maxPage)
                        echo "<button><a style='font-size: 21px'  href=viewPrescripResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='viewPrescripResult.php?page=".($page-1)."'>&#60;</a> </button>"?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col"></th>
                            <?php $count = mysqli_num_rows($Result);
                            $count += mysqli_num_rows($ResultI);
                            echo "<th scope='col'><input type='checkbox' name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>"; ?>
                            <th scope="col">IC</th>
                            <th scope="col">Code</th>
                            <th scope="col">Eyewear</th>
                            <th scope="col">Checked Date</th>
                            <th scope="col">Checked By</th>
                            <th scope="col">Expire Date</th>
                            <th colspan="100%">Actions</th>
                        </tr>
                        <?php
                        $count = 1;
                        for($i = ($minLim - 1); $i < $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);
                            if($i >= mysqli_num_rows($Result))
                            {
                                    $RowInfo = mysqli_fetch_array($ResultI);
                                    echo "<tr>";
                                    echo "<td>".($i + 1)."</td>";
                                    echo "<td style='text-align: center'><input type=\"checkbox\" name=\"".$RowInfo['gID']." glass\" id=\"Rec".($i)."\"></td>";
                                    echo "<td style='text-align: center'>".$RowInfo['CustIC']."</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['GCode']."</td>";
                                    echo "<td style='text-align: center'>Glasses</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['checkDate']."</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['checkBy']."</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['expireDate']."</td>";
                                    echo "<td><a class= 'hoverme' href=\"editGlassPrescrip.php?Id=".$RowInfo['CustIC']."&date=".$RowInfo['checkDate']."\">$act</a></td>";
                                    echo "</tr>";
                                    ++$count;
                            }
                            else
                            {

                                    echo "<tr>";
                                    echo "<td>".($i + 1)."</td>";
                                    echo "<td style='text-align: center'><input type=\"checkbox\" name=\"".$RowInfo['cID']." contact\" id=\"Rec".($i)."\"></td>";
                                    echo "<td style='text-align: center'>".$RowInfo['CustIC']."</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['CCode']."</td>";
                                    echo "<td style='text-align: center'>Contact Lens</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['checkDate']."</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['checkBy']."</td>";
                                    echo "<td style='text-align: center'>".$RowInfo['expireDate']."</td>";
                                    echo "<td><a class= 'hoverme' href=\"editContactPrescrip.php?Id=".$RowInfo['CustIC']."&date=".$RowInfo['checkDate']."\">$act</a></td>";
                                    echo "</tr>";
                                    ++$count;

                            }
                        }

                            echo "<tr>";
                            echo "<td></td>";
                            echo "<th  style='background-color: initial'></td>";
                            echo "<td align=\"center\" colspan=\"8\"><input type=\"submit\" name=\"btnDel\" value=\"Delete Records\" id=\"Register\" onclick='return confirm(\"This will delete the selected records. Continue?\");' class='button'></td>";
                            echo "</tr>";
                        ?>
                    </table>
                </form>
            <?php }
        }
        else echo "<h1 style='color: red'>Sorry, this patient does not have any prescription currently in the database/matches your filters</h1>";
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