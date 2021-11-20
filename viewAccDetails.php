<?php
/**
 Allows the viewing of the details of a new account
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="printThis-master/printThis.js"></script>
<script>
    function printIt()
    {
        $('.Printing').printThis({
            debug: false,               // show the iframe for debugging
            importCSS: true,            // import page CSS
            importStyle: true,         // import style tags
            printContainer: true,       // grab outer container as well as the contents of the selector
            loadCSS: "http://localhost/php/Default Theme.css",  // path to additional css file - use an array [] for multiple
            pageTitle: "Account Details",              // add title to print page
            removeInline: false,        // remove all inline styles from print elements
            printDelay: 333,            // variable print delay
            header: null,               // prefix to html
            footer: null,               // postfix to html
            base: false ,               // preserve the BASE tag, or accept a string for the URL
            formValues: true,           // preserve input/form values
            canvas: false,              // copy canvas elements (experimental)
            doctypeString: "",       // enter a different doctype for older markup
            removeScripts: false,       // remove script tags from print content
            copyTagClasses: false,   // copy classes from the html &amp; body tag
        });
    }
</script>
<title>Account Details</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />
<?php
if($_GET['Id'] != "")
{
    $SQL = "SELECT tbllogin.* FROM tbllogin WHERE  IC = '".$_GET['Id']."'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) > 0)
    {
        $Row = mysqli_fetch_array($Result);
    }
}
?>
<body>
<div class="container">
    <div class="Printing">
    <table style="table-layout: fixed">
        <caption>Account Details</caption>
        <tr>
            <td class="move"><label for="aIC">IC: </label></td>
            <td><input type="text" name="aIC" id="aIC" value="<?php echo $Row['IC']; ?>" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="aName">Username: </label></td>
            <td><input type="text" name="aName" id="aName" value="<?php echo $Row['Username']; ?>" readonly> </td>
        </tr>
        <tr>
            <td class="move"><label for="aPW">Password: </label></td>
            <td><input type="text" name="aPW" id="aPW" value="<?php echo $Row['Password']; ?>" readonly> </td>
        </tr>
    </table>
    </div>
    <br/>
    <table>
        <tr>
            <td><input type="button" class="button" name="btnPrint" value="Print Details" onclick="printIt()"> </td>
        </tr>
    </table>
</div>
</body>