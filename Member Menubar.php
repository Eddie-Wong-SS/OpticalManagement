<?php
/** Menubar for the usage of the customer, also the default menubar
 */
session_start();
?>
<link rel="stylesheet" type = "text/css"
      href="Menubar.css" />
<style>
    .navigate{background-color: transparent !important; border-color: transparent !important; table-layout: fixed}
</style>
<div align="right" class="navbar">
    <table class="navigate" border="0" align="right">
        <tr>
            <td class="paddy"><div class="dropdown"><a href="Main%20Page.php" style="color: black;text-decoration: none;display: block; padding: 16px;
    font-size: 16px;"> Main Menu </a> </div></td>
            <td class="paddy"><div class="dropdown">
                    <button class="dropbtn">Histories &#9660</button>
                    <div class="dropdown-content">
                        <a href="viewPrescrip.php?Id=<?php echo $_SESSION['IC']; ?>&type=PE">Medical History</a>
                        <a href="viewSales.php?type=R&name=<?php echo $_SESSION['IC'] ?>">Buy History</a>
                        <a href="viewPayment.php?name=<?php echo $_SESSION['IC'] ?>">Payment History</a>
                    </div>
                </div></td>
            <td class="paddy"><div class="dropdown"><button class="dropbtn"> <?php echo $_SESSION['Username']."&#9660"; ?> </button>
                    <div class="dropdown-content">
                        <a href="editPatient.php?Id=<?php echo $_SESSION['IC']; ?>&type=C">Profile</a>
                        <a href="editPW.php?Id=<?php echo $_SESSION['IC']; ?>">Change Password</a>
                        <a href="Main Page.php?log=o">Logout</a>
                    </div> </div> </td>
        </tr>
    </table>
</div>
<br />