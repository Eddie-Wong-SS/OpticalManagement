<?php
/** Allows users to login or head to registration*/
error_reporting(1);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Login</title>
<link rel="stylesheet" type = "text/css" href="Menubar.css" />
<?php
if($_SESSION['Login'] == true)
{
    $previous = "javascript:history.go(-1)";
    if(isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }
    echo "<script>alert('You are already logged in, please log out to access this page again');</script>";
    echo "<script>location = '". $previous ."'; </script>";
}
?>
<body>
<link rel="stylesheet" href="Default%20Theme.css">
<div class="container" style="width: 35%; height: 85%">
<form id="form1" name="form1" method="post" action="Main%20Page.php">
   <div align="center"><table width="30%" border="0">
           <caption>Log In</caption>
           <tr>
           </tr>
           <tr style="background-color: inherit">
               <td align="center"><label for="txtUsername">Username: </label><input name="txtUsername" id="txtUsername" type="text" value=""/></td>
           </tr>
           <tr>
               <td align="center"><label for="txtPassword">Password: </label><input name="txtPassword" id="txtPassword" type="password" value=""/></td>
           </tr>
           <tr style="background-color: inherit">
               <td><br/><div align="center">
                       <input name="btnLogin" class = "button"  type="submit"  id="btnLogin" value="Log in" /></div></td>
           </tr>
       </table>
   </div>
</form>
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