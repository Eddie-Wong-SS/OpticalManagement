<?php
/**
 Records payments made for a specified invoice
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include('Menu.php');
include("Email.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Edited!', {
            body: 'Payment for invoice <?php echo $_POST['iCode']; ?> was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function enableThem()
    {
        var type = document.getElementById('pType').value;
        if(type === "Cash")
        {
            document.getElementById('image').disabled = true;
            document.getElementById('image').required = false;
            document.getElementById('image').value = "";
            document.getElementById('uploadPreview').src = "Images/no%20image%20selected.gif";
            document.getElementById('pNO').disabled = true;
            document.getElementById('pNO').required = false;
            document.getElementById('pNO').value = "";
        }
        else
        {
            document.getElementById('pNO').disabled = false;
            document.getElementById('pNO').required = true;
            if(type === "Bank Transfer")
            {
                document.getElementById('image').disabled = false;
                document.getElementById('image').required = true;
            }
            else
            {
                document.getElementById('image').disabled = true;
                document.getElementById('image').required = false;
                document.getElementById('image').value = "";
            }
        }
    }

    function readURL()
    {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("image").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    }

    function compPrice(i)
    {
        var paid = parseFloat(document.getElementById(i).value);
        var owed = parseFloat(document.getElementById('pAO').value);
        if(paid > owed)
        {
            document.getElementById(i).style = "border-color: red";
            document.getElementById('pAO').style = "border-color: red";
            document.getElementById('btnSub').disabled = true;
            alert('The amount paid exceeds the amount owed!');
        }
        else
        {
            document.getElementById(i).style = "border-color: green";
            document.getElementById('pAO').style = "border-color: green";
            document.getElementById('btnSub').disabled = false;
        }
    }

    window.onload = function()
    {
        var paid = parseFloat(document.getElementById('pAO').value);
        if(paid === 0)
        {
            document.getElementById('btnSub').disabled = true;
            alert('Payment has been fully made for this invoice, no need for further payments');
        }
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
    $SQL = "SELECT * FROM tblinvoice, tblcustomer WHERE tblcustomer.CustIC = tblinvoice.CustIc AND tblinvoice.invoiceCode = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
        if($Row['Gender'] == 'M') $Gend = 'Male';
        else $Gend = 'Female';

        $payable = $Row['DateSold'];

        $Get = "SELECT * FROM tblreceipt WHERE tblreceipt.invoiceCode = '".$_GET['Id']."'";
        $gResult = mysqli_query($Link, $Get);
        $total = 0.00;
        $owed = $Row['TotalPrice'];
        if(mysqli_num_rows($gResult) > 0)
        {

            for($i = 0; $i < mysqli_num_rows($gResult); ++$i)
            {
                $RowInfo = mysqli_fetch_array($gResult);
                $total += $RowInfo['amount'];
            }

        }
        $total = $owed - $total;
    }
}
?>
<body>
<div class="container">
    <h1>Payment For Invoice</h1>
    <h3>*Mandatory</h3>
    <form method="post" enctype="multipart/form-data">
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
            <td><input type="text" name="pCode" id="pCode" placeholder="R01" maxlength="25" size="27" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="pDate">*Paid On: </label></td>
            <td><input type="date" name="pDate" id="pDate" max="<?php echo $Mtime; ?>" min="<?php echo $payable ?>" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="pBy">*Collected By: </label></td>
            <td class="move"><input type="text" name="pBy" id="pBy" placeholder="Enter collector's username" maxlength="35" size="37" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="pAO">Amount Owed(RM):</label></td>
            <td><input type="text" name="pAO" id="pAO" value="<?php echo $total; ?>" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="pType">Payment Type: </label></td>
            <td><select name="pType" id="pType" onchange="enableThem()">
                    <option selected="selected">Cash</option>
                    <option>Credit Card</option>
                    <option>PayPal</option>
                    <option>Bank Transfer</option>
                </select> </td>
        </tr>
        <tr>
            <td class="move"><label for="pPaid">*Amount Paid(RM):</label></td>
            <td><input type="number" name="pPaid" id="pPaid" step="0.01" min="0" onblur="compPrice(this.id)" required> </td>
        </tr>
        <tr>
            <td class="move"><label for="pNO">*Credit/PayPal/Bank Number(Not required if using cash): </label></td>
            <td><input type="number" name="pNO" id="pNO" placeholder="Numbers only" step="1" min="0" disabled> </td>
        </tr>
        <tr>
            <td class="move"><label for="image">*Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button" disabled></td>
            <td rowspan="7" align="center"><img src="Images/no%20image%20selected.gif" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <td><input type="submit" name="btnSub" id="btnSub" value="Pay" onclick="return confirm('This will enter the money into the system: Continue?')" class="button"> </td>
        </tr>
    </table>
    </form>
</div>
</body>
<br style="line-height: 100px"/><br/><br/><br/><br/><br/><br/>
<?php
if($_REQUEST['btnSub'])
{
    if($_POST['pType'] == "Cash")
    {
        $Num = 0;
        $Loc = "Images/no image available.png";
    }
    else
    {
        $Num = $_POST['pNO'];
        $Loc = "Images/";
        $Loc = $Loc ."Receipt".$_POST['iCode'].".png";
        move_uploaded_file($_FILES['image']['tmp_name'], $Loc);
    }

    $Check = "SELECT payCode FROM tblreceipt WHERE payCode = '".$_POST['pCode']."'";
    $cResult = mysqli_query($Link, $Check);
    if(mysqli_num_rows($cResult) > 0)
    {
        echo "<script>alert('Sorry, this code has been used already, please select another');</script>";
    }
    else
    {
       $Inspect = "SELECt Username FROm tbllogin WHERE (AccType = 'STAFF' OR AccType = 'ADMIN') AND Status = 'A' AND Username = '". strtoupper(trim($_POST['pBy']))."'";
        $nResult = mysqli_query($Link, $Inspect);
        if(mysqli_num_rows($nResult) > 0)
        {
            $Insert = "INSERT INTO tblreceipt(payCode, invoiceCode, CustIC, Collector, payType, PayNo, bankReceipt, datePaid, amount) VALUES (
                  '" . strtoupper(trim($_POST['pCode'])) . "',
                  '" . strtoupper(trim($_POST['iCode'])) . "',
                  '" . strtoupper(trim($_POST['pIC'])) . "',
                  '" . strtoupper(trim($_POST['pBy'])) . "',
                  '" . strtoupper(trim($_POST['pType'])) . "',
                  '" . strtoupper(trim($Num)) . "',
                  '" . strtoupper(trim($Loc)) . "',
                  '" . strtoupper(trim($_POST['pDate'])) . "',
                  '" . strtoupper(trim($_POST['pPaid'])) . "')";
            $iResult = mysqli_query($Link, $Insert);
            if(($_POST['pAO'] - $_POST['pPaid'])  <= 0)
            {
                $Update = "UPDATE tblinvoice SET Status = 'P' WHERE invoiceCode = '".$_GET['Id']."'";
                $uResult = mysqli_query($Link, $Update);
            }
            $Prints = "<div class='Printing'><table>
        <caption>Invoice Details</caption>
        <tr>
            <td class=\"move\"><label for=\"iCode\">Invoice Code: </label></td>
            <td><input type=\"text\" name=\"iCode\" id=\"iCode\" maxlength=\"25\" size=\"27\" value='".$_POST['iCode']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"iDate\">Date Sold: </label></td>
            <td><input type=\"text\" name=\"iDate\" id=\"iDate\" value='".$_POST['iDate']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"iSold\">Sold By: </label></td>
            <td><input type=\"text\" name=\"iSold\" id=\"iSold\" value='".$_POST['iSold']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"iTotal\">Total Price(RM): </label></td>
            <td><input type=\"text\" name=\"iTotal\" id=\"iTotal\" value='".$_POST['iTotal']."'></td>
        </tr>
    </table>
    <br/>
    <table>
        <caption>Patient Details</caption>
        <tr>
            <td class=\"move\"><label for=\"pIC\">Patient IC: </label></td>
            <td><input type=\"text\" name=\"pIC\" id=\"pIC\" value='".$_POST['pIC']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"pName\">Patient Name: </label></td>
            <td><input type=\"text\" name=\"pName\" id=\"pName\" value='".$_POST['pName']."'> </td>
        </tr>
    </table>
     <br/>
    <table>
        <caption>Payment Details</caption>
        <tr>
            <td class=\"move\"><label for=\"pCode\">Payment Code: </label></td>
            <td><input type=\"text\" name=\"pCode\" id=\"pCode\" value='".$_POST['pCode']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"pDate\">*Paid On: </label></td>
            <td><input type=\"text\" name=\"pDate\" id=\"pDate\" value='".$_POST['pDate']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"pBy\">*Collected By: </label></td>
            <td><input type=\"text\" name=\"pBy\" id=\"pBy\" value='".$_POST['pBy']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"pAO\">Amount Owed(RM):</label></td>
            <td><input type=\"text\" name=\"pAO\" id=\"pAO\" value='".$_POST['pAO']."'> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"pType\">Payment Type: </label></td>
            <td><input type='text' value='".$_POST['pType']."''> </td>
        </tr>
        <tr>
            <td class=\"move\"><label for=\"pPaid\">*Amount Paid(RM):</label></td>
            <td><input type=\"number\" name=\"pPaid\" id=\"pPaid\" value='".$_POST['pPaid']."'> </td>
        </tr>
        </table></div>";
        echo $Prints;
        sendEmail("Receipt", "Attached is proof of payment for invoice ". $_POST['iCode'].", should any errors be found, please contact us immediately <br/>". $Prints, $_POST['pEm']);
            if($iResult)
           { ?>
                <script>
                    printIt();
                    checkers();
                </script>
            <?php }
        }
        else
        {
            echo "<script>alert('Please ensure that the collector\'s username has been entered correctly and is a staff member with an existing account');</script>";
        }
    }
}
