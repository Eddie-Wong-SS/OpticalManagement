<?php
/** Menubar for the usage of the administrator */
session_start();
?>
<link rel="stylesheet" type = "text/css"
      href="Menubar.css" />
<style>
   .navigate{background-color: transparent !important; border-color: transparent !important; table-layout: initial}
</style>
<div align="right" class="navbar">
    <table class="navigate" border="0">
        <tr>
            <td class="paddy"><div class="dropdown"><a href="Main%20Page.php" style="color: black;text-decoration: none;display: block; padding: 16px;
    font-size: 16px;"> Main Menu </a> </div></td>
            <td class="paddy"><div class="dropdown">
                    <button class="dropbtn">User &#9660</button>
                    <div class="dropdown-content">
                        <a href="viewStaffCust.php?id=A">Add Staff/Patient Account</a>
                        <a href="viewAcc.php?Id=V&type=S">View Accounts</a>
                    </div>
                </div></td>
            <td class="paddy"><div class='dropdown'><button class='dropbtn'>Customer &#9660</button>
                    <div class='dropdown-content'>
                        <a href='addPatient.php'>Customer Registration </a>
                        <a href="viewPatient.php?id=V">Member/Customer View</a>
                    </div></div></td>
            <td class="paddy"><div class="dropdown"><button class="dropbtn">Prescription &#9660</button>
                <div class="dropdown-content">
                    <a href="viewPatient.php?id=C">Add Contact Prescription</a>
                    <a href="viewPatient.php?id=G">Add Glasses Prescription</a>
                    <a href="viewPatient.php?id=PV">Medical Record Lookup</a>
                </div> </div> </td>
                <td>
                    <div class="dropdown">
                        <button class="dropbtn"> Staff &#9660</button>
                        <div class="dropdown-content">
                            <a href="addStaff.php">Staff Registration</a>
                            <a href="viewStaff.php?id=V">Staff View</a>
                        </div>
                    </div>
                </td>
            <td class="paddy"><div class="dropdown"><button class="dropbtn"> Inventory &#9660</button>
                <div class="dropdown-content">
                    <a href="addLens.php">Add Lens</a>
                    <a href="addFrame.php">Add Frame</a>
                    <a href="addContacts.php">Add Contact Lens</a>
                    <a href="addSolution.php">Add Contact Solution</a>
                    <a href="viewInventory.php?id=V">View Inventory</a>
                </div> </div> </td>
            <td class="paddy">
                <div class="dropdown">
                    <button class="dropbtn"> Stocks &#9660</button>
                    <div class="dropdown-content">
                        <a href="viewInventory.php?id=P">Set Item Price</a>
                        <a href="viewInventory.php?id=Q">Set Item Stock</a>
                        <a href="viewInventory.php?id=VS">View Item Price and Quantity</a>
                    </div>
                </div>
            </td>
            <td class="paddy"><div class="dropdown"><button class="dropbtn"> Supplier &#9660</button>
                    <div class="dropdown-content">
                        <a href="addSupplier.php">Add Supplier</a>
                        <a href="viewSupplier.php?id=V">View Suppliers</a>
                        <a href="viewSupplied.php?id=V">View Supplied Items</a>
                        <a href="viewReorders.php">View Reorders</a>
                        <a href="viewPurchase.php">View Purchases</a>
                    </div> </div> </td>

            <td class="paddy"><div class="dropdown"><button class="dropbtn"> Sales &#9660</button>
                    <div class="dropdown-content">
                        <a href="viewPatient.php?id=S">Record Sale and Create Invoice</a>
                        <a href="viewSales.php?type=V">View Sales</a>
                    </div> </div> </td>

            <td class="paddy"><div class="dropdown"><button class="dropbtn">Report &#9660</button>
                    <div class="dropdown-content">
                        <a href="viewSales.php?type=R">Sales Report</a>
                        <a href="viewPayment.php">Payment Report</a>
                        <a href="viewSold.php">Items Sold Report</a>
                    </div> </div> </td>

            <td class="paddy"><div class="dropdown"><button class="dropbtn"> <?php echo $_SESSION['Username']."&#9660"; ?> </button>
                    <div class="dropdown-content">
                        <a href="Main Page.php?log=o">Logout</a>
                    </div> </div> </td>
        </tr>
    </table>
</div>
<br />
