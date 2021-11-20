<?php
/**
 Verifies that the account belongs to the correct user
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
if($_SESSION['Login'] == true)
{
    $previous = "javascript:history.go(-1)";
    if(isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }
    echo "<script>alert('Your account has already been verified, you do not need access to this page');</script>";
    echo "<script>location = '". $previous ."'; </script>";
}?>
<title>Verify Account</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>Verify Account</h1>
    <h3>Enter the verification code you have received</h3>
    <br/>
   <form method="post">
       <table>
           <caption>Enter Code</caption>
           <tr>
               <td><label for="vCode">Verification Code: </label></td>
               <td><input type="text" name="vCode" id="vCode" maxlength="8" size="10" required> </td>
           </tr>
           <tr>
               <td colspan="100%"><input type="submit" name="btnSub" id="btnSub" value="Submit Code" class="button"> </td>
           </tr>
       </table>
   </form>
    <br/>
    <?php
   if($_REQUEST['btnSub'])
   {
       $SQL = "SELECT * FROM tbllogin WHERE Status = 'N' AND veriCode = '".trim($_POST['vCode'])."'";
       $Result = mysqli_query($Link, $SQL);
       $flag = 0;

       if(mysqli_num_rows($Result) > 0)
       {
           $RowInfo = mysqli_fetch_array($Result);
           $UpSQL = "UPDATE tbllogin SET Status = 'A', Password = '".strtoupper(md5(trim($RowInfo['Password'])))."' WHERE veriCode = '".trim($_POST['vCode'])."'";
           $UpSQLResult = mysqli_query($Link, $UpSQL);
           $flag = 1;
           echo "<h1 style='color: green;'>Your Account Has Been Successfully Verified!</h1>";
           echo "<br/>";
           echo "<h3>Your Username: ".$RowInfo['Username']."</h3>";
           echo "<br/>";
           echo "<h3>Your Password: <div class='spoiler'>".$RowInfo['Password']."</div></h3>";
           echo "<br/><br/>";
           echo "<a href='Login.php'><input type='button' value='Proceed to Login' class='button' style=\" font-size: 36px\"></a>";
       }
       else
       {
           echo "<h1 style='color: red;'>You have either entered the wrong code, or your account has already been verified</h1>";
       }
   }
    ?>
</div>
</body>
