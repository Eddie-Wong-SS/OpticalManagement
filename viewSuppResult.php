<?php
/**
 Shows the results of supplier search
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<script language="javascript">
    function toggle(MaxCheck) {
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
<title>Supplier Search Result</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container" align="center">
    <?php
    if($_REQUEST['btnDelete'])
    {
        // while(list($key,$val) = each($_POST)) each function is deprecated
        foreach($_POST as $key => $val)
        {
            if($key != "chkAll" && $key != "btnDelete")
            {
                $DelEmpSQL = "UPDATE tblsupplier SET Status = 'I' WHERE SuppId = '".$key."'";
                $DelEmpResult = mysqli_query($Link, $DelEmpSQL);
            }
        }
        if($DelEmpResult) echo "<script>alert('Selected record(s) has been deleted');location='viewSuppResult.php?page=1';</script>";
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
                        echo "<button><a style='font-size: 21px'  href='viewSuppResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='viewSuppResult.php?page=".($page-1)."'>&#60;</a> </button>"?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col">No</th>
                            <?php $count = mysqli_num_rows($Result);
                            echo "<th scope='col'><input type='checkbox' name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>"; ?>
                            <th scope="col">Supplier Name</th>
                            <th scope="col">Supplier Id</th>
                            <th scope="col">Contact Person</th>
                            <th scope="col">Contact Number</th>
                            <th scope="col">Email</th>
                            <th colspan="100%">Actions</th>
                        </tr>
                        <?php
                        for($i = $minLim; $i <= $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);
                            echo "<tr>";
                            echo "<td>".($i)."</td>";
                            echo "<td><input type=\"checkbox\" name=\"".$RowInfo['SuppId']."\" id=\"Rec".($i)."\"></td>";
                            echo "<td>".$RowInfo['SuppName']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['SuppId']."</td>";
                            echo "<td>".$RowInfo['ContactPerson']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['ContactNo']."</td>";
                            echo "<td>".$RowInfo['Email']."</td>";
                            echo "<td><a class='hoverme' href=\"editSupplier.php?Id=".$RowInfo['SuppId']."\">Edit</a></td>";
                            echo "<td><a class='hoverme' href=\"addSuppliedItems.php?Id=".$RowInfo['SuppId']."\">Add Supplied Item</a></td>";
                            echo "<td><a class='hoverme' href=\"Purchase.php?Id=".$RowInfo['SuppId']."\">Purchase Items</a></td>";
                            echo "<td><a class='hoverme' href=\"Reorder.php?Id=".$RowInfo['SuppId']."\">Reorder Items</a></td>";
                            echo "</tr>";
                        }
                            echo "<tr>";
                            echo "<td></td>";
                            echo "<th  style='background-color: initial'></td>";
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
