<?php
/**
 Redirects to the appropriate pages for adding items to cart
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
        echo "<script>location='cartLens.php?Id=".$Id."'</script>";
    }
    else if($type == "CONTACT")
    {
        echo "<script>location='cartContact.php?Id=".$Id."'</script>";
    }
    else if($type == "FRAME")
    {
        echo "<script>location='cartFrame.php?Id=".$Id."'</script>";
    }
    else if($type == "SOLUTION")
    {
        echo "<script>location='cartSolution.php?Id=".$Id."'</script>";
    }
    ?>
</div>
</body>
