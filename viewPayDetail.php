<?php
/**
Shows a payment that has been made for printing
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include('Menu.php');
?>
    <script>
    function back()
    {
        var loc = "<?php echo $_SESSION['page']; ?>";
        location = 'viewPaymentResult.php?page='+loc;
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
                pageTitle: "Receipt",              // add title to print page
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
    <title>Payment</title>
    <link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblreceipt, tblinvoice, tblcustomer WHERE tblinvoice.CustIc = tblcustomer.CustIC AND tblinvoice.invoiceCode = tblreceipt.invoiceCode AND payCode = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        if($Row['Gender'] == 'M') $Gend = "MALE";
        else $Gend = "FEMALE";

        if($Row['bankReceipt'] == "") $loc = "Images/no image available.png";
        else $loc = $Row['bankReceipt'];
        if($Row['PayNo'] == 0) $Row['PayNo'] = "";
    }
}
?>
    <body>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="Printing">
                <h1>Receipt</h1>
            <table>
                <caption>Invoice Details</caption>
                <tr>
                    <td class="move"><label for="iCode">Invoice Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" value="<?php echo $Row['invoiceCode']; ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="iDate">Date Sold: </label></td>
                    <td><input type="date" name="iDate" id="iDate" value="<?php echo $Row['DateSold']; ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="iSold">Sold By: </label></td>
                    <td><input type="text" name="iSold" id="iSold" value="<?php echo $Row['Username']; ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="iTotal">Total Price(RM): </label></td>
                    <td><input type="text" name="iTotal" id="iTotal" value="<?php echo $Row['TotalPrice']; ?>" readonly></td>
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
            </table>
            <br/>
            <table>
                <caption>Payment Details</caption>
                <tr>
                    <td class="move"><label for="pCode">*Payment Code: </label></td>
                    <td><input type="text" name="pCode" id="pCode" value="<?php echo $Row['payCode'] ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pDate">*Paid On: </label></td>
                    <td><input type="text" name="pDate" id="pDate" value="<?php echo $Row['datePaid'] ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pBy">*Collected By: </label></td>
                    <td class="move"><input type="text" name="pBy" id="pBy" value="<?php echo $Row['Collector'] ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pType">Payment Type: </label></td>
                    <td><input type="text" value="<?php echo $Row['payType'] ?>" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="pPaid">*Amount Paid(RM):</label></td>
                    <td><input type="text" name="pPaid" id="pPaid" value="<?php echo $Row['amount'] ?>" readonly> </td>
                </tr>
            </table>
            </div>
            <br/>
            <table>
                <tr>
                    <td><input type="button" name="btnSub" id="btnSub" value="Print Receipt" onclick="printIt();" class="button">
						<input type='button' name='btnBack' id='btnBack' onclick='back()' value='Back' class='button'/></td>
                </tr>
            </table>
        </form>
    </div>
    </body>