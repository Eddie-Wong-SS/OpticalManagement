<?php
/**
 Shows the invoice in greater detail
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Cart Items</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<script>
    function back()
    {
        var loc = "<?php echo $_SESSION['page']; ?>";
        location = 'viewSalesResult.php?page='+loc;
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="printThis-master/printThis.js"></script>
<script>
    function printIt()
    {
        event.preventDefault();
        $('.Printing').printThis({
            debug: true,               // show the iframe for debugging
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
<body>
<br/>
    <?php
    $SQL = "SELECT DISTINCT invoiceCode, tblitemsold.itemId, Code, ItemName, ItemDesc, ItemType Price, SUM(Quantity) As Quan, SUM(fullPrice) As Total FROM tblitemsold, tblinventory WHERE tblitemsold.itemId = tblinventory.ItemId AND invoiceCode = '".$_GET['Id']."'
                 GROUP BY itemId";
        $Result = mysqli_query($Link, $SQL);
        $Info = "SELECT * FROM tblcustomer WHERE CustIC = '".$_GET['cust']."'";
        $iResult = mysqli_query($Link, $Info);
        $Invoice = "SELECT * FROM tblinvoice WHERE invoiceCode = '".$_GET['Id']."'";
        $sResult = mysqli_query($Link, $Invoice);

        if(mysqli_num_rows($iResult) > 0 && mysqli_num_rows($sResult) > 0)
        {
            $Sale = mysqli_fetch_array($sResult);

            $Row = mysqli_fetch_array($iResult);
            if($Row['Gender'] == 'M') $Gend = 'Male';
            else $Gend = 'Female';

            if($Row['AccType'] == "MEMBER") $Discount = "5%";
            else $Discount = "NONE";
            ?>
<div class="container" align="center">
            <form method="post" action="">
            <div class="Printing">
            <table>
                <caption>Invoice Details</caption>
                <tr>
                    <td><label for="iCode">*Invoice Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode" maxlength="25" size="27" value="<?php echo $Sale['invoiceCode']; ?>" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td><label for="iDate">*Date Sold: </label></td>
                    <td><input type="date" name="iDate" id="iDate" value="<?php echo $Sale['DateSold']; ?>" style="background-color: lightgray" readonly> </td>
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
                        <caption>Items Added</caption>
                        <tr>
                            <th scope="col">No</th>
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
                        for($i = 0 ; $i < mysqli_num_rows($Result); ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);

                            echo "<tr>";
                            echo "<td>".($i + 1)."</td>";
                            echo "<td>".$RowInfo['ItemName']."</td>";
                            echo "<td>".$RowInfo['ItemDesc']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['Code']."</td>";
                            echo "<td>".$RowInfo['ItemType']."</td>";
                            echo "<td>".$RowInfo['Price']."</td>";
                            echo "<td>".$RowInfo['Quan']."</td>";
                            echo "<td>".$RowInfo['Total']."</td>";
                            echo "</tr>";

                            $total += $RowInfo['Total'];
                        }
                        echo "<table align='center'>";
                        echo "<tr>";
                        echo "<td>Final Price(RM): <input type='text' name='final' id='final' value='".$total."' style='background-color: lightgray' readonly></td>
                                <td>Discount(RM): <input type='text' name='discount' id='discount' value='".$Sale['Discount']."' readonly></td>";
                        ?>
                    </table>
                <br/>
            </div>
            <?php }
            else
            {
                echo "<h1 style='color: red'>There seems to be something wrong with this invoice...</h1>";
            }?>
            <table>
                    <tr>
                        <td>                        
                             <input type='button' id='btnP' value='Print Invoice' onclick='printIt();' class='button'> <input type='button' name='btnBack' id='btnBack' onclick='back()' value='Back' class='button'/> </td>
                    </tr>
                  </table>
            </form>
      <?php  }
    ?>
</div>
</body>
