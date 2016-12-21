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
my $Client_ID = $cgi->param('Client_ID');
my $Last_Name = $cgi->param('Last_Name');
my $First_Name = $cgi->param('First_Name');
my $Middle_Name = $cgi->param('Middle_Name');
my $Suffix_Name = $cgi->param('Suffix_Name');
my $DOB = $cgi->param('DOB');
my $Address = $cgi->param('Address');
my $City = $cgi->param('City');
my $State = $cgi->param('State');
my $Zip_Code = $cgi->param('Zip_Code');
my $Phone_Number = $cgi->param('Phone_Number');
my $Gift_Description = $cgi->param('Gift_Description');
my $Gift_Name = $cgi->param('Gift_Name');
my $Price = $cgi->param('Price');
my $Gift_Name_2 = $cgi->param('Gift_Name_2');
my $Price_2 = $cgi->param('Price_2');
my $Pant_Text = $cgi->param('Pant_Text');
my $Pant_Size = $cgi->param('Pant_Size');
my $Shoes_Text = $cgi->param('Shoes_Text');
my $Shoes_Size = $cgi->param('Shoes_Size');
my $Shirt_Text = $cgi->param('Shirt_Text');
my $Shirt_Size = $cgi->param('Shirt_Size');
my $Agency_Name = $cgi->param('Agency_Name');
my $Category = $cgi->param('Category');
my $Tag = $cgi->param('Tag');

if ( ( $userID eq "" ) or ( $Client_ID eq "" ) ) {
	exec("../index.cgi");
}

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);
my $sql_statment = "update Client set First_Name=\"$First_Name\", Last_Name=\"$Last_Name\", Middle_Name=\"$Middle_Name\", Suffix_Name=\"$Suffix_Name\", DOB=\"$DOB\", Phone_Number=\"$Phone_Number\", Address=\"$Address\", City=\"$City\", State=\"$State\", Zip_Code=\"$Zip\", Gift_Name=\"$Gift_Name\", Price=\"$Price\", Gift_Name_2=\"$Gift_Name_2\", Price_2=\"$Price_2\", Pant_Text=\"$Pant_Text\", Pant_Size=\"$Pant_Size\", Shoes_Text=\"$Shoes_Text\", Shoes_Size=\"$Shoes_Size\", Shirt_Text=\"$Shirt_Text\", Shirt_Size=\"$Shirt_Size\", Tag=\"$Tag\", Category=\"$Category\", Gift_Description=\"$Gift_Description\" Where Client_ID=$Client_ID";
my $sth = $DBHandle->prepare($sql_statment);
$sth->execute();

exec("./display.cgi -B $userID userID=$userID");

#
#print $cgi->table({-border=>1,width=>'100%'},
#	$cgi->Tr({-align=>left},
#	[
#		$cgi->td({-valign=>'top',-width=>'5%'},
#		[
#			$userID,
#			$Client_ID,
#			$Last_Name,
#			$First_Name,
#			$DOB,
#			$Address,
#			$City,
#			$State,
#			$Zip_Code,
#			$Phone_Number,
#			$Email,
#			$Gift_Name,
#			$Gift_Description,
#			$Price,
#			$Agency_Name,
#			$Tag
#		]
#		)
#	]
#	)
#);
#print $cgi->br();
#print $cgi->font({face=>'arial',size=>'-3'},
##	("Copyright United Way Midland County, All Rights Reserved.")
#);
#print $cgi->end_html;
#exit(0);






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
