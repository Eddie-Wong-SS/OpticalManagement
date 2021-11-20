<?php
/** Default page of the system*/
error_reporting(1);
session_start();
include("database.php");
include("Email.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<script>
    Push.Permission.request();
    function checkers()
    {
        Push.create('Warning!', {
            body: 'Item of code <?php echo $_SESSION['Low']; ?> is at or lower than the reorder limit, please click this notification to reorder',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                location='ReorderLow.php?Id=<?php echo $_SESSION['Low'] ?>';
            }
        });
    }

    function expireE()
    {
        Push.create('Warning!', {
            body: 'Item of code <?php echo $_SESSION['ExpiredE']; ?> is close to or already expired, please click this notification to reorder',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                location='ReorderLow.php?Id=<?php echo $_SESSION['ExpireE'] ?>';
            }
        });
    }

    function expireS()
    {
        Push.create('Warning!', {
            body: 'Item of code <?php echo $_SESSION['ExpiredS']; ?> is close to or already expired, please click this notification to reorder',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
                location='ReorderLow.php?Id=<?php echo $_SESSION['ExpireS'] ?>';
            }
        });
    }

    function expireCP()
    {
        Push.create('Warning!', {
            body: 'Your contact prescription has expired, please get an eye test as soon as possible',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
            }
        });
    }

    function expireGP()
    {
        Push.create('Warning!', {
            body: 'Your glasses prescription has expired, please get an eye test as soon as possible',
            icon: 'icon.png',
            timeout: 8000,                  // Timeout before notification closes automatically.
            onClick: function() {
                // Callback for when the notification is clicked.
            }
        });
    }

</script>
<head>
    <title>Main Page</title>
