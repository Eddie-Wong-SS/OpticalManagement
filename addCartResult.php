<?php
/**
 Shows the items that are ready to be sold
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
?>
<title>Inventory Search Result</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<br/>
<div class="container" align="center">
    <?php
    if($_POST['btnSub'])
    {
        echo "<script>location='viewCart.php';</script>";
    }
    else if($_GET['page'] != "")
    {
        $SQL = $_SESSION['SQL'];
        $Result = mysqli_query($Link, $SQL);
        if($Result)
        {
            if(mysqli_num_rows($Result) > 0)
            {
                $page = $_GET['page'];
                $_SESSION['page'] = $page;
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
                        echo "<button><a style='font-size: 21px'  href='addCartResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='addCartResult.php?page=".($page-1)."'>&#60;</a> </button>"; echo $_SESSION['Buyer']?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Item Description</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Item Type</th>
                            <th scope="col">Price(RM)</th>
                            <th scope="col">Quantity</th>
                            <th colspan="100%">Actions</th>
                        </tr>
                        <?php
                        for($i = $minLim ; $i <= $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);

                            echo "<tr>";
                            echo "<td>".($i)."</td>";
                            echo "<td>".$RowInfo['ItemName']."</td>";
                            echo "<td>".$RowInfo['ItemDesc']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['Code']."</td>";
                            echo "<td>".$RowInfo['ItemType']."</td>";
                            echo "<td>".$RowInfo['Price']."</td>";
                            echo "<td>".$RowInfo['CurQuan']."</td>";
                            echo "<td><a class= 'hoverme' href=\"addItem.php?Id=".$RowInfo['Code']."&type=".$RowInfo['ItemType']."\">Add Quantity</a></td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td colspan='100%'><input type='submit' name='btnSub' id='btnSub' value='Proceed to Cart' class='button'/>
                                <input type='button' name='btnBack' id='btnBack' onclick='back()' value='Back' class='button'/> </td>";
                        echo "</tr>"; ?>
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

    function back()
    {
        var loc = "<?php echo $_SESSION['Buyer']; ?>";
        location = 'Sales.php?Reset=No&Id='+loc;
    }
</script>