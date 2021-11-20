<?php
/** Creates the database of the system and a default administrator account */
error_reporting();
$Username = "username";
$Pass = "password";
$host = "localhost";
$Name = "dbadmin";
$TableList = array(
    "CREATE TABLE tblCustomer(
		CustIC varchar(14) PRIMARY KEY,
		CustName varchar(50),
		AccType varchar(8),
		Gender CHAR(1),
		Address VARCHAR(250),
		Phone int,
		DOB date,
		Email VARCHAR(40),
		imageLoc VARCHAR(50),
		Status char(1) DEFAULT 'A')",

    "CREATE TABLE tblConMedRec( 
		cID int Primary Key AUTO_INCREMENT,
		CCode varchar(10),
        CustIC varchar(14),
        checkDate date,
		checkBy varchar(50),
        Eye char(2),
        Pwr float,
		BC float,
		Dia float,
		Cyl float,
		Axis int,
		addPwr float
		expireDate date,
		Status char(1) DEFAULT 'A')",
		
	"CREATE TABLE tblGlassMedRec( 
		gID int Primary Key AUTO_INCREMENT,
		GCode varchar(10),
        CustIC varchar(14),
        checkDate date,
		checkBy varchar(50),
        Eye char(2),
        Sphere float,
		Cylinder float,
		Axis int,
		Prism float,
		Base varchar(4),
		addPwr float,
		expireDate date,
		Remark varchar(500),
		Status char(1) DEFAULT 'A')",
		
    "CREATE TABLE tblStaff(
		StaffIC varchar(14) PRIMARY KEY,
		StaffName varchar(50),
		Gender CHAR(1),
		Address VARCHAR(250),
		DOB DATE,
		Phone int,
		Email VARCHAR(40),
		Position varchar(20),
		imageLoc varchar(50),
		Remark varchar(500),
		Status char(1) DEFAULT 'A')",

    "CREATE TABLE tblStaffQual(
        ID int Primary Key AUTO_INCREMENT,
		StaffIC varchar(14),
		Qualification varchar(50),
		Specialization varchar(50),
		School varchar(50),
		gradYear int)",
		
	"CREATE TABLE tblWorkExp(
        ID int Primary Key AUTO_INCREMENT,
		StaffIC varchar(14),
		Position varchar(25),
		Company varchar(50),
		fromYear int(4),
		toYear int(4))",
		
    "CREATE TABLE tblInventory(
		ItemId INT PRIMARY KEY AUTO_INCREMENT,
		Code varchar(25),
		ItemName VARCHAR(50),
		ItemDesc VARCHAR(100),
		ItemType varchar(11),
		ReorderLim int,
		imageLoc varchar(50),
		Status char(1) DEFAULT 'A')",
		
	"CREATE TABLE tblSellPrice(
		ID int PRIMARY KEY AUTO_INCREMENT,
		ItemId int,
		Price Float,
		Status char(1) DEFAULT 'A')",
		
	"CREATE TABLE tblCurrentStock(
		ItemId INT Primary Key,
		CurQuan int)",

    "CREATE TABLE tblSupplies(
		sID int Primary Key AUTO_INCREMENT,
        ItemName varchar(50),
        SuppId VARCHAR(12),
		ETA int,
		Price float,
		Remark varchar(500),
		Status char(1) DEFAULT 'A')",

    "CREATE TABLE tblLens(
		lensCode varchar(25) PRIMARY KEY,
		lensCategory varchar(25),
		lensType varchar(25),
		lensMaterial varchar(25),
		lensColor varchar(20),
		Sphere float,
		Cylinder float,
		Axis int,
		Prism float,
		Base varchar(4),
		addPwr float,
		Treatment varchar(250))",

    "CREATE TABLE tblFrames(
		frameCode varchar(25) PRIMARY KEY,
		frameMaterial varchar(25),
		Color varchar(12),
		Shape varchar(10),
		Hinge varchar(20))",

    "CREATE TABLE tblContacts(
		contactCode varchar(25) PRIMARY KEY,
		conCategory varchar(25),
		conType varchar(25),
		conMaterial varchar(25),
		conColor VARCHAR(20),
		Sphere float,
		BC float,
		Dia float,
		Cyl float, 
		Axis int,
		addPwr float,
		ExpireDateC DATE)",

    "CREATE TABLE tblSolution(
		solutionCode varchar(25) PRIMARY KEY,
		Type varchar(15),
		forcontact varchar(20),
		ExpireDateS DATE)",

    "CREATE TABLE tblSupplier(
		SuppId int AUTO_INCREMENT PRIMARY KEY,
		SuppName VARCHAR(50),
		Address varchar(250),
		Email varchar(40),
		SuppNo int,
		ContactPerson VARCHAR(50),
		ContactNo int,
		ContactEmail varchar(40),
		Status char(1) DEFAULT 'A')",
		
	"CREATE TABLE tblReorder(
		ID INT PRIMARY KEY AUTO_INCREMENT,
		reCode varchar(25),
		SuppId INT,
		SuppName VARCHAR(50),
		ItemName VARCHAR(50),
		ETA int,
		Price FLOAT,
		totalPrice FLOAT,
		Quantity INT,
		getTime DATETIME DEFAULT CURRENT_TIMESTAMP,
		OrderDate date As (DATE(getTime)))",
		
	
	"CREATE TABLE tblPurchase(
		ID INT PRIMARY KEY AUTO_INCREMENT,
		purCode varchar(25),
		SuppId INT,
		SuppName VARCHAR(50),
		ItemName VARCHAR(50),
		ItemDesc VARCHAR(100),
		Quantity INT,
		getTime DATETIME DEFAULT CURRENT_TIMESTAMP,
		OrderDate date As (DATE(getTime)))",

    "CREATE TABLE tblInvoice(
        invoiceCode varchar(25) PRIMARY KEY,
        CustIc varchar(14),
        Username varchar(35),
        TotalPrice float,
		Discount float,
        DateSold date,
		Status char(1) DEFAULT 'U')",

    "CREATE TABLE tblItemSold(
		ID int Primary Key AUTO_INCREMENT,
        invoiceCode varchar(25),
        itemId int,
        Quantity int,
		Price float,
        fullPrice float)",

    "CREATE TABLE tblReceipt(
		payCode varchar(25) Primary Key,
        invoiceCode varchar(25),
		CustIC varchar(14),
		Collector varchar(35),
		payType varchar(25),
		PayNo int DEFAULT '0',
		bankReceipt varchar(50) DEFAULT 'NONE',
        datePaid date,
        amount float)",

    "CREATE TABLE tbllogin(
		userId int PRIMARY KEY AUTO_INCREMENT,
		IC varchar(14),
		Username VARCHAR(35),
		Password VARCHAR(32),
		veriCode varchar(8),
		AccType VARCHAR(10),
		Status char(1) DEFAULT 'A'	
	)");
//21 tables
$Link = mysqli_connect($host, $Username, $Pass) or die(mysqli_error($Link));

if($Link)
{
    if(!mysqli_select_db($Link, $Name))
    {
        $SQL = "CREATE DATABASE ". $Name;
        mysqli_query($Link,$SQL);
    }
    mysqli_select_db($Link, $Name);
    for($i = 0; $i<count($TableList);++$i)
    {
        mysqli_query($Link, $TableList[$i]);
    }
    $SQL = "SELECT * FROM tbllogin WHERE Username = 'ADMIN' AND Password = '".md5("pass")."' AND Status = 'A'";
    $Result = mysqli_query($Link, $SQL);
    if(mysqli_num_rows($Result) == 0)
    {
        $SQL = "INSERT INTO tbllogin(IC, Username, Password, AccType, Status) VALUES('12341234', 'ADMIN','".md5('pass')."','ADMIN','A')";
        $Result = mysqli_query($Link, $SQL);
    }

}
else
{
    echo "<script language='JavaScript'>alert('Failed to connect');</script>";
}
?>