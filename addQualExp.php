<?php
/**
 Adds more qualifications and work experiences for a staff record
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script>
    var count = 0;
    var count2 = 0;
    var count3 = 1;

    function plusEducation()
    {
        var table = document.getElementById("tblEducation");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        count++;

        cell1.innerHTML = count + ".";
        cell2.innerHTML = "<label for='institution" + count + "'></label><input type='text' name='institution" + count + "\' id='institution" + count + "\' size='52' maxlength='50' placeholder='Institution' required/>";
        cell3.innerHTML = "<label for=\"level" + count + "\"></label><select name=\"level" + count + "\" id=\"level" + count + "\">" +
            "<option></option>" +
            "<option value=\"SPM\"  selected=\"selected\">SPM</option>" +
            "<option value=\"Diploma\">Diploma</option>" +
            "<option value=\"Degree\">Degree</option>" +
            "<option value=\"Master\">Master</option>" +
            "<option value=\"PhD\">PhD</option>" +
            "</select>";
        cell4.innerHTML = "<label for='specialization" + count + "' ></label><input type='text' name='specialization" + count + "' id='specialization" + count + "' size='52' maxlength='50' placeholder='Specialization' title=\"Leave blank if none\"/>";
        cell5.innerHTML = "<label for='graduateYr" + count + "'></label><input type=\"text\" maxlength=\"4\" oninput=\"this.value=this.value.replace(/[^0-9]/g,'');\"\n" +
            " name='graduateYr" + count + "' size='15' id='graduateYr" + count + "' placeholder='Graduate Year' required/>";

        document.getElementById("counterEdu").value = count;
    }

    function minusEducation()
    {
        var table = document.getElementById("tblEducation");
        var counter = document.getElementById("counterEdu");
        var row = table.deleteRow(-1);

        if ( count !== 0 )
            count--;

        document.getElementById("counterEdu").value = count;
    }

    function plusWork()
    {
        var table = document.getElementById("tblWork");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);

        count2++;

        cell1.innerHTML = count2 + ".";
        cell2.innerHTML = "<label for='company" + count2 + "'></label><input type='text' name='company" + count2 + "' id='company" + count2 + "' size='52' maxlength='50' placeholder='Company' required/>";
        cell3.innerHTML = "<label for='position" + count2 + "'></label><input type='text' name='position" + count2 + "' id='position" + count2 + "' size='27' maxlength='25' placeholder='Position' required/>";
        cell4.innerHTML = "<label for='workFrom" + count2 + "'></label><input type=\"text\" maxlength=\"4\" oninput=\"this.value=this.value.replace(/[^0-9]/g,'');\"\n" +
            " name='workFrom" + count2 + "' size='8' id='workFrom" + count2 + "' placeholder='From Year' required/>";
        cell5.innerHTML = "-";
        cell6.innerHTML = "<label for='workTo" + count2 + "'></label><input type=\"text\" maxlength=\"4\" oninput=\"this.value=this.value.replace(/[^0-9]/g,'');\"\n" +
            " name='workTo" + count2 + "' size='6' id='workTo" + count2 + "' placeholder='To Year' required/>";

        document.getElementById("counterWork").value = count2;
    }

    function minusWork()
    {
        var table = document.getElementById("tblWork");
        var row = table.deleteRow(-1);

        if ( count2 !== 0 )
            count2--;

        document.getElementById("counterWork").value = count2;
    }

    function checkName()
    {
        var flag = 0;
        var i = document.getElementById('counterEdu').value;
        if(i == 0) return 1;
        else
        {
            for(f = 1; f <= i; ++f)
            {
                if(i === 1) break;
                var checkp = document.getElementById('institution'+(f)).value;
                for(g = 2; g <= i; ++g)
                {
                    if(f === g) continue;
                    var check = document.getElementById('institution' + g).value;
                    if(check === checkp)
                    {
                        document.getElementById('institution'+f).style = "border-color: red";
                        document.getElementById('institution'+g).style = "border-color: red";
                        flag = 1;
                        return 0;
                    }
                }
            }
            if(flag === 0)
            {
                return 1;
            }
        }
    }

    function checkWork()
    {
        var flag = 0;
        var i = document.getElementById('counterWork').value;
        if(i == 0) return 1;
        else
        {
            for(f = 1; f <= i; ++f)
            {
                if(i === 1) break;
                var checkp = document.getElementById('company'+(f)).value;
                for(g = 2; g <= i; ++g)
                {
                    if(f === g) continue;
                    var check = document.getElementById('company' + g).value;
                    if(check === checkp)
                    {
                        document.getElementById('company'+f).style = "border-color: red";
                        document.getElementById('company'+g).style = "border-color: red";
                        flag = 1;
                        return 0;
                    }
                }
            }
            if(flag === 0)
            {
                return 1;
            }
        }
    }

    function check()
    {
        var flag1 = checkName();
        var flag2 = checkWork();

        if(flag1 === 1 && flag2 === 1)
        {
            document.forms['fForm'].submit();
        }
        else alert("You have duplicate records, please check again");
    }

    function back()
    {
        var b = "<?php echo $_GET['id']; ?>";
        location = "editStaff.php?Id="+b;
    }
</script>
<?php
if($_POST['Wee'] != "")
{
    for ($i = 1; $i <= $_POST['counterEdu']; ++$i)
    {
        $checkStaffQualSQL = "SELECT * FROM tblstaffqual WHERE School='" . strtoupper(trim($_POST['institution' . $i])) . "'";
        $checkStaffQualSQLRecord = mysqli_query($Link, $checkStaffQualSQL);
        if (mysqli_num_rows($checkStaffQualSQLRecord))
        {
            $qualflag = true;
            $qualNum = $i;
            break;
        }
    }

    for ($f = 1; $f <= $_POST['counterWork']; ++$f)
    {
        $checkWorkSQL = "SELECT * FROM tblworkexp WHERE Company='" . strtoupper(trim($_POST['company' . $f])) . "'";
        $checkWorkSQLRecord = mysqli_query($Link, $checkWorkSQL);
        if (mysqli_num_rows($checkWorkSQLRecord))
        {
            $workflag = true;
            $workNum = $f;
            break;
        }
    }

    if ($qualflag == true)
    {
        echo '<script>alert("This specific school record has already been recorded into the database. Record: "' . $qualNum . ');</script>';
    } else if ($workflag == true)
    {
        echo '<script>alert("This specific work record has already been recorded into the database. Record: "' . $workNum . ');</script>';
    }
    else
    {
        for ($i = 1; $i <= $_POST['counterEdu']; ++$i) {
            $addQualSQL = "INSERT INTO tblstaffqual(StaffIC, Qualification, Specialization, School, gradYear) VALUES(
                    '" . strtoupper(trim($_GET['id'])) . "',
                    '" . strtoupper(trim($_POST['level' . $i])) . "',
                    '" . strtoupper(trim($_POST['specialization' . $i])) . "',
                    '" . strtoupper(trim($_POST['institution' . $i])) . "',
                    '" . strtoupper(trim($_POST['graduateYr' . $i])) . "')";
            $addQualSQLResult = mysqli_query($Link, $addQualSQL);
        }

        for ($f = 1; $f <= $_POST['counterWork']; ++$f) {
            $addWorkSQL = "INSERT INTO tblworkexp(StaffIC, Position, Company, fromYear, toYear) VALUES (
                    '" . strtoupper(trim($_GET['id'])) . "',
                    '" . strtoupper(trim($_POST['position' . $f])) . "',
                    '" . strtoupper(trim($_POST['company' . $f])) . "',
                    '" . strtoupper(trim($_POST['workFrom' . $f])) . "',
                    '" . strtoupper(trim($_POST['workTo' . $f])) . "')";
            $addWorkSQLResult = mysqli_query($Link, $addWorkSQL);
        }

        if($addQualSQLResult || $addWorkSQLResult)
        {
            $Id = $_GET['id'];
            echo "<script>location='editStaff.php?Id=$Id';</script>";
        }
    }
}
?>
<title>Add Qualifications and Experiences</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<body>
<div class="container">
    <h1>Add Staff Qualificaiton and Experience</h1>
    <h3>*Mandatory</h3>
    <form id="fForm" name="fForm" method="post">
    <table align="center" cellpadding="6" border="0" id="tblEducation">
        <caption>Qualifications</caption>
        <tr>
            <td></td>
            <td><label for="institution1">*Institution</label></td>
            <td><label for="level1">Level</label></td>
            <td><label for="specialization1">Specialization</label></td>
            <td><label for="graduateYr1">*Graduate Year</label></td>
        </tr>
    </table>
    <table align="center" cellpadding="6" border="0">
        <tr>
            <td style="text-align:center">
                <input type="button" name="plus" id="plus" value="+" onclick="plusEducation()" class="button site"/>
                <input type="button" name="minus" id="minus" value="-" onclick="minusEducation()" class="button site"/>
            </td>
        </tr>
    </table>
    <br/>
    <table align="center" cellpadding="6" border="0" id="tblWork">
        <caption>Work Experience(if any)</caption>
        <tr>
            <td></td>
            <td>Company</td>
            <td>Position</td>
            <td>From Year</td>
            <td>-</td>
            <td>To Year</td>
        </tr>
    </table>
    <table align="center" cellpadding="6" border="0">
        <tr>
            <td colspan="6" style="text-align:center">
                <input type="button" name="plus" id="plus" value="+" onclick="plusWork()"  class="button site"/>
                <input type="button" name="minus" id="minus" value="-" onclick="minusWork()" class="button site"/>
            </td>
        </tr>
    </table>

    <br /><br />
    <input type="hidden" name="counterEdu" id="counterEdu" value="0"/>
    <input type="hidden" name="counterWork" id="counterWork" value="0"/>
    <input type="hidden" name="Wee" id="Wee" value="Wee"/>
    <table>
        <tr>
            <td><input type="button" name="btnSub" class="button site" style="width: 100px" id="btnSub" onclick="check()" value="Add"/></td>
            <td><input type="button" name="btnBack" class="button site" style="width: 100px" id="btnBack" onclick="back()" value="Back"/> </td>
        </tr>
    </table>
    </form>
</div>
</body>