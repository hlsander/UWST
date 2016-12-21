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
my $ID = $cgi->param('id');
my $userID = $cgi->param('userID');

if ( $ID eq "" ) {
    exec("../index.cgi");
}

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);
my $Tag = get_tag($DBHandle, $ID);

print $cgi->header();

print $cgi->start_html(-title=>'SharingTree - United Way of Midland County', -BGCOLOR=>'white');

#http://www.perlmonks.org/?node_id=330823
#while($row = $Tag->fetchrow_hashref) {
#	for (keys %$row) {
#		print "$_ => $$row{$_}\n";
#	}
#}

while ($row = $Tag->fetchrow_hashref) {
	my $Client_ID		= $$row{Client_ID};
	my $Last_Name		= $$row{Last_Name};
	my $First_Name		= $$row{First_Name};
	my $Middle_Name		= $$row{Middle_Name};
	my $Suffix_Name		= $$row{Suffix_Name};
	my $DOB			= $$row{DOB};
	my $Address		= $$row{Address};
	my $City		= $$row{City};
	my $State		= $$row{State};
	my $Zip_Code		= $$row{Zip_Code};
	my $Phone_Number	= $$row{Phone_Number};
	my $Email		= $$row{Email};
	my $Gift_Description	= $$row{Gift_Description};
	my $Gift_Name		= $$row{Gift_Name};
	my $Price		= $$row{Price};
	my $Gift_Name_2		= $$row{Gift_Name_2};
	my $Price_2		= $$row{Price_2};
	my $Pant_Text		= $$row{Pant_Text};
	my $Pant_Size		= $$row{Pant_Size};
	my $Shoes_Text		= $$row{Shoes_Text};
	my $Shoes_Size		= $$row{Shoes_Size};
	my $Shirt_Text		= $$row{Shirt_Text};
	my $Shirt_Size		= $$row{Shirt_Size};
	my $Agency_Name		= $$row{Agency_Name};
	my $Category		= $$row{Category};
	my $Tag			= $$row{Tag};

	if ( $Client_ID eq "" ) {
		$Client_ID = $ID;
	}
#<input type="submit" value="Submit Changes">
	print $cgi->start_form(-method=>'post',-action=>'/cgi-bin/submit_change.cgi'),
		$cgi->input({-name=>'userID',-type=>'hidden',-value=>$userID}),
		$cgi->input({-name=>'Client_ID',-type=>'hidden',-value=>$Client_ID});
		$cgi->input({-name=>Agency_Name,-type=>'hidden',-value=>$$row{Agency_Name}});
	
	print $cgi->table({-border=>1,-width=>'50%'},
		$cgi->Tr({-align=>left},
		[
			$cgi->td({-valign=>'top',-width=>'5%'},["Last Name: ",$cgi->input({-name=>Last_Name,-type=>'text',-value=>$$row{Last_Name}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["First Name: ",$cgi->input({-name=>First_Name,-type=>'text',-value=>$$row{First_Name}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Middle Initial ",$cgi->input({-name=>Middle_Name,-type=>'text',-value=>$$row{Middle_Name}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Suffix: ",$cgi->input({-name=>Suffix_Name,-type=>'text',-value=>$$row{Suffix_Name}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["DOB: ",$cgi->input({-name=>DOB,-type=>'text',-value=>$$row{DOB}})]),

			$cgi->td({-valign=>'top',-width=>'5%'},["Gift Description ",$cgi->textarea({-name=>Gift_Description,-rows=>'5',-columns=>'50',-maxlength=>'200',-value=>$$row{Gift_Description}})]),

			$cgi->td({-valign=>'top',-width=>'5%'},["Price ",$cgi->input({-name=>Price,-type=>'text',-value=>$$row{Price}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Tag: ",$cgi->input({-name=>Tag,-type=>'text',-value=>$$row{Tag}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Category ",$cgi->input({-name=>Category,-type=>'text',-value=>$$row{Category}})]),

			$cgi->td({-valign=>'top',-width=>'5%',-colspan=>"2"},["&nbsp;"]),
			$cgi->td({-valign=>'top',-width=>'5%',-align=>'middle',-colspan=>"2"},["Full Information Below For Future Use"]),
			$cgi->td({-valign=>'top',-width=>'5%',-colspan=>"2"},["&nbsp;"]),

			$cgi->td({-valign=>'top',-width=>'5%'},["Address: ",$cgi->input({-name=>Address,-type=>'text',-value=>$$row{Address}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["City: ",$cgi->input({-name=>City,-type=>'text',-value=>$$row{City}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["State: ",$cgi->input({-name=>State,-type=>'text',-value=>$$row{State}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Zip: ",$cgi->input({-name=>Zip_Code,-type=>'text',-value=>$$row{Zip_Code}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Phone: ",$cgi->input({-name=>Phone_Number,-type=>'text',-value=>$$row{Phone_Number}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Gift 1 ",$cgi->input({-name=>Gift_Name,-type=>'text',-rows=>'5',-columns=>'100',-value=>$$row{Gift_Name}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Gift 1 Price ",$cgi->input({-name=>Price,-type=>'text',-value=>$$row{Price}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Gift 2 ",$cgi->input({-name=>Gift_Name_2,-type=>'text',-value=>$$row{Gift_Name_2}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Gift 2 Price ",$cgi->input({-name=>Price_2,-type=>'text',-value=>$$row{Price_2}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Pants ",$cgi->input({-name=>Pant_Text,-type=>'text',-value=>$$row{Pant_Text}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Pants Info ",$cgi->input({-name=>Pant_Size,-type=>'text',-value=>$$row{Pant_Size}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Shoes ",$cgi->input({-name=>Shoes_Text,-type=>'text',-value=>$$row{Shoes_Text}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Shoes Info ",$cgi->input({-name=>Shoes_Size,-type=>'text',-value=>$$row{Shoes_Size}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Shirt ",$cgi->input({-name=>Shirt_Text,-type=>'text',-value=>$$row{Shirt_Text}})]),
			$cgi->td({-valign=>'top',-width=>'5%'},["Shirt Info ",$cgi->input({-name=>Shirt_Size,-type=>'text',-value=>$$row{Shirt_Size}})])
		])
	),

	
	$cgi->br(),
	
	$cgi->submit({-name=>'Submit Changes'}),
	$cgi->end_form()
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

sub get_tag {
	my $DBHandle  = shift;
	my $ID = shift;

	my $sql_statement = "Select Client_ID, First_Name, Last_Name, Middle_Name, Suffix_Name, DOB, AoCD, Phone_Number, Alt_Phone, Address, Email, City, Township, State, Zip_Code, Gift_Description, Gift_Name, Price, Gift_Name_2, Price_2, Pant_Text, Pant_Size, Shoes_Text, Shoes_Size, Shirt_Text, Shirt_Size, Tag, Category, Agency_Name from Client where Client_ID = $ID";

	my $sth = $DBHandle->prepare($sql_statement);
	$sth->execute();

	return $sth;
}
