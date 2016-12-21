#!/usr/local/bin/perl

#use strict;
use Time::Local;
use Getopt::Std;
use POSIX;
use DBI;
#use CGI qw(standard -debug);
use CGI 'standard';

getopts('B:');

my $mysql_db     = "sharingt_sharingtreeDB";
my $mysql_server = "localhost";
my $mysql_user   = "sharingtree";
my $mysql_pass   = "N.y\$;9qk\@DHr";
#
my $graphics     = "../graphics";

my $cgi = new CGI;
my $userID = $cgi->param('B');

if ( $userID eq "" ) {
	$userID = $opt_B;
}

if ( $userID eq "" ) {
    exec("../index.cgi");
}

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);
my $Agency_Name = get_agency_name($DBHandle, $userID);

print $cgi->header();

print $cgi->start_html(-title=>'SharingTree - United Way of Midland County', -BGCOLOR=>'white');

print $cgi->table({-cellpadding=>2,-cellspacing=>2,-border=>5,width=>'100%'},
	$cgi->th(["Welcome ".$Agency_Name]),
#	$cgi->Tr({-align=>CENTER,-colspan=>1},
#	[
#		$cgi->td({-valign=>'top'},
#			$cgi->start_form(-method=>'post',-action=>'form_client.cgi'),
#				$cgi->input({-type=>'hidden',-name=>'userID',-value=>$userID}),
#				$cgi->submit({-name=>'Client Enter Tags'}),
#			$cgi->end_form()
#		)
#	]
#	),
	$cgi->Tr({-align=>CENTER,-colspan=>1},
	[
		$cgi->td({-valign=>'top'},
			$cgi->start_form(-method=>'post',-action=>'form_agency.cgi'),
				$cgi->input({-type=>'hidden',-name=>'userID',-value=>$userID}),
				$cgi->submit({-name=>'Agency Enter Tags'}),
			$cgi->end_form()
		)
	]
	),
	$cgi->Tr({-align=>CENTER,-colspan=>1},
	[
		$cgi->td({-valign=>'top'},
			$cgi->start_form(-method=>'post',-action=>'display.cgi'),
				$cgi->input({-type=>'hidden',-name=>'userID',-value=>$userID}),
				$cgi->submit({-name=>'Display/Edit Tags'}),
			$cgi->end_form()
		)
	]
	),
	$cgi->Tr({-align=>CENTER,-colspan=>1},
	[
		$cgi->td({-valign=>'top'},
			$cgi->start_form(-method=>'post',-action=>'dedup.cgi'),
				$cgi->input({-type=>'hidden',-name=>'userID',-value=>$userID}),
				$cgi->submit({-name=>'Deduplicate Tags (coming soon)'}),
			$cgi->end_form()
		)
	]
	)
);

print $cgi->br();

if ( $userID eq "uwadmin" ) {
	print $cgi->table({-cellpadding=>2,-cellspacing=>2,-border=>5,width=>'100%'},
		$cgi->th(["United Way Admin Functions"]),
		$cgi->Tr({-align=>CENTER,-colspan=>1},
		[
			$cgi->td({-valign=>'top'},["Export Full Dump"])
		]
		),
		$cgi->Tr({-align=>CENTER,-colspan=>1},
		[
			$cgi->td({-valign=>'top'},["Export Collection Sheets"])
		]
		),
		$cgi->Tr({-align=>CENTER,-colspan=>1},
		[
			$cgi->td({-valign=>'top'},["Additional Report..."])
		]
		),
		$cgi->Tr({-align=>CENTER,-colspan=>1},
		[
			$cgi->td({-valign=>'top'},["Add Agency Password"])
		]
		),
		$cgi->Tr({-align=>CENTER,-colspan=>1},
		[
			$cgi->td({-valign=>'top'},
				$cgi->start_form(-method=>'post',-action=>'change_password.cgi'),
					$cgi->input({-type=>'hidden',-name=>'userID',-value=>$userID}),
					$cgi->submit({-name=>'Reset an agency passowrd'}),
				$cgi->end_form()
			)
		]
		)
	);
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
