<?php
/**Adds in a new staff to the database
 */
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    var count = 1;
    var count2 = 1;
    var count3 = 1;

    Push.Permission.request();
    function checkers() {
        Push.create('Successfully Registered!', {
            body: 'Registration of the staff member <?php echo $_POST['supName']; ?> into the database was successful',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                console.log(this);
            }
        });
    }

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

        if ( count !== 1 )
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

        if ( count2 !== 1 )
            count2--;

        document.getElementById("counterWork").value = count2;
    }

    function plusComment()
    {
        var table = document.getElementById("tblComment");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        count3++;

        cell1.innerHTML = count3 + ".";
        cell2.innerHTML = "<label for='comment" + count3 + "'></label><input type='text' name='comment" + count3 + "' id='comment" + count3 + "' size='102' maxlength='100' placeholder='Remarks' />";

        document.getElementById("counterComment").value = count3;
    }

    function minusComment()
    {
        var table = document.getElementById("tblComment");
        var row = table.deleteRow(-1);

        if( count3 !== 1)
            count3--;

        document.getElementByID("counterComment").value = count3;
    }


    function readURL()
    {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("image").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    }

    function checkName()
    {
        var flag = 0;
        var i = document.getElementById('counterEdu').value;
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
                    document.getElementById('sError').innerHTML = "You have entered one or more identical school names, please check again";
                    document.getElementById('sError').style = "color: red";
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

    function checkWork()
    {
        var flag = 0;
        var i = document.getElementById('counterWork').value;
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
                    document.getElementById('qError').innerHTML = "You have entered one or more identical company names, please check again";
                    document.getElementById('qError').style = "color: red";
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

    function check()
    {
        var flag1 = checkName();
        var flag2 = checkWork();

        if(flag1 === 1 && flag2 === 1)
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
<title>Add Staff</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_POST['yay'])
{
    $qualflag = false;
    $qualNum = 0;
    $workflag = false;
    $workNum = 0;
    $target_path = "Images/";
    $target_path = $target_path . "Staff".$_POST['stfIC'].".png";

    $checkStaffSQL = "SELECT * FROM tblstaff WHERE StaffIC='" . strtoupper(trim($_POST['stfIC'])) . "'";
    $checkStaffSQLRecord = mysqli_query($Link, $checkStaffSQL);

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

    if (mysqli_num_rows($checkStaffSQLRecord))
    {
        echo "<script>alert('The inputted IC already exists in the database');</script>";
    } else if ($qualflag == true)
    {
        echo '<script>alert("This specific school record has already been recorded into the database. Record: "' . $qualNum . ');</script>';
    } else if ($workflag == true)
    {
        echo '<script>alert("This specific work record has already been recorded into the database. Record: "' . $workNum . ');</script>';
    } else
    {
        $qualNum = 0;
        $qualflag = false;
        $workNum = 0;
        $workflag = false;
        $accType = "STAFF";

        move_uploaded_file($_FILES['image']['tmp_name'], $target_path);

        $addStaffSQL = "INSERT INTO tblstaff(StaffIC, StaffName, Gender, Address, DOB, Phone, Email, Position, imageLoc, Remark, Status) VALUES (
                  '" . strtoupper(trim($_POST['stfIC'])) . "',
                  '" . strtoupper(trim($_POST['stfName'])) . "',
                  '" . strtoupper(trim($_POST['stfGen'])) . "',
                  '" . strtoupper(trim($_POST['stfAdd'])) . "',
                  '" . strtoupper(trim($_POST['stfDOB'])) . "',
                  '" . strtoupper(trim($_POST['stfCNO'])) . "',
                  '" . strtoupper(trim($_POST['stfEm'])) . "',
                  '" . strtoupper(trim($_POST['stfPos'])) . "',
                  '" . strtoupper(trim($_POST['stfRem'])) . "',
                  '$target_path',
                  'A')";
        $addStaffSQLResult = mysqli_query($Link, $addStaffSQL);

        for ($i = 1; $i <= $_POST['counterEdu']; ++$i) {
            $addQualSQL = "INSERT INTO tblstaffqual(StaffIC, Qualification, Specialization, School, gradYear) VALUES(
                    '" . strtoupper(trim($_POST['stfIC'])) . "',
                    '" . strtoupper(trim($_POST['level' . $i])) . "',
                    '" . strtoupper(trim($_POST['specialization' . $i])) . "',
                    '" . strtoupper(trim($_POST['institution' . $i])) . "',
                    '" . strtoupper(trim($_POST['graduateYr' . $i])) . "')";
            $addQualSQLResult = mysqli_query($Link, $addQualSQL);
        }

        for ($f = 1; $f <= $_POST['counterWork']; ++$f) {
            $addWorkSQL = "INSERT INTO tblworkexp(StaffIC, Position, Company, fromYear, toYear) VALUES (
                    '" . strtoupper(trim($_POST['stfIC'])) . "',
                    '" . strtoupper(trim($_POST['position' . $f])) . "',
                    '" . strtoupper(trim($_POST['company' . $f])) . "',
                    '" . strtoupper(trim($_POST['workFrom' . $f])) . "',
                    '" . strtoupper(trim($_POST['workTo' . $f])) . "')";
            $addWorkSQLResult = mysqli_query($Link, $addWorkSQL);
        }

        ?>
        <script>checkers();</script>

        <?php
    }

}
?>

