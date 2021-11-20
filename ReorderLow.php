<?php
/**
 Allows for the reordering of an item from multiple suppliers
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
include ('Email.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    var count = 0;
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Sent!', {
            body: 'Reorder form for item <?php echo $_POST['iCode']; ?> has been successfully emailed',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

    function View()
    {
        window.open('viewSupplied.php', '_blank', 'location=yes,height=570,width=1000,scrollbars=yes,status=yes');
    }

    function totalPrice(obtain)
    {
        var array = obtain.split("n");
        var i = array[1];
        var sum = document.getElementById("Price" + i).value;
        var quan = document.getElementById("Quan"+ i).value;
        document.getElementById("tPrice" + i).value = sum * quan;
    }

    function addOption()
    {
        var g = document.getElementById('maxRow').value;
        g = parseInt(g);
        var select = document.getElementById('Name' + count);
        for(var i = 0; i < g; ++i)
        {
            var iName = document.getElementById(i).value;
            select.options[select.options.length] = new Option(iName, iName);
        }
        select.options[select.options.length] = new Option("", "");
        select.selectedIndex = (select.options.length - 1);
    }

    function plusReorder()
    {
        var table = document.getElementById("tblReorder");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);

        ++count;
        
        cell1.innerHTML = count + "." ;
        cell2.innerHTML = "<input type='number' name='ID" + count + "' id='ID" + count + "' style='width: 70%; ' readonly/>";
        cell3.innerHTML = "<select name='Name" + count + "' id='Name" + count + "' onchange='addDetails(this.id)' required>" + "</select>";
        cell4.innerHTML = "<input type='number' name='Price" + count + "' size='5' id='Price" + count + "' step='0.01' min='0' style='background-color: lightgray' readonly/>";
        cell5.innerHTML = "<input type='number' name='ETA" + count + "' size='5' id='ETA" + count + "' min = '0' step='1' style='background-color: lightgray' readonly/>";
        cell6.innerHTML = "<input type='number' name='Quan" + count + "' id='Quan" + count + "' min = '0' step='1' onblur='totalPrice(this.id)' required/>";
        cell7.innerHTML = "<input type='number' name='tPrice" + count + "' id='tPrice" + count + "' value = '0' style='background-color: lightgray' readonly/>";
        addOption();

        document.getElementById("counterReorder").value = count;
    }

    function minusReorder()
    {
        var table = document.getElementById("tblReorder");
        var row = table.deleteRow(-1);

        if ( count !== 0 )
            count--;

        document.getElementById("counterReorder").value = count;
    }

    function addDetails(obtain)
    {
        var array = obtain.split('e');
        var i = array[1];
        var index = document.getElementById('Name'+i).selectedIndex;
        var max = parseInt(index);
       if((document.getElementById('Name'+i).length - 1) === max)
       {
           document.getElementById('ID' + i).value = "";
           document.getElementById('Price' + i).value = "";
           document.getElementById('ETA' + i).value = "";
           document.getElementById('Quan' + i).value = "";
           document.getElementById('tPrice' + i).value = 0;
       }
       else
       {
           var details = document.getElementById('Det' + index).value;
           details = details.split('*');
           document.getElementById('ID' + i).value = details[0];
           document.getElementById('Price' + i).value = details[1];
           document.getElementById('ETA' + i).value = details[2];
       }
    }

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="printThis-master/printThis.js"></script>
<script>
    function printIT()
    {
        $('#Print').printThis({
            debug: true,               // show the iframe for debugging
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
            doctypeString: "...",       // enter a different doctype for older markup
            removeScripts: false,       // remove script tags from print content
            copyTagClasses: false,   // copy classes from the html &amp; body tag
        });
    }
</script>
<title>Order From Suppliers</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<?php
if($_REQUEST['btnSub'])
{
    $flag = 0;
    $Check = "SELECT reCode FROM tblreorder WHERE reCode = '".strtoupper(trim($_POST['reCode']))."'";
    $CResult = mysqli_query($Link, $Check);
    if(mysqli_num_rows($CResult) > 0)
    {
        echo "<script>alert('The reorder code you are using is already used in another form');</script>";
    }
    else
    {
        if($_POST['reCode'] == "") echo "<script>alert('Please fill in the reorder code first');</script>";
        for($i = 0, $count = 1; $i < $_POST['counterReorder']; ++$i, ++$count)
        {
            $inspectSQL = "SELECT * FROM tblsupplies, tblsupplier WHERE tblsupplies.SuppId = tblsupplier.SuppId AND tblsupplier.SuppId = '".strtoupper(trim($_POST['ID'.$count]))."'
            AND tblsupplier.SuppName = '".strtoupper(trim($_POST['Name'.$count]))."' AND tblsupplies.Price = '".strtoupper(trim($_POST['Price'.$count]))."'
             AND tblsupplies.ETA = '".strtoupper(trim($_POST['ETA'.$count]))."'";
            $inspectResult = mysqli_query($Link, $inspectSQL);
            if(mysqli_num_rows($inspectResult) < 1)
            {
                echo "<script>alert('You have entered some of the details for the supplier wrong');</script>";
                $flag = 1;
                break;
            }

        }

        if($flag != 1)
        {
            for($i = 1; $i <= $_POST['counterReorder']; ++$i)
            {
                if($_POST['tPrice'.$i] == 0) continue;
                $getEM = "SELECT Email FROM tblsupplier WHERE SuppId = '".strtoupper(trim($_POST['ID'.$i]))."'";
                $EMResult = mysqli_query($Link, $getEM);
                $EM = mysqli_fetch_array($EMResult);

                $Reorder = "<body>
                <div class=\"container\" style=\"width: 90%\">
                <h1>Reorder Form</h1>
                <br />
                <form method=\"post\" action=\"\">
                <div align=\"center\">
                    <table cellpadding=\"10\" style=\"width: 90%\" id=\"tblReorder\">
                        <caption>Supplier(s)</caption>
                        <tr>                          
                            <td >Supplier ID</td>
                            <td >*Supplier Name</td>
                            <td >Price</td>
                            <td >Estimated Arrival Time(Days)</td>
                            <td >*Quantity To Order</td>
                            <td >Total Price(RM)</td>
                        </tr>
                    ";

                $AddReorderSQL = "INSERT INTO tblreorder(reCode, SuppId, SuppName, ItemName, ETA, Price, totalPrice, Quantity) VALUES (
                            '".strtoupper(trim($_POST['reCode']))."',
                            '".strtoupper(trim($_POST['ID'.$i]))."',
                            '".strtoupper(trim($_POST['Name'.$i]))."',
                            '".strtoupper(trim($_POST['iName']))."',
                            '".strtoupper(trim($_POST['ETA'.$i]))."',
                            '".strtoupper(trim($_POST['Price'.$i]))."',
                            '".strtoupper(trim($_POST['tPrice'.$i]))."',
                            '".strtoupper(trim($_POST['Quan'.$i]))."')";

                $AResult = mysqli_query($Link, $AddReorderSQL);

                $Reorder .= "<tr>                         
                            <td><input type='text' value='".strtoupper(trim($_POST['ID'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['Name'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['Price'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['ETA'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['Quan'.$i]))."' </td>
                            <td><input type='text' value='".strtoupper(trim($_POST['tPrice'.$i]))."' </td>
                         </tr>
                         ";
                $Reorder .= "</table>
                     <br />";
                $Reorder .= "
                    <table>
                        <caption>Reorder Code</caption>
                        <tr>
                            <td class=\"move\"><label for=\"reCode\">*Reorder Form Code: </label></td>
                            <td><input type=\"text\" name=\"reCode\" id=\"reCode\" maxlength=\"25\" size=\"27\" value='".strtoupper(trim($_POST['reCode']))."' required> </td>
                        </tr>
                    </table>
                    <br/>
                    <table border=\"0\">
                        <caption>Item Details</caption>
                        <tr>
                    <td class=\"move\"><label for=\"iCode\">Item Code: </label></td>
                    <td><input type=\"text\" value='".strtoupper(trim($_POST['iCode']))."' style=\"background-color: lightgray\" readonly> </td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"iName\">Item Name: </label></td>
                    <td><input type=\"text\" value='".strtoupper(trim($_POST['iName']))."' maxlength=\"50\" size=\"52\" style=\"background-color: lightgray\" readonly></td>
                </tr>
                <tr>
                    <td class=\"move\"><label for=\"iDescp\">Item Description: </label></td>
                    <td><textarea cols=\"35\" rows=\"5\" style=\"background-color: lightgray\" readonly>'".strtoupper(trim($_POST['iDescp']))."'</textarea> </td>
                </tr>
                    </table>
                    </div>
                    </form>
                    </div>";
                echo $Reorder;
                sendEmail("Reorder Form", $Reorder, $_POST['conEm']);
                ?>
                <script>
                    checkers();</script>
                <?php
            }


            {
            }
        }
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT * FROM tblinventory WHERE Code = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
    }

}
?>

<body>
<div class="container" style="width: 90%">
    <h1>Reorder Form</h1>
    <h3>*Mandatory</h3>
    <br />
    <div id="Print">
    <form method="post" action="">
        <div align="center">
            <table border="0">
                <caption>Item Details</caption>
                <tr>
                    <td class="move"><label for="iId">Item ID: </label></td>
                    <td><input type="text" name="iId" id="iId" value = "<?php echo $Row['ItemId']; ?>" style="background-color: lightgray" readonly></td>
                    <td><img src="<?php echo $Row['imageLoc']; ?>" style="height: 100px; width: 100px"></td>
                </tr>
                <tr>
                    <td class="move"><label for="iCode">Item Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode" value="<?php echo $Row['Code']; ?> " style="background-color: lightgray" readonly> </td>
                </tr>
                <tr>
                    <td class="move"><label for="iName">Item Name: </label></td>
                    <td><input type="text" name="iName" id="iName" value="<?php echo $Row['ItemName']; ?> " maxlength="50" size="52" style="background-color: lightgray" readonly></td>
                </tr>
                <tr>
                    <td class="move"><label for="iDescp">Item Description: </label></td>
                    <td><textarea name="iDescp" id="iDescp" cols="35" rows="5" style="background-color: lightgray" readonly><?php echo $Row['ItemDesc']; ?></textarea> </td>
                </tr>
                <tr>
                    <td class="move"><label for="iType">Item Type: </label></td>
                    <td><input type="email" name="iType" id="iType" value="<?php echo $Row['ItemType']; ?> " maxlength="40" size="42" style="background-color: lightgray" readonly> </td>
                </tr>
            </table>
            <br /><br />
            <table>
                <caption>Reorder Code</caption>
                <tr>
                    <td class="move"><label for="reCode">*Reorder Form Code: </label></td>
                    <td><input type="text" name="reCode" id="reCode" maxlength="25" size="27" placeholder="RF01" required> </td>
                </tr>
            </table>
            <?php  $GET = "SELECT tblsupplies.*, tblsupplier.SuppName FROM tblsupplies, tblsupplier WHERE tblsupplier.SuppId = tblsupplies.SuppId
                  AND tblsupplies.Status = 'A' AND tblsupplier.Status = 'A' AND tblsupplies.ItemName = '".$Row['ItemName']."' ORDER BY 'SuppName'";
            $gResult = mysqli_query($Link, $GET);
            for($g = 0; $g < mysqli_num_rows($gResult); ++$g)
            {
                $Item = mysqli_fetch_array($gResult);
                $Name = $Item['SuppName'];
            $Detail = $Item['SuppId'] . "*". $Item['Price']."*".$Item['ETA'];
                echo "<input type='hidden' value='$Name' id='$g'>";
                echo "<input type='hidden' value='$Detail' id='Det$g'>";

            }
            echo "<input type='hidden' value='$g' id='maxRow' />";
            ?>
            <br/>
            <table cellpadding="10" style="width: 90%" id="tblReorder">
                <caption>Supplier(s)</caption>
                <tr>
                    <td ></td>
                    <td >Supplier ID</td>
                    <td >*Supplier Name</td>
                    <td >Price</td>
                    <td >Estimated Arrival Time(Days)</td>
                    <td >*Quantity To Order</td>
                    <td >Total Price(RM)</td>
                </tr>
            </table>
            <table style="width:90%">
                <tr>
                    <td colspan="6" style="text-align:center">
                        <input type="button" name="plus" id="plus" value="+" onclick="plusReorder()"  class="button site"/>
                        <input type="button" name="minus" id="minus" value="-" onclick="minusReorder()" class="button site"/>
                    </td>
                </tr>
            </table>
            <br/><br/>
            <input type="hidden" name="counterReorder" id="counterReorder" value="0"/>
            <input type="button" name="btnLook" class="button" onclick="View()" value="Look Up Suppliers' Items">
            <br/><br/>
            <input type="submit" name="btnSub" class="button" value="Submit Form" style="text-align: center">
        </div>
    </form>
    </div>
</div>
</body>