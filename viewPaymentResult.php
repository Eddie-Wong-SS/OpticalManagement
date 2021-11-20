<?php
/**
 Shows the payments made
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="printThis-master/printThis.js"></script>
<script>
    function printIt()
    {
        event.preventDefault();
        $('.Printing').printThis({
            debug: false,               // show the iframe for debugging
            importCSS: true,            // import page CSS
            importStyle: true,         // import style tags
            printContainer: true,       // grab outer container as well as the contents of the selector
            loadCSS: "http://localhost/php/Default Theme.css",  // path to additional css file - use an array [] for multiple
            pageTitle: "Payments Report",              // add title to print page
            removeInline: false,        // remove all inline styles from print elements
            printDelay: 333,            // variable print delay
            header: null,               // prefix to html
            footer: null,               // postfix to html
            base: false ,               // preserve the BASE tag, or accept a string for the URL
            formValues: true,           // preserve input/form values
            canvas: false,              // copy canvas elements (experimental)
            doctypeString: "",       // enter a different doctype for older markup
            removeScripts: false,       // remove script tags from print content
            copyTagClasses: false,   // copy classes from the html &amp; body tag
        });
    }
</script>
<title>View Payments</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<body>
<br/>
<div class="container" align="center">
    <?php
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
            echo "<button><a style='font-size: 21px'  href='viewPaymentResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
        &nbsp;<?php if($page <= $maxPage && $page != 1)
            echo "<button><a style='font-size: 21px'  href='viewPaymentResult.php?page=".($page-1)."'>&#60;</a> </button>"?>
        <div class="Printing">
        <table align="center">
            <tr>
                <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
            </tr>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Invoice Code</th>
                        <th scope="col">Payment Code</th>
                        <th scope="col">Customer IC</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Collected By</th>
                        <th scope="col">Payment Type</th>
                        <th scope="col">Date Paid</th>
                        <th scope="col">Amount(RM)</th>
                        <th scope="col">Actions</th>
                    </tr>
                    <?php
                    for($i = $minLim ; $i <= $maxLim; ++$i)
                    {
                        $RowInfo = mysqli_fetch_array($Result);
                        echo "<tr>";
                        echo "<td>".$i."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['invoiceCode']."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['payCode']."</td>";
                        echo "<td>".$RowInfo['CustIC']."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['CustName']."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['Collector']."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['payType']."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['datePaid']."</td>";
                        echo "<td style='text-align: center'>".$RowInfo['amount']."</td>";
                        echo "<td><a class= 'hoverme' href=\"viewPayDetail.php?Id=".$RowInfo['payCode']."\">View Receipt Details</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
             <table>
            <tr>
                <td><input type="button" name="btnPrint" id="btnPrint" onclick="printIt();" value="Print" class="button"> </td>
            </tr>
            </table>
            </form>
        <?php }
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
