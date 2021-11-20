<?php
/**
 Shows and allows printing of reorder form
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script>
    function back()
    {
        var loc = "<?php echo $_SESSION['page']; ?>";
        location = 'viewReordersResult.php?page='+loc;
    }
</script>
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
            pageTitle: "Reorder Form",              // add title to print page
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
<head>
    <title>Reorder Form</title>
</head>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_GET['Id'] != "")
{
    $SQL = "SELECT tblsupplier.* FROM tblsupplier, tblreorder WHERE tblsupplier.SuppId = tblreorder.SuppId AND tblreorder.reCode = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
    }
}

$SQL = "SELECT * FROM tblreorder WHERE reCode = '".$_GET['Id']."'";
$Result = mysqli_query($Link, $SQL);
if(mysqli_num_rows($Result) > 0)
{
    $Row = mysqli_fetch_array($Result);
}
?>
<body>
<div class="container" style="width: 90%">
    <div id="Printing" class="Printing">
        <h1>Reorder Form</h1>
        <h3>*Mandatory</h3>
        <br />
        <form method="post" action="" name="fForm" id="fForm">
            <table border="0">
                <caption>Supplier Details</caption>
                <tr>
                    <td class="move"><label for="supId">Supplier ID: </label></td>
                    <td><input type="text" name="supId" id="supId" value = "<?php echo $Row['SuppId']; ?>" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="supName">Supplier Name: </label></td>
                    <td><input type="text" name="supName" id="supName" value="<?php echo $Row['SuppName']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="supAdd">Supplier Location: </label></td>
                    <td><textarea name="supAdd" id="supAdd" maxlength="250" cols="45" rows="5" style="background-color: lightgray" readonly><?php echo $Row['Address']; ?></textarea> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supNum">Supplier Contact Number: </label></td>
                    <td><input type="text" name="supNum" id="supNum" value="<?php echo $Row['SuppNo']; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');" style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="supEm">Supplier Email: </label></td>
                    <td><input type="email" name="supEm" id="supEm" value="<?php echo $Row['Email']; ?> " maxlength="40" size="42" style="background-color: lightgray" readonly> </td>
                </tr>
            </table>
            <br /><br />

            <table border="0" >
                <caption>Person-in-charge Details</caption>
                <tr>
                    <td class="move"><label for="supCon">Person-in-charge: </label></td>
                    <td><input type="text" name="supCon" id="supCon" value="<?php echo $Row['ContactPerson']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="conNum">Contact Number: </label> </td>
                    <td><input type="text" name="conNum" id="conNum" value="<?php echo $Row['ContactNo']; ?> " oninput="this.value=this.value.replace(/[^0-9]/g,'');"  style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="conEm">Email: </label></td>
                    <td><input type="email" name="conEm" id="conEm" value="<?php echo $Row['ContactEmail']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
            </table>
            <br/>
            <table>
                <caption>Reorder Code</caption>
                <tr>
                    <td class="move"><label for="reCode">*Reorder Form Code: </label></td>
                    <td><input type="text" name="reCode" id="reCode" maxlength="25" size="27" placeholder="RF01" required> </td>
                </tr>
            </table>
            <br/>
            <table cellpadding="6" style="width: 90% !important">
                <caption>Supplied Item(s)</caption>
                <tr>
                    <td ></td>
                    <td >Item Name</td>
                    <td >Estimated Arrival Time(Days)</td>
                    <td >Price(RM) Per Unit</td>
                    <td >Additional Remarks</td>
                    <td >*Quantity To Order</td>
                    <td >Total Price(RM)</td>
                </tr>
                <?php $SQLi = "SELECT * FROM tblreorder WHERE reCode = '".$_GET['Id']."'";
                $sResult = mysqli_query($Link, $SQLi);
                if(mysqli_num_rows($sResult) > 0);
                {
                    for($i = 0; $i < mysqli_num_rows($SQLIResult); ++$i)
                    {
                        $Row = mysqli_fetch_array($SQLIResult);
                        $cSupp = $i + 1;?>
                        <tr>
                            <td style="width: 5px"><?php echo ($i+1); ?></td>
                            <td style="width: 5px"><input type="text" name="<?php echo 'Code'.$i;?>" id="<?php echo 'Code'.$i;?>" size="27" maxlength="25" value="<?php echo $Row['ItemName']; ?>" readonly /></td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'ETA'.$i;?>" id="<?php echo 'ETA'.$i;?>" step="1" min="0" value="<?php echo $Row['ETA'] ?>" style="background-color: lightgray" readonly /></td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'Price'.$i;?>" id="<?php echo 'Price'.$i;?>" step="0.01" min="0" value="<?php echo $Row['Price'] ?>" readonly /></td>
                            <td style="width: 5px"><textarea name="<?php echo 'Remark'.$i;?>" id="<?php echo 'Remark'.$i;?>" cols="25" rows="5" maxlength="500" style="background-color: lightgray" readonly><?php echo $Row['Remark'] ?></textarea></td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'orQ'.$i;?>" id="<?php echo 'orQ'.$i;?>" step="1" min="0" value="<?php echo $Row['Quantity'] ?>" readonly> </td>
                            <td style="width: 5px"><input type="number" name="<?php echo 'tPrice'.$i;?>" id="<?php echo 'tPrice'.$i;?>" step="0.01" min="0" value="<?php echo $Row['totalPrice']; ?>" readonly> </td>
                        </tr>
                        <input type="hidden" name="<?php echo 'ID'.$i;?>" value="<?php echo $Row['sID'] ?>">
                    <?php    }
                }
                ?>
                <input type="hidden" name="cSupplied" value="<?php echo $cSupp ?>">
            </table>
            <br/><br/>
            <table>
                <tr>
                    <td><input type="button" name="btnSub" id="btnSub" class="button" value="Submit Form" onclick="printIt();"  style="text-align: center">
                        <input type='button' name='btnBack' id='btnBack' onclick='back()' value='Back' class='button'/></td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>

