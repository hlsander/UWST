#!/usr/local/bin/perl

#use strict;
use Time::Local;
use Getopt::Std;
use POSIX;
use DBI;
use CGI 'standard';

getopts('B:');

my $mysql_db     = "sharingt_sharingtreeDB";
my $mysql_server = "localhost";
my $mysql_user   = "sharingtree";
my $mysql_pass   = "N.y\$;9qk\@DHr";
#
my $graphics     = "../graphics";

my $cgi = new CGI;
my $userID = $cgi->param('userID');

if ( $userID eq "" ) {
	$userID = $opt_B;
}

if ( $userID eq "" ) {
	exec("../index.cgi");
}

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);
my $Tags = get_tags($DBHandle, $userID);

print $cgi->header();

print $cgi->start_html(-title=>'SharingTree - United Way of Midland County', -BGCOLOR=>'white');

print $cgi->table({-border=>1,width=>'100%'},
	$cgi->Tr({-align=>left},
	[
		$cgi->td({-valign=>'top',-align=>'center',-width=>'5%'},
		[
			"<a href=\"/cgi-bin/main.cgi?B=".$userID."\">Main Page</a>",
			"<a href=\"/index.cgi\">Logout</a>"
		]
		)
	]
	)
);

print $cgi->br();
print $cgi->hr();
print $cgi->br();

#http://www.perlmonks.org/?node_id=330823
#print_table_header($cgi);

my $C_Agency = "";

while ($row = $Tags->fetchrow_hashref) {
	my $Client_ID			= $$row{Client_ID};
	my $Last_Name			= $$row{Last_Name};
	my $First_Name			= $$row{First_Name};
	my $Middle_Name			= $$row{Middle_Name};
	my $Suffix_Name			= $$row{Suffix_Name};
	my $DOB				= $$row{DOB};
	my $Phone_Number		= $$row{Phone_Number};
	my $Gift_Description		= $$row{Gift_Description};
	my $Price			= $$row{Price};
	my $Category			= $$row{Category};
	my $Agency_Name			= $$row{Agency_Name};
	my $Tag				= $$row{Tag};

	#if ( ($C_Agency ne $Agency_Name) or ($C_Agency ne "") ) {
	if ( ($C_Agency ne $Agency_Name) ) {
		print $cgi->br();
		print $cgi->hr();
		print $cgi->br();
		$C_Agency = $Agency_Name;

		print $cgi->table({-border=>1,width=>'100%'},
			$cgi->Tr({-align=>'left'},
			[
				$cgi->td({-valign=>'top',-align=>'center'},
				[
					$C_Agency
				]
				)
			]
			)
		);

		print_table_header($cgi);
	}

	print $cgi->table({-border=>1,width=>'100%'},
		$cgi->Tr({-align=>left},
		[
			$cgi->td({-valign=>'top',-width=>'7%'},
			[
				"<a href=\"/cgi-bin/edit.cgi?id=".$$row{Client_ID}."&userID=".$userID."\">".$$row{Client_ID}."</a>",
				$$row{Agency_Name},
				$$row{Category},
				$$row{Gift_Description},
				$$row{Price},
				$$row{First_Name},
				$$row{Middle_Name},
				$$row{Last_Name},
				$$row{Suffix_Name},
				$$row{DOB},
				$$row{Phone_Number},
				$$row{Tag}
			]
			)
		]
		)
	)
}

print $cgi->br();
print $cgi->font({face=>'arial',size=>'-3'},
	("Copyright United Way Midland County, All Rights Reserved.")
);

print $cgi->end_html;

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

sub get_tags {
	my $DBHandle  = shift;
	my $userID = shift;

	my $sql_statement = "Select Client_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, Phone_Number, Gift_Description, Price, Tag, Category, Agency_Name from Client";

	if ( $userID ne "uwadmin" ) {
		my $Agency_Long_Name = get_agency_name($DBHandle, $userID);
		$sql_statement = $sql_statement . " where Agency_Name = '" . $Agency_Long_Name . "'";
	}

	$sql_statement = $sql_statement . " order by Agency_Name, Tag, Client_ID ASC";

	my $sth = $DBHandle->prepare($sql_statement);
	$sth->execute();

	return $sth;
}

sub print_table_header {
	my $cgi = shift;

	print $cgi->table({-border=>1,width=>'100%'},
		$cgi->th({-valign=>'top',-width=>'7%'},
		[
			"Client ID",
			"Agency Name",
			"Category",
			"Gift Description",
			"Price",
			"First",
			"MI",
			"Last",
			"Suffix",
			"DOB",
			"Phone",
			"Tag"
		]
		)
	);
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
