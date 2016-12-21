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

my $Tag = $cgi->param('Tag');
my $Category = $cgi->param('Category');

my $First_Name = $cgi->param('First_Name');
my $Middle_Initial = $cgi->param('Middle_Initial');
my $Last_Name = $cgi->param('Last_Name');
my $Suffix_Name = $cgi->param('Suffix_Name');
my $DOB_M = $cgi->param('DOB_M');
my $DOB_D = $cgi->param('DOB_D');
my $DOB_Y = $cgi->param('DOB_Y');
my $Phone_Number = $cgi->param('Phone_Number');

my $Gift_Description = $cgi->param('Gift_Description');
my $Gift_One_Price = $cgi->param('Gift_One_Price');

if ( $userID eq "") {
	exec("../index.cgi");
}

if ($Tag eq "")			{ $Tag = "not provided"; }
if ($Gift_Description eq "")	{ $Gift_Description = "not provided"; }
if ($Gift_One_Price eq "")	{ $Gift_One_Price = "not provided"; }
if ($Category eq "")		{ $Category = "not provided"; }

if ( ($Tag ne "Red") and ($Tag ne "not provided") and ($Tag ne "Select Tag") ) {
	if ($First_Name eq "")		{ $First_Name = "not provided"; }
	if ($Middle_Initial eq "")	{ $Middle_Initial = "not provided"; }
	if ($Last_Name eq "")		{ $Last_Name = "not provided"; }
	if ($Suffix_Name eq "")		{ $Suffix_Name = ""; }
	if ($Phone_Number eq "")	{ $Phone_Number = "not provided"; }
}

my $DOB = $DOB_M . "/" . $DOB_D . "/" . $DOB_Y;

# hack for now
$EntryDate = localtime(time);

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);

my $Agency = get_agency_name($DBHandle, $userID);

my $sql_statement = "insert into Client (First_Name, Middle_Name, Last_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, Agency_Name) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

my $sth = $DBHandle->prepare($sql_statement);

$sth->execute($First_Name, $Middle_Initial, $Last_Name, $Suffix_Name, $DOB, $Phone_Number, $Gift_Description, $Gift_One_Price, $Tag, $Category, $Agency);

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

sub get_agency_name {
        my $DBHandle  = shift;
        my $userID = shift;
        my $Agency_Name = "";
        my $elements = "";

        my $sql_statement = "select Agency_Name from Account where userID = '$userID'";

        my $sth = $DBHandle->prepare($sql_statement);
        $sth->execute();

        while ($elements = $sth->fetchrow_hashref) {
                $Agency_Name = $$elements{'Agency_Name'};
        }

        return $Agency_Name;
}