</head>
<?php
if($_REQUEST['btnLogin'])
{

    if($_POST['txtUsername'] == "" || $_POST['txtPassword'] == "")
    {
        echo "<script>alert('You have not entered a username or password!')</script>";
        $previous = "javascript:history.go(-1)";
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
        echo "<script>location = '". $previous ."'; </script>";
    }
    else
    {
        $SQL = "SELECT * FROM tbllogin WHERE Username = '" . strtoupper(trim($_POST['txtUsername'])) . "' AND Password = '" . md5(trim($_POST['txtPassword'])) . "' AND Status = 'A'";
        $Result = mysqli_query($Link, $SQL);
        if (mysqli_num_rows($Result) > 0)
        {
            $Row = mysqli_fetch_array($Result);

            $_SESSION['Username'] = $Row['Username'];
            $_SESSION['Id'] = $Row['userId'];
            $_SESSION['IC'] = $Row['IC'];
            $_SESSION['AccType'] = $Row['AccType'];
            $_SESSION['Login'] = true;

            if ($_SESSION['AccType'] == "ADMIN")
            {
                $_SESSION['log'] = 'a';
            }
            else if ($_SESSION['AccType'] == "STAFF")
            {
                $_SESSION['log'] = 's';
            }
            else if ($_SESSION['AccType'] == "MEMBER")
            {
                $_SESSION['log'] = 'm';
            }

            if ($_SESSION['AccType'] == "ADMIN" || $_SESSION['AccType'] == "STAFF")
            {
                $Check = "SELECT tblinventory.ItemId FROM tblinventory JOIN tblcurrentstock ON tblinventory.ItemId = tblcurrentstock.ItemId WHERE tblinventory.ItemId = tblcurrentstock.ItemId AND CurQuan <= ReorderLim";
                $CheckResult = mysqli_query($Link, $Check);
                if (mysqli_num_rows($CheckResult) > 0) {
                    $Row = mysqli_fetch_array($CheckResult);
                    if ($Row['CurQuan'] <= 0) {
                        $Deactivate = "UPDATE tblinventory SET Status = 'I' WHERE ItemId = '" . $Row['Item1'] . "'";
                        $DeActSQL = mysqli_query($Link, $Deactivate);
                    }
                    $_SESSION['Low'] = $Row['Item1'];
                    ?>
                    <script>checkers();</script>
                    <?php
                }
                $date = date('Y/m/d', time());
                $ExpireE = "SELECT * FROM tblcontacts WHERE NOW() > ExpireDateC - INTERVAL 1 WEEK";
                $ExpireResultE = mysqli_query($Link, $ExpireE);
                if (mysqli_num_rows($ExpireResultE) > 0) {
                    $RowInfo = mysqli_fetch_array($ExpireResultE);
                    if ($RowInfo['ExpireDateC'] < $date) {
                        $Codes = $RowInfo['contactCode'];
                        $Delete = "UPDATE tblinventory, tblcontacts SET Status = 'I' WHERE tblinventory.Code = tblcontacts.contactCode AND contactCode = '". $Codes ."'";
                        $DeleteResult = mysqli_query($Link, $Delete);
                    }
                    $_SESSION['ExpiredE'] = $RowInfo['contactCode'];
                    ?>
                    <script>expireE();</script>
                    <?php
                }

                $ExpireS = "SELECT * FROM tblsolution WHERE NOW() > ExpireDateS - INTERVAL 1 WEEK";
                $ExpireResultS = mysqli_query($Link, $ExpireS);
                if (mysqli_num_rows($ExpireResultS) > 0) {
                    $InfoRow = mysqli_fetch_array($ExpireResultS);
                    if ($InfoRow['ExpireDateS'] < $date) {
                        $SCode = $InfoRow['solutionCode'];
                        $Remove = "UPDATE tblinventory, tblsolution SET Status = 'I' WHERE tblinventory.Code = tblsolution.solutionCode AND solutionCode = '".$SCode."'";
                        $RemoveResult = mysqli_query($Link, $Remove);
                    }
                    $_SESSION['ExpireS'] = $InfoRow['solutionCode'];
                    ?>
                    <script>expireS();</script>
                    <?php
                }
            }

            $Pat = "SELECT CustName, CustIC, Email FROM tblcustomer WHERE Status = 'A'";
            $PatResult = mysqli_query($Link, $Pat);
            if(mysqli_num_rows($PatResult) > 0)
            {
                for($i = 0; $i < mysqli_num_rows($PatResult); ++$i)
                {
                    $Details = mysqli_fetch_array($PatResult);
                    $Patient = $Details['CustIC'];

                    $CExpire = "SELECT * FROM tblconmedrec WHERE Eye = 'OD' AND expireDate = (SELECT MAX(expireDate) FROM tblconmedrec WHERE CustIC = '$Patient' AND Status = 'A' AND expireDate < NOW()) ";
                    $CExpireR = mysqli_query($Link, $CExpire);
                    if(mysqli_num_rows($CExpireR) > 0)
                    {
                        $ExpireC = mysqli_fetch_array($CExpireR);
                        sendEmail("Expired Prescription!", "This is an automatic email to let you know that your latest contact prescription has recently expired.<br/>For your eye health, it is highly recommended that you get an eye test as soon as possible", $Details['Email']);
                        if($_SESSION['AccType'] == "MEMBER")
                        {
                            ?>
                            <script>expireCP();</script>
                            <?php
                        }
                    }

                    $GExpire = "SELECT * FROM tblglassmedrec WHERE Eye = 'OD' AND expireDate = (SELECT MAX(expireDate) FROM tblglassmedrec WHERE CustIC = '$Patient' AND Status = 'A' AND expireDate < NOW())";
                    $GExpireR = mysqli_query($Link, $GExpire);
                    if(mysqli_num_rows($GExpireR))
                    {
                        $ExpireG = mysqli_fetch_array($GExpireR);
                        sendEmail("Expired Prescription!", "This is an automatic email to let you know that your latest glasses prescription has recently expired.<br/>For your eye health, it is highly recommended that you get an eye test as soon as possible", $Details['Email']);
                        if($_SESSION['AccType'] == "MEMBER")
                        {
                            ?>
                            <script>expireGP();</script>
                            <?php
                        }
                    }
                }
            }
        }
        else
        {
            $SQLN = "SELECT * FROM tbllogin WHERE Username = '" . strtoupper(trim($_POST['txtUsername'])) . "' AND Password = '" . trim($_POST['txtPassword']) . "' AND Status = 'N'";
            $ResultN = mysqli_query($Link, $SQLN);
            if (mysqli_num_rows($ResultN) > 0)
            {
                echo "<script>location = 'verifyAccount.php';</script>";
            }
            else
            {
                echo "<script>alert('Invalid Username or Password'); </script>";
                echo "<script>location = 'Login.php'; </script>";
            }
        }
    }
}
?>
<?php include("Menu.php");
if ($_GET['log'] == 'o')
{
    session_unset();
    session_destroy();
    echo "<script>location = 'Main Page.php'; </script>";
}?>
    <link rel="stylesheet" href="Default%20Theme.css">
<div align="center" class="container" style="height: 100%">
    <h1 style="font: 72px bold;">Welcome To The Optical Store Management System</h1>
    <a href="Login.php"><input type="button" value="Login" class="button" style=" font-size: 36px"> </a>
    <br/><br>
    <a href="verifyAccount.php"><input type="button" value="Verify Account" class="button" style="font-size: 36px"> </a>
    <br/><br/>
	<h2>Not yet a member? Sign up to receive a 5% discount on all purchases!</h2><br/>
    <a href="findCustomer.php?id=S"><input type="button" value="Sign Up" class="button" style="font-size: 36px"> </a>
</div>
