<?php
/**Determines the menubar to be used in the project
 */
date_default_timezone_set("Asia/Kuala_Lumpur");
$Mtime = date('Y-m-d');
    if($_SESSION['log'] == 'o')
    {
        $_SESSION['Username'] = "";
        $_SESSION['UserId'] = "";
        $_SESSION['AccType'] = "";
        $_SESSION['Login'] = false;
        include("Customer Menubar.php");
    }
    else if($_SESSION['log'] == 'a')
    {
        $_SESSION['Login'] = true;
        include("Admin Menubar.php");
    }
    else if($_SESSION['log'] == 's')
    {
        $_SESSION['Login'] = true;
        include("Staff Menubar.php");
    }
    else if($_SESSION['log'] == 'm')
    {
        $_SESSION['Login'] = true;
        include("Member Menubar.php");
    }
    else
    {
        include("Customer Menubar.php");
    }