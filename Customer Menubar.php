<?php
/** Menubar for the usage of the customer, also the default menubar
 */
session_start();
?>
<style>
    .navigate{background-color: transparent !important; border-color: transparent !important; table-layout: fixed}
</style>
<link rel="stylesheet" type = "text/css"
      href="Menubar.css" />
<div align="right" class="navbar">
    <table class="navigate" border="0" align="right">
        <tr>
            <td class="paddy"><div class="dropdown"><a href="Main%20Page.php" style="color: black;text-decoration: none;display: block; padding: 16px;
    font-size: 16px;"> Main Menu </a> </div></td>
		<td class="paddy"><div class="dropdown"><button class="dropbtn"> Profile &#9660</button>
                    <div class="dropdown-content">
                        <a href="findCustomer.php?id=E">Profile Details</a>
                        <a href="findCustomer.php?id=M">Medical History</a>
						<a href="findCustomer.php?id=B">Buy History</a>
                        <a href="findCustomer.php?id=P">Payment History</a>
                    </div> </div> </td>
            <td class="paddy"><div class="dropdown"><button class="dropbtn"> Login/Register &#9660</button>
                    <div class="dropdown-content">
                        <a href="Login.php">Log In</a>
                        <a href="findCustomer.php?id=S">Register</a>
                    </div> </div> </td>
        </tr>
    </table>
</div>
<br />