<body>
<div class="container">
    <h1>Staff Registration</h1>
    <h3>*Mandatory</h3>

    <form method="post" action="" enctype="multipart/form-data" name="fForm" id="fForm">
	<div align="center">
        <table border="0">
            <caption>Staff Details</caption>
            <tr>
                <td class="move"><label for="stfName">*Staff Name: </label></td>
                <td><input type="text" name="stfName" id="stfName" maxlength="50" size="52" pattern="[A-Za-z]{3,50}" title="Only characters and a minimum length of 3" required></td>
                <td><label for="image">*Upload a picture: </label><input type="file" name="image" id="image" accept="image/*" onchange="readURL();" class="button" required></td>
            </tr>
            <tr>
                <td class="move"><label for="stfIC">*Staff IC: </label></td>
                <td><input type="text" name="stfIC" id="stfIC" maxlength="14" pattern="\d*" title="Numbers only" minlength="14" size="16" required></td>
                <td rowspan="7" align="center"><img src="Images/no%20image%20selected.gif" id="uploadPreview" style="width: 100px; height: 100px;" /></td>
            </tr>
            <tr>
                <td class="move"><label for="stfGen">Gender: </label></td>
                <td><select name="stfGen" id="stfGen">
                        <option selected="selected" value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="move"><label for="stfDOB">*Date of Birth: </label></td>
                <td><input type="date" name="stfDOB" id="stfDOB" max="<?php echo $Mtime; ?>" required></td>
            </tr>
            <tr>
                <td class="move"><label for="stfPos">Position: </label></td>
                <td><select name="stfPos" id="stfPos">
                        <option selected="selected" value="Clerk">Clerk</option>
                        <option value="Optometrist">Optometrist</option>
                    </select>
                </td>
            </tr>
        </table>
        <br/>
        <table cellpadding="6" border="0">
            <caption>Contact Details</caption>
            <tr>
                <td class="move"><label for="stfCNO">*Contact Number: </label></td>
                <td><input type="number" name="stfCNO" id="stfCNO" min="0" step="1" required> </td>
            </tr>
            <tr>
                <td class="move"><label for="stfAdd">*Address: </label></td>
                <td><textarea name="stfAdd" id="stfAdd" maxlength="250" cols="45" rows="5" required></textarea></td>
            </tr>
            <tr>
                <td class="move"><label for="stfEm">*Email: </label></td>
                <td><input type="email" name="stfEm" id="stfEm" maxlength="40" size="42" required> </td>
            </tr>
        </table>
        <br />
        <label id="sError"></label>
        <table align="center" cellpadding="6" border="0">
            <caption>Qualifications</caption>
            <tr>
                <td></td>
                <td><label for="institution1">Institution</label></td>
                <td><label for="level1">Level</label></td>
                <td><label for="specialization1">Specialization</label></td>
                <td><label for="graduateYr1">Graduate Year</label></td>
            </tr>
            <tr>
                <td>1.</td>
                <td><input type="text" name="institution1" id="institution1" size="52" maxlength="50" placeholder="Institution" required/></td>
                <td><select name="level1" id="level1">
                        <option></option>
                        <option value="SPM" selected="selected">SPM</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Degree">Degree</option>
                        <option value="Master">Master</option>
                        <option value="PhD">PhD</option>
                    </select></td>
                <td><input type="text" maxlength="50" name="specialization1" id="specialization1" size="52" placeholder="Specialization" title="Leave blank if none" /></td>
                <td><input type="text" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                            name="graduateYr1" size="15" id="graduateYr1" placeholder="Graduate Year" required/></td>
            </tr>
        </table>
        <br />
        <table align="center" cellpadding="6" border="0" id="tblEducation">
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
        <label id="qError"></label>
        <table align="center" cellpadding="6" border="0">
            <caption>Work Experience(if any)</caption>
            <tr>
                <td></td>
                <td>Company</td>
                <td>Position</td>
                <td>From Year</td>
                <td>-</td>
                <td>To Year</td>
            </tr>
            <tr>
                <td>1.</td>
                <td><input type="text" name="company1" id="company1" size="52" maxlength="50" placeholder="Company"/></td>
                <td><input type="text" name="position1" id="position1" size="27" maxlength="25" placeholder="Position"/></td>
                <td><input type="text" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                            name="workFrom1" size="8" id="workFrom1" placeholder="From Year"/></td>
                <td>-</td>
                <td><input type="text" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                            name="workTo1" size="6" id="workTo1" placeholder="To Year"/></td>
            </tr>
        </table>
        <table align="center" cellpadding="6" border="0" id="tblWork">
        </table>
        <table align="center" cellpadding="6" border="0">
            <tr>
                <td colspan="6" style="text-align:center">
                    <input type="button" name="plus" id="plus" value="+" onclick="plusWork()"  class="button site"/>
                    <input type="button" name="minus" id="minus" value="-" onclick="minusWork()" class="button site"/>
                </td>
            </tr>
        </table>
        <br/>
        <table align="center" cellpadding="6" border = "0">
            <caption>Comments(Optional)</caption>
            <tr>
                <td class="move" style="table-layout: fixed">Remarks: </td>
                <td><textarea name="stfRem" id="stfRem" rows="5" cols="52" maxlength="500"></textarea> </td>
            </tr>
        </table>

        <br /><br />
        <input type="hidden" name="counterEdu" id="counterEdu" value="1"/>
        <input type="hidden" name="counterWork" id="counterWork" value="1"/>
        <input type="button" name="btnSub" class="button site" style="width: 100px" id="btnSub" onclick="check()" value="Register"/>
		</div>
    </form>
</div>
</body>