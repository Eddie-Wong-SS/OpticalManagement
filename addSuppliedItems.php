<?php
/**
 Allows the adding of items that is supplied by a selected supplier
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    var count1 = 0;
    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Registered!', {
            body: 'Registration of the items for supplier <?php echo $_POST['supName']; ?> into the database was successful',
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
        window.open('viewInventory.php', '_blank' ,'width=710,height=555,left=160,top=170');
    }

    function addOption()
    {
        var g = document.getElementById('maxRow').value;
        g = parseInt(g);
        var select = document.getElementById('Name' + count1);
        for(var i = 0; i < g; ++i)
        {
            var iName = document.getElementById(i).value;
            select.options[select.options.length] = new Option(iName, iName);
        }
        select.options[select.options.length] = new Option("", "");
        select.selectedIndex = (select.options.length - 1);
    }

    function plusSupplied()
    {
        var table = document.getElementById("tblSupplied");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);

        count1++;
        cell1.style.width = "10px";
        cell2.style.width = "10px";
        cell3.style.width = "10px";
        cell4.style.width = "10px";
        cell5.style.width = "10px";
        cell1.innerHTML = count1 + ".";
        cell2.innerHTML = "<label for='Name" + count1 + "'></label><select name='Name" + count1 + "' id='Name" + count1 + "' onchange='addDetails(this.id);' required/>"+
                            "</select>";
        cell3.innerHTML = "<input type='text' name='Code" + count1 + "' id='Code" + count1 + "' size='5' style='background-color: lightgray' readonly />";
        cell4.innerHTML = "<textarea name='Desc" + count1 + "' id='Desc" + count1 + "' style='background-color: lightgray' cols='25' rows='5' readonly></textarea>";
        cell5.innerHTML = "<label for='ETA" + count1 + "'></label><input type='number' name='ETA" + count1 + "' id='ETA" + count1 + "' step='1' min='0' placeholder='7' required/>";
        cell6.innerHTML = "<label for='Price" + count1 + "'></label><input type='number' name='Price" + count1 + "' id='Price" + count1 + "' step='0.01' min='0' placeholder='1.50' required/>";
        cell7.innerHTML = "<label for='Remark" + count1 + "'></label><textarea name='Remark" + count1 +"' id='Remark"+ count1 +"' cols='25' rows='5' maxlength='500'></textarea>";
        document.getElementById("counterSupplied").value = count1;
        addOption();
    }

    function minusSupplied()
    {
        var table = document.getElementById("tblSupplied");
        var row = table.deleteRow(-1);

        if ( count1 !== 0 )
            count1--;

        document.getElementById("counterSupplied").value = count1;
    }

    function addDetails(obtain)
    {
        var array = obtain.split('e');
        var i = array[1];
        var index = document.getElementById('Name'+i).selectedIndex;
        var max = parseInt(index);
        if((document.getElementById('Name'+i).length - 1) === max)
        {
            document.getElementById('Code' + i).value = "";
            document.getElementById('Desc' + i).value = "";
            document.getElementById('ETA' + i).value = "";
            document.getElementById('Price' + i).value = "";
            document.getElementById('Remark' + i).value = "";

        }
        else
        {
            var details = document.getElementById('Det' + index).value;
            details = details.split('*');
            document.getElementById('Code' + i).value = details[1];
            document.getElementById('Desc' + i).value = details[2];
        }
    }

    function checkName()
    {
        var flag = 0;
        var i = document.getElementById('counterSupplied').value;
        for(f = 1; f <= i; ++f)
        {
            if(i === 1) break;
            var checkp = document.getElementById('Name'+(f)).value;
            for(g = 2; g <= i; ++g)
            {
                if(f === g) continue;
                var check = document.getElementById('Name' + g).value;
                if(check === checkp)
                {
                    document.getElementById('Name'+f).style = "border-color: red";
                    document.getElementById('Name'+g).style = "border-color: red";
                    document.getElementById('sError').innerHTML = "You have entered one or more identical supplier names, please check again";
                    document.getElementById('sError').style = "color: red";
                    flag = 1;
                    return false;
                }
            }
        }
        if(flag === 0)
        {
            document.forms['fForm'].submit();
        }
    }

    function setHid()
    {
        var add = document.createElement('input');
        add.type = "hidden";
        add.name = "yay";
        add.id = "yay";
        add.value="2";

        var z = document.getElementById('fForm');
        z.appendChild(add);
    }
</script>
<title>Add Supplier's Items</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_REQUEST['yay'])
{
    for($i = 1; $i <= $_POST['counterSupplied']; ++$i)
    {
        if($_POST['Name'.$i] == "" || $_POST['ETA'.$i] == "" || $_POST['Price'.$i] == "")
        {
            echo "<script>alert('Please ensure all Item Names, ETA and Price fields are properly filled');</script>";
        }
        else
        {
            $Check = "SELECT * FROM tblinventory WHERE ItemName = '".$_POST['Name'.$i]."'";
            $CheckResult = mysqli_query($Link, $Check);
            if(mysqli_num_rows($CheckResult) < 1 ) echo "<script>alert('You have not entered the name of any item in the inventory');</script>";
            else
            {
                $Inspect = "SELECT * FROM tblsupplies WHERE ItemName = '".$_POST['Code'.$i]."' AND SuppId = '".$_GET['Id']."'";
                $InspectResult = mysqli_query($Link, $Inspect);
                if(mysqli_num_rows($InspectResult) > 0) echo "<script>alert('Item of row '+$i+' already has been entered for this supplier');</script>";
                else
                {
                    $SQLI = "INSERT INTO tblsupplies(ItemName, SuppId, ETA, Price, Remark) VALUES(
                            '" . strtoupper(trim($_POST['Name'.$i])) . "',
                            '" . strtoupper(trim($_GET['Id'])) . "',
                            '" . strtoupper(trim($_POST['ETA'.$i])) . "',
                            '" . strtoupper(trim($_POST['Price'.$i])) . "',
                            '" . strtoupper(trim($_POST['Remark'.$i])) . "')";
                    $SQLResult = mysqli_query($Link, $SQLI);
                    if($SQLResult)
                    {?>
                        <script>
                            checkers();
                        </script>
                    <?php }
                }
            }
        }
    }
}
else if($_GET['Id'] != "")
{
    $SQL = "SELECT SuppName, SuppId FROM tblsupplier WHERE SuppId  = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if($Result)
    {
        $Row = mysqli_fetch_array($Result);
    }
}
?>

<body onload="setHid()">
<div class="container" style="width: 90%">
    <h1>Add Supplier's Items</h1>
    <h3>* Mandatory</h3>

    <form method="post" action="" name="fForm" id="fForm">
        <label for="sName">Supplier Name:</label> <input type="text" name="sName" id="sName" size="27" value="<?php echo $Row['SuppName']; ?>" style="height: initial" readonly>
        <br/><br/>
        <?php  $GET = "SELECT ItemName, Code, ItemDesc FROM tblinventory WHERE tblinventory.Status = 'A' ORDER BY 'ItemName'";
        $gResult = mysqli_query($Link, $GET);
        for($g = 0; $g < mysqli_num_rows($gResult); ++$g)
        {
            $Item = mysqli_fetch_array($gResult);
            $Name = $Item['ItemName'];
            $Detail = $Item['ItemName'] . "*". $Item['Code']."*".$Item['ItemDesc'];
            echo "<input type='hidden' value='$Name' id='$g'>";
            echo "<input type='hidden' value='$Detail' id='Det$g'>";

        }
        echo "<input type='hidden' value='$g' id='maxRow' />";
        ?>
        <label id="sError"></label>
        <table cellpadding="6" id="tblSupplied" style="width: 90%;">
            <caption>Supplied Item(s)</caption>
            <tr>
                <td style="width: 10px"></td>
                <td style="width: 10px">*Item Name</td>
                <td style="width: 10px">*Item Code</td>
                <td style="width: 10px">*Item Description</td>
                <td style="width: 10px">*Estimated Arrival Time(Days)</td>
                <td style="width: 10px">*Price(RM) Per Unit</td>
                <td style="width: 10px">Remarks</td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="6" style="text-align:center">
                    <input type="button" name="plus" id="plus" value="+" onclick="plusSupplied()"  class="button site"/>
                    <input type="button" name="minus" id="minus" value="-" onclick="minusSupplied()" class="button site"/>
                </td>
            </tr>
        </table>
        <br/><br/>

        <input type="hidden" name="counterSupplied" id="counterSupplied" value="0"/>
        <table width="85%">
            <tr>
                <td style="width: 10px"><input type="button" name="btnSub" class="button site" style="width: 100px" id="btnSub" onclick="checkName();" value="Register"/></td>
                <td style="width: 10px"><input type="button" name="btnView" class="button site" style="width: 100px" id="btnView" onclick="View();" value="View Items"/> </td>
            </tr>
        </table>
    </form>
</div>
</body>
