<?php
/**
Allows for the viewing of patient results
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
include ('Email.php');
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

</script>
<title>Patient Search Result</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<br/>
<div class="container" align="center">
    <?php
    if($_REQUEST['btnDelete'])
    {
        // while(list($key,$val) = each($_POST)) each function is deprecated
        foreach($_POST as $key => $val)
        {
            if($key != "chkAll" && $key != "btnDelete")
            {
                $DelEmpSQL = "UPDATE tblcustomer SET Status = 'I' WHERE CustIC = '".$key."'";
                $DelEmpResult = mysqli_query($Link, $DelEmpSQL);

                $DelPatAcc = "SELECT * FROM tbllogin WHERE IC = '".$key."'";
                $DelPatAccRes = mysqli_query($Link, $DelPatAcc);
                if(mysqli_num_rows($DelPatAccRes) > 0)
                {
                    $DeactAcc = "UPDATE tbllogin SET Status = 'I' WHERE IC = '".$key."'";
                    $DeactRes = mysqli_query($Link, $DeactAcc);
                }
            }
        }
        if($DelEmpResult)
        {
            $id = $_SESSION['id'];
            echo "<script>alert('Selected record(s) has been deleted');location='viewPatientResult.php?id=$id&page=1';</script>";
        }
    }
    else if($_REQUEST['btnSub'])
    {
       foreach($_POST as $key => $val)
       {
           if($key != "chkAll" && $key != "btnSub")
           {
                $SQLI = "SELECT CustName, Email from tblcustomer WHERE CustIC= '".$key."'";
                $SQLIResult = mysqli_query($Link, $SQLI);
                $RowInfo = mysqli_fetch_array($SQLIResult);

                if(mysqli_num_rows($SQLIResult) > 0)
                {
                    $setSQL = "SELECT AccType FROM tblcustomer WHERE CustIC = '".$key."'";
                    $setSQLResult = mysqli_query($Link, $setSQL);
                    $sRowInfo = mysqli_fetch_array($setSQLResult);
                    if($sRowInfo['AccType'] != "MEMBER")
                    {
                        $aSQL = "UPDATE tblcustomer SET AccType = 'MEMBER'";
                        $aSQLResult = mysqli_query($Link, $aSQL);
                    }

                    $name = generate_unique_username($RowInfo['CustName'], 9999);
                    $PW = randomPassword(10, 1, "lower_case,numbers,upper_case,special_symbols");
                    $random_hash = substr(uniqid(rand(), true), 8, 8);
                    $SQLAdd = "INSERT INTO tbllogin(IC, Username, Password, veriCode, AccType, Status) VALUES (
                    '".strtoupper(trim($key))."',
                    '".strtoupper(trim($name))."',
                    '".strtoupper(trim($PW))."',
                     '".$random_hash."',
                    'MEMBER',
                    'N'
                    )";
                    $SQLAddResult = mysqli_query($Link, $SQLAdd);
                    $Verify = "An account has been created for you in Optical Store Management! Please enter the code below to verify your account and receive your new username and password!<br/>
                                ".$random_hash."<br/> Please ignore if you did not wish or plan on having an account with us";
                    sendEmail("Account Verification", $Verify, $RowInfo['Email']);
                }
           }
       }
        echo "<script>location='viewPatientResult.php?id=A&page=1';</script>";
    }
    else{
        $SQL = $_SESSION['SQL'];
        $Result = mysqli_query($Link, $SQL);
        if($Result)
        {
            if(mysqli_num_rows($Result) > 0)
            {
                $id = $_GET['id'];
                $_SESSION['id'] = $id;
                $page = $_GET['page'];
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
                        echo "<button><a style='font-size: 21px'  href='viewPatientResult.php?page=".($page+1)."'>&#62;</a> </button>"?> &nbsp;
                    &nbsp;<?php if($page <= $maxPage && $page != 1)
                        echo "<button><a style='font-size: 21px'  href='viewPatientResult.php?page=".($page-1)."'>&#60;</a> </button>"?>
                    <table align="center">
                        <tr>
                            <th style="background-color: blue; color: white" colspan='100%'>Showing <?php echo $minLim ." to ". $maxLim ." of ". $maxRec; ?> Results</th>
                        </tr>
                        <tr>
                            <th scope="col">No</th>
                            <?php $count = mysqli_num_rows($Result);
                            echo "<th scope='col'><input type='checkbox' name=\"chkAll\" id=\"chkAll\" onClick=\"toggle($count)\"></th>"; ?>
                            <th scope="col">Patient Name</th>
                            <th scope="col">Patient IC</th>
                            <th scope="col">Account Type</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Email</th>
                            <?php
                            if($id != 'A' && $id != "")
                            {
                                echo "<th colspan=\"100%\">Actions</th>";
                            }
                            ?>
                        </tr>
                        <?php
                        for($i = $minLim ; $i <= $maxLim; ++$i)
                        {
                            $RowInfo = mysqli_fetch_array($Result);
                            echo "<tr>";
                            echo "<td>".($i)."</td>";
                            echo "<td><input type=\"checkbox\" name=\"".$RowInfo['CustIC']."\" id=\"Rec".($i)."\"></td>";
                            echo "<td>".$RowInfo['CustName']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['CustIC']."</td>";
                            echo "<td>".$RowInfo['AccType']."</td>";
                            echo "<td style='text-align: center'>".$RowInfo['Phone']."</td>";
                            echo "<td>".$RowInfo['Address']."</td>";
                            echo "<td>".$RowInfo['Gender']."</td>";
                            echo "<td>".$RowInfo['Email']."</td>";
                            if($id == 'V')
                            echo "<td><a class= 'hoverme' href=\"editPatient.php?Id=".$RowInfo['CustIC']."\">Edit</a></td>";
                            if($id == 'C')
                            echo "<td><a class= 'hoverme' href=\"addContactPrescrip.php?Id=".$RowInfo['CustIC']."\">Add Contact Precrip</a></td>";
                            if($id == 'G')
                            echo "<td><a class= 'hoverme' href=\"addGlassPrescrip.php?Id=".$RowInfo['CustIC']."\">Add Glass Prescrip</a></td>";
                            if($id == 'PV')
                            echo "<td><a class= 'hoverme' href=\"viewPrescrip.php?Id=".$RowInfo['CustIC']."&type=V\">View Prescriptions</a></td>";
                            if($id == 'S')
                            {
                                echo "<td><a class= 'hoverme' href=\"Sales.php?Id=".$RowInfo['CustIC']."\">Add Invoice</a></td>";
                            }
                            echo "</tr>";
                        }
                            echo "<tr>";
                            echo "<td></td>";
                            echo "<td style='background-color: initial'></td>";
                            echo "<td align=\"center\" colspan='100%'><input type=\"submit\" name=\"btnDelete\" value=\"Delete Checked\" onclick='return confirm(\"This will delete the selected records. Proceed?\");' id=\"Delete\" class='button'>";
							if($id == 'A')
                            echo "<input type=\"submit\" name=\"btnSub\" value=\"Register Accounts\" id=\"Register\" onclick='return confirm(\"This will register accounts for the selected. Continue?\")' class='button'></td>";
                            echo "</tr>";

                       ?>
                    </table>
                </form>
            <?php }
        }
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
