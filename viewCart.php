<?php
/**
 Shows the items selected by the user
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include ('Email.php');
include("database.php");
if($_POST['btnSub']) include ("Menu.php");
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

    function back()
    {
        var loc = "<?php echo $_SESSION['page']; ?>";
        location = 'addCartResult.php?page='+loc;
    }

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="printThis-master/printThis.js"></script>
<script>
    function printIt()
    {
        $('.Printing').printThis({
            debug: false,               // show the iframe for debugging
            importCSS: true,            // import page CSS
            importStyle: true,         // import style tags
            printContainer: true,       // grab outer container as well as the contents of the selector
            loadCSS: "http://localhost/php/Default Theme.css",  // path to additional css file - use an array [] for multiple
            pageTitle: "Invoice",              // add title to print page
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
<title>Cart Items</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<br/>
<div class="container" align="center" id="container">
    <?php
    if($_REQUEST['btnCan'])
    {
        $_SESSION['Buyer'] = "";
        $_SESSION['iDate'] = "";
        $_SESSION['inCode'] = "";
        $_SESSION['page'] = "";

        $Remove = "DELETE FROM tblitemsold WHERE NOT EXISTS(SELECT 1 FROM tblinvoice WHERE tblinvoice.invoiceCode = tblitemsold.invoiceCode)";
        $remResult = mysqli_query($Link, $Remove);

        if($remResult)
        {
            echo "<script>alert('Transaction has successfully been cancelled!');</script>";
            echo "<script>location='Main Page.php';</script>";
        }
    }
    else if($_REQUEST['btnDelete'])
    {
        // while(list($key,$val) = each($_POST)) each function is deprecated
        foreach($_POST as $key => $val)
        {
            if($key != "chkAll" && $key != "btnDelete")
            {
                $DelEmpSQL = "DELETE FROm tblitemsold WHERE ID = '".$key."'";
                $DelEmpResult = mysqli_query($Link, $DelEmpSQL);
            }
        }
        if($DelEmpResult) echo "<script>alert('Selected record(s) have been deleted');location='viewCart.php';</script>";
    }
    else{
        $SQL = "SELECT * FROM tblitemsold, tblinventory WHERE tblitemsold.itemId = tblinventory.ItemId AND invoiceCode = '".$_SESSION['inCode']."'
                ORDER BY ItemType";
        $Result = mysqli_query($Link, $SQL);
        $Info = "SELECT * FROM tblcustomer WHERE CustIC = '".$_SESSION['Buyer']."'";
        $iResult = mysqli_query($Link, $Info);
        if(mysqli_num_rows($iResult) > 0)
        {

            $Row = mysqli_fetch_array($iResult);
            if($Row['Gender'] == 'M') $Gend = 'Male';
            else $Gend = 'Female';

            if($Row['AccType'] == "MEMBER") $Discount = "5%";
            else $Discount = "NONE";
            ?>
            <form method="post" action="">
            <table>
                <caption>Invoice Details</caption>
                <tr>
                    <td><label for="iCode">*Invoice Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" value="<?php echo $_SESSION['inCode']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td><label for="iDate">*Date Sold: </label></td>
                    <td><input type="date" name="iDate" id="iDate" value="<?php echo $_SESSION['date']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Patient Details</caption>
                <tr>
                    <td class="move"><label for="pIC">Patient IC: </label></td>
                    <td><input type="text" name="pIC" id="pIC" value="<?php echo $Row['CustIC']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pName">Patient Name: </label></td>
                    <td><input type="text" name="pName" id="pName" value="<?php echo $Row['CustName']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pGen">Gender: </label></td>
                    <td><input type="text" name="pGen" id="pGen" value="<?php echo $Gend; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pCNo">Contact NO: </label></td>
                    <td><input type="text" name="pCNo" id="pCNo" value="<?php echo $Row['Phone']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pEm">Email: </label></td>
                    <td><input type="text" name="pEm" id="pEm" value="<?php echo $Row['Email']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pAcc">Account Type: </label></td>
                    <td><input type="text" name="pAcc" id="pAcc" value="<?php echo $Row['AccType']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pDisc">Discount?</label></td>
                    <td><input type="text" name="pDisc" id="pDisc" value="<?php echo $Discount; ?>" style="background-color: lightgray" readonly></td>
                </tr>
            </table>
            <br/>
            <?php
            if(mysqli_num_rows($Result) > 0)
            {
                $page = $_GET['page'];
                ?>
                    <table align="center">
                        <caption>Items In Cart</caption>
                        <tr>
                            <th scope="col">No</th>
                            <?php
                            $count = mysqli_num_rows($Result);
                            echo "<th scope='col' style='background-color: inherit'><input type=\"checkbox\" name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>";
                            ?>
                            <th scope="col">Item Name</th>
                            <th scope="col">Item Description</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Item Type</th>
                            <th scope="col">Price(RM)</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Price(RM)</th>
                        </tr>
                        <?php
                        $total = 0;
                        $discount = 0.00;
                        for($i = 0 ; $i < mysqli_num_rows($Result); ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);
                            if($Row['AccType'] == "MEMBER")
                            {
                                $disc = number_format($RowInfo['fullPrice'] * 0.05, 2);
                                $discount += floatval($disc);
                                $RowInfo['fullPrice'] = number_format($RowInfo['fullPrice'] * 0.95, 2);
                            }

                            echo "<tr>";
                            echo "<td>".($i + 1)."</td>";
                            echo "<td style='text-align: center'><input type=\"checkbox\" name=\"".$RowInfo['ID']."\" id=\"Rec".($i + 1)."\"></td>";
                            echo "<td>".$RowInfo['ItemName']."</td>";
                            echo "<td>".$RowInfo['ItemDesc']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['Code']."</td>";
                            echo "<td>".$RowInfo['ItemType']."</td>";
                            echo "<td>".$RowInfo['Price']."</td>";
                            echo "<td>".$RowInfo['Quantity']."</td>";
                            echo "<td>".$RowInfo['fullPrice']."</td>";
                            echo "</tr>";

                            $total += $RowInfo['fullPrice'];
                        }
                        echo "<tr>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td align=\"center\" colspan=\"8\"><input type=\"submit\" name=\"btnDelete\" value=\"Delete checked items\" id=\"Delete\" class='button'></td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "<br/>";
                        echo "<table align='center'>";
                        echo "<tr>";
                        echo "<td>Final Price(RM): <input type='text' name='final' id='final' value='".$total."' style='background-color: lightgray' readonly></td>
                                <td>Discount(RM): <input type='text' name='discount' id='discount' value='".$discount."' readonly></td></tr>";
                        echo "<tr style='background-color: transparent'>
                                <td colspan='100%'><input type='submit' name='btnSub' id='btnSub' value='Confirm Sale' onclick='return confirm(\"This will record this transaction into the database, Proceed?\")' class='button' /></td>
                              </tr>";?>
                    </table>
                <br/>
            <?php }
            else
            {
                echo "<h1 style='color: red'>You have not added any items yet!</h1>";
            }
            echo "<table>
                    <tr>
                        <td><input type='submit' name='btnCan' id='btnCan' onclick='return confirm(\"Are you sure you wish to cancel this transaction?\")' value='Cancel Sale' class='button' />                          
                             <input type='button' name='btnBack' id='btnBack' onclick='back()' value='Back' class='button'/> </td>
                    </tr>
                  </table>";
            echo "</form>";
        }
    }
    ?>
</div>
<br style="line-height: 100px"/><br/><br/><br/><br/><br/><br/>
</body>
<?php
if($_REQUEST['btnSub'])
{
    $Check = "<div class='Printing'> <table>
                <caption>Invoice Details</caption>
                <tr>
                    <td><label for=\"iCode\">*Invoice Code: </label></td>
                    <td><input type=\"text\" name=\"iCode\" id=\"iCode\" maxlength=\"25\" size=\"27\" value='".strtoupper(trim($_POST['iCode']))."'> </td>
                </tr>
                <tr>
                    <td><label for=\"iDate\">*Date Sold: </label></td>
                    <td><input type=\"date\" name=\"iDate\" id=\"iDate\" value='".strtoupper(trim($_POST['iDate']))."'> </td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Customer Details</caption>
                <tr>
                    <td class=\"move\"><label for=\"pIC\">Patient IC: </label></td>
                    <td><input type=\"text\" name=\"pIC\" id=\"pIC\" value='".strtoupper(trim($_POST['pIC']))."'> </td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"pName\">Patient Name: </label></td>
                    <td><input type=\"text\" name=\"pName\" id=\"pName\" value='".strtoupper(trim($_POST['pName']))."'> </td>
                </tr>            
                <tr>
                    <td class=\"move\"><label for=\"pAcc\">Account Type: </label></td>
                    <td><input type=\"text\" name=\"pAcc\" id=\"pAcc\" value='".strtoupper(trim($_POST['pAcc']))."'> </td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"pDisc\">Discount?</label></td>
                    <td><input type=\"text\" name=\"pDisc\" id=\"pDisc\" value='".strtoupper(trim($_POST['pDisc']))."'></td>
                </tr>
            </table>
            <br/>
            <table align=\"center\">
                        <caption>Items Added</caption>
                        <tr>
                            <th scope=\"col\">No</th>                         
                            <th scope=\"col\">Item Name</th>
                            <th scope=\"col\">Item Description</th>
                            <th scope=\"col\">Item Code</th>
                            <th scope=\"col\">Item Type</th>
                            <th scope=\"col\">Price(RM)</th>
                            <th scope=\"col\">Quantity</th>
                            <th scope=\"col\">Total Price(RM)</th>
                        </tr>";

    $Insert = "INSERT INTO tblinvoice(invoiceCode, CustIc, Username, TotalPrice, Discount, DateSold) VALUES (
               '" . strtoupper(trim($_SESSION['inCode'])) . "',
               '" . strtoupper(trim($_SESSION['Buyer'])) . "',
               '" . strtoupper(trim($_SESSION['Username'])) . "',
               '" . strtoupper(trim($_POST['final'])) . "',
               '" . strtoupper(trim($_POST['discount'])) . "',
               '" . strtoupper(trim($_SESSION['date'])) . "')";
    $iInsert = mysqli_query($Link, $Insert);

     $Get = "SELECT * FROm tblitemsold WHERE invoiceCode = '" . strtoupper(trim($_SESSION['inCode'])) . "'";
     $gResult = mysqli_query($Link, $Get);
     if(mysqli_num_rows($gResult) > 0)
     {
         for($f = 0; $f < mysqli_num_rows($gResult); ++$f)
         {
             $Reduce = mysqli_fetch_array($gResult);

             $Update = "UPDATE tblcurrentstock SET CurQuan = (CurQuan - ".$Reduce['Quantity'].") WHERE ItemId = '" . $Reduce['itemId'] . "'";
             $uResult = mysqli_query($Link, $Update);

         }
    $Add = "SELECT * FROM tblitemsold, tblinventory WHERE tblitemsold.itemId = tblinventory.ItemId AND invoiceCode = '".$_SESSION['inCode']."'
                    ORDER BY ItemType";
    $aResult = mysqli_query($Link, $Add);

    for($i = 0 ; $i < mysqli_num_rows($aResult); ++$i)
    {
        $RowInfo = mysqli_fetch_array($aResult);

        $Check .= "<tr>";
        $Check .= "<td>".($i + 1)."</td>";
        $Check .= "<td>".$RowInfo['ItemName']."</td>";
        $Check .= "<td>".$RowInfo['ItemDesc']."</td>";
        $Check .= "<td style='text-align: center'>".$RowInfo['Code']."</td>";
        $Check .= "<td>".$RowInfo['ItemType']."</td>";
        $Check .= "<td>".$RowInfo['Price']."</td>";
        $Check .= "<td>".$RowInfo['Quantity']."</td>";
        $Check .= "<td>".$RowInfo['fullPrice']."</td>";
        $Check .= "</tr>";
    }
    $Check .=  "<tr>";
    $Check .=  "<td colspan='100%'>Final Price(RM): <input type='text' name='final' id='final' value='".$total."' size='10' readonly>
                                Discount(RM): <input type='text' name='discount' id='discount' value='".$discount."' size='10' readonly></td></tr>";
    $Check .= "</table></div>";
    echo $Check;
    echo "<script>printIt();</script>";
    sendEmail("Your Invoice", "Your purchases has been successfully performed and recorded<br/><br/>".$Check."<br/><br/>Not what you purchased? Contact us", $_POST['pEm']);
}
else
{
    echo "<h3>Sorry, there was an error while performing the transaction, please try again</h3>";
}
}

?>
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
