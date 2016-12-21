#!/usr/local/bin/perl

#use strict;
use Time::Local;
use Getopt::Std;
use POSIX;
use DBI;
use CGI 'standard';

my $mysql_db     = "sharingt_sharingtreeDB";
my $mysql_server = "localhost";
my $mysql_user   = "sharingtree";
my $mysql_pass   = "N.y\$;9qk\@DHr";
#
my $graphics     = "../graphics";

my $cgi = new CGI;

my $userID = $cgi->param('userID');
my $Entry_Date = $cgi->param('Entry_Date');
my $Agency = $cgi->param('agency');
my $Tag = $cgi->param('Tag');
my $First_Name = $cgi->param('First_Name');
my $Middle_Initial = $cgi->param('Middle_Initial');
my $Last_Name = $cgi->param('Last_Name');
my $Phone_Number = $cgi->param('Phone_Number');
my $Alt_Number = $cgi->param('Alt_Number');
my $Address = $cgi->param('Address');
my $City = $cgi->param('City');
my $State = $cgi->param('State');
my $Zip = $cgi->param('Zip');
my $Township = $cgi->param('Township');
my $DOB = $cgi->param('DOB');
my $AoCD = $cgi->param('AoCD');
my $Gender = $cgi->param('Gender');
my $Pant_Text = $cgi->param('Pant_Text');
my $Pant_Size = $cgi->param('Pant_Size');
my $Shoe_Text = $cgi->param('Shoe_Text');
my $Shoe_Size = $cgi->param('Shoe_Size');
my $Shirt_Text = $cgi->param('Shirt_Text');
my $Shirt_Size = $cgi->param('Shirt_Size');
my $Gift_One = $cgi->param('Gift_One');
my $Gift_One_Price = $cgi->param('Gift_One_Price');
my $Gift_Two = $cgi->param('Gift_Two');
my $Gift_Two_Price = $cgi->param('Gift_Two_Price');

if ( $userID eq "") {
	exec("../index.cgi");
}

if ($First_Name eq "")		{ $First_Name = "not provided"; }
if ($Middle_Initial eq "")	{ $Middle_Initial = "not provided"; }
if ($Last_Name eq "")		{ $Last_Name = "not provided"; }
if ($DOB eq "")			{ $DOB = "not provided"; }
if ($AoCD eq "")		{ $AoCD = "not provided"; }
if ($Gender eq "")		{ $Gender = "not provided"; }
if ($Phone_Number eq "")	{ $Phone_Number = "not provided"; }
if ($Alt_Number eq "")		{ $Alt_Number = "not provided"; }
if ($Address eq "")		{ $Address = "not provided"; }
if ($City eq "")		{ $City = "not provided"; }
if ($Township eq "")		{ $Township = "not provided"; }
if ($State eq "")		{ $State = "not provided"; }
if ($Zip eq "")			{ $Zip = "not provided"; }
if ($Gift_One eq "")		{ $Gift_One = "not provided"; }
if ($Gift_One_Price eq "")	{ $Gift_One_Price = "not provided"; }
if ($Gift_Two eq "")		{ $Gift_Two = "not provided"; }
if ($Gift_Two_Price eq "")	{ $Gift_Two_Price = "not provided"; }
if ($Pant_Text eq "")		{ $Pant_Text = "not provided"; }
if ($Pant_Size eq "")		{ $Pant_Size = "not provided"; }
if ($Shoe_Text eq "")		{ $Shoe_Text = "not provided"; }
if ($Shoe_Size eq "")		{ $Shoe_Size = "not provided"; }
if ($Shirt_Text eq "")		{ $Shirt_Text = "not provided"; }
if ($Shirt_Size eq "")		{ $Shirt_Size = "not provided"; }
if ($Entry_Date eq "")		{ $Entry_Date = "not provided"; }
if ($Agency eq "")		{ $Agency = "not provided"; }
if ($Tag eq "")			{ $Tag = "not provided"; }

# hack for now
$EntryDate = localtime(time);

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);

#my $sql_statement = "insert into Client (First_Name, Middle_Name, Last_Name, DOB, AoCD, Gender, Phone_Number, Alt_Phone, Address, City, Township, State, Zip_Code, Gift_Name, Price, Gift_Name_2, Price_2, Pant_Text, Pant_Size, Shoes_Text, Shoes_Size, Shirt_Text, Shirt_Size, Entry_Date, Agency_Name, Tag) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
my $sql_statement = "insert into Client (First_Name, Middle_Name, Last_Name, DOB, AoCD, Gender, Phone_Number, Alt_Phone, Address, City, Township, State, Zip_Code, Gift_Name, Price, Gift_Name_2, Price_2, Pant_Text, Pant_Size, Shoes_Text, Shoes_Size, Shirt_Text, Shirt_Size, Agency_Name, Tag) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

my $sth = $DBHandle->prepare($sql_statement);

#$sth->execute($First_Name, $Middle_Initial, $Last_Name, $DOB, $AoCD, $Gender, $Phone_Number, $Alt_Number, $Address, $City, $Township, $State, $Zip, $Gift_One, $Gift_One_Price, $Gift_Two, $Gift_Two_Price, $Pant_Text, $Pant_Size, $Shoe_Text, $Shoe_Size, $Shirt_Text, $Shirt_Size, $Entry_Date, $Agency, $Tag);
$sth->execute($First_Name, $Middle_Initial, $Last_Name, $DOB, $AoCD, $Gender, $Phone_Number, $Alt_Number, $Address, $City, $Township, $State, $Zip, $Gift_One, $Gift_One_Price, $Gift_Two, $Gift_Two_Price, $Pant_Text, $Pant_Size, $Shoe_Text, $Shoe_Size, $Shirt_Text, $Shirt_Size, $Agency, $Tag);

exec("./main.cgi -B $userID userID=$userID");

exit(0);

########################################################################################

sub database_connect {
    my $mysql_user   = shift;
    my $mysql_pass   = shift;
    my $mysql_db     = shift;
    my $mysql_server = shift;

    my $DBHandle = DBI->connect ("DBI:mysql:database=$mysql_db;host=$mysql_server", $mysql_user, $mysql_pass, {'RaiseError' => 1});

    return $DBHandle;
}
