<?php
/**
 Shows the quantity and reorder limit of a selected item
 */
error_reporting(1);
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
<title>View Item Price</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<body>
<br/>
<div class="container" align="center">
    <?php
        $SQL = "SELECT * FROM tblcurrentstock,tblinventory WHERE Code = '".$_GET['Id']."' AND tblcurrentstock.ItemId = tblinventory.ItemId AND tblinventory.Status = 'A'";
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
                        echo "<button><a style='font-size: 21px'  href='viewQuantity.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='viewQuantity.php?page=".($page-1)."'>&#60;</a> </button>"?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Item Description</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Item Quantity</th>
                            <th scope="col">Reorder Limit</th>
                            <th colspan="100%">Actions</th>
                        </tr>
                        <?php
                        for($i = $minLim ; $i <= $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);

                            echo "<tr>";
                            echo "<td>".($i)."</td>";
                            echo "<td>".$RowInfo['ItemName']."</td>";
                            echo "<td>".$RowInfo['ItemName']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['Code']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['CurQuan']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['ReorderLim']."</td>";
                            echo "<td><a class= 'hoverme' href=\"setQuantity.php?Id=".$RowInfo['Code']."\">Change Quantity And Limit</a></td>";
                            echo "<td><a class= 'hoverme' href=\"ReorderLow.php?Id=".$RowInfo['Code']."\">Reorder Item</a></td>";
                            echo "</tr>";
                        }
                       ?>
                    </table>
                </form>
            <?php }
            else echo "<h1 style='text-align: center'>Sorry, this item does not have a quantity set yet</h1>";
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
