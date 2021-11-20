<?php
/**
 Redirects the editing query to the appropriate pages
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>

<body>
<div class="container">
    <?php
    $Id = $_GET['Id'];
    $type = $_GET['type'];
    if($type == "LENS")
    {
        echo "<script>location='editLens.php?Id=".$Id."'</script>";
    }
    else if($type == "CONTACT")
    {
        echo "<script>location='editContacts.php?Id=".$Id."'</script>";
    }
    else if($type == "FRAME")
    {
        echo "<script>location='editFrame.php?Id=".$Id."'</script>";
    }
    else if($type == "SOLUTION")
    {
        echo "<script>location='editSolution.php?Id=".$Id."'</script>";
    }
    ?>
</div>
</body>
