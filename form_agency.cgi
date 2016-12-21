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

print $cgi->header();

print $cgi->start_html(-title=>'SharingTree - United Way of Midland County', -BGCOLOR=>'white');

my $Date = localtime(time);

print $cgi->table({-border=>1,width=>'100%'},
        $cgi->Tr({-align=>left},
        [
                $cgi->td({-valign=>'top',-align=>'middle',-width=>'5%'},
                [
                        "<a href=\"/cgi-bin/main.cgi?B=".$userID."\">Main Page</a>",
                        "<a href=\"/index.cgi\">Logout</a>"
                ]
                )
        ]
        )
);

print $cgi->start_form(-method=>'post',-action=>'/cgi-bin/submit_form_agency.cgi'),
	$cgi->input({-name=>'userID',-type=>'hidden',-value=>$userID}),
	$cgi->input({-name=>'Entry_Date',-type=>'hidden',-value=>$Date});

print $cgi->table({-border=>0,width=>'100%'},
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'2'},
			$cgi->font({face=>'arial',size=>'+4'},("Sharing Tree")),
			$cgi->br(),
			"Agency Entry Form"
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'2'},
			$cgi->popup_menu({	-name=>'Tag',
						-values=>['Select Tag Type',
								'Green',
								'Red'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'2'},
			$cgi->popup_menu({	-name=>'Category',
						-values=>['Please Select Category',
								'',
								' - Green - ',
								'',
								'Youth 0-11',
								'Teen 12-17',
								'Male 18+',
								'Female 18+',
								'',
								' - Red - ',
								'',
								'Baby and New Mothers-clothes, diapers, bottles, nursing supplies',
								'Books',
								'Cleaning- mops, brooms, buckets, detergents',
								'Clothing-shoes, hats, gloves',
								'Energy-Consumers, propane',
								'Entertainment-movie tickets, restaurants',
								'Fuel-gasoline',
								'Grocery-food or gift cards for food',
								'Household-small appliances, dishes, bakeware, pots and pans',
								'Laundry-laundromat, baskets, laundry detergent',
								'Linens-bedding, towels, blankets',
								'Miscellaneous',
								'Paper Products-toilet paper, trash bags, paper towels',
								'Personal-toiletries, blow dryers',
								'Toys and Games-MP3, DVD, CD, cards',
								'Transportation-Dial-A-Ride, County Connection tickets, taxi'
							]
					})
		)
	),
        $cgi->Tr({-align=>left},
                $cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'2'},
                        $cgi->b('Tag Description:'),
                        $cgi->br(),
                        $cgi->textarea({-name=>'Gift_Description',-rows=>'5',-columns=>'50',-maxlength=>'200'}),
                        $cgi->br(),
                        "Gift Price: (don't include the \$ symbol)",
                        $cgi->input({-name=>'Gift_One_Price',-type=>'text',-value=>''}),
                )
        ),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'2'},
			$cgi->br(),
			$cgi->b('Gift Recipient Information:'),
			$cgi->br(),
			"(only needed for green tag items)",
			$cgi->p()
		)
	),
	$cgi->Tr({-align=>left},
	[
		$cgi->td({-valign=>'top',-align=>'left'}, "First Name: ", $cgi->input({-name=>'First_Name',-type=>'text'}),),
                $cgi->td({-valign=>'top',-align=>'left'}, "M.I.: ", $cgi->input({-name=>'Middle_Initial',-type=>'text',-size=>'3'}),),
                $cgi->td({-valign=>'top',-align=>'left'}, "Last Name: ", $cgi->input({-name=>'Last_Name',-type=>'text'}),),
                $cgi->td({-valign=>'top',-align=>'left'}, "Suffix (Jr, Sr, III): ", $cgi->input({-name=>'Suffix_Name',-type=>'text'}),)
	]
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'1'},
			"Date Of Birth: ",
			$cgi->popup_menu({	-name=>'DOB_M',
						-values=>['Month',
								'01',
								'02',
								'03',
								'04',
								'05',
								'06',
								'07',
								'08',
								'09',
								'10',
								'11',
								'12'
							]
					}),
			$cgi->popup_menu({	-name=>'DOB_D',
						-values=>['Day',
								'01',
								'02',
								'03',
								'04',
								'05',
								'06',
								'07',
								'08',
								'09',
								'10',
								'11',
								'12',
								'13',
								'14',
								'15',
								'16',
								'17',
								'18',
								'19',
								'20',
								'21',
								'22',
								'23',
								'24',
								'25',
								'26',
								'27',
								'28',
								'29',
								'30',
								'31'
							]
					}),
			$cgi->popup_menu({	-name=>'DOB_Y',
						-values=>['Year',
								'1920',
								'1921',
								'1922',
								'1923',
								'1924',
								'1925',
								'1926',
								'1927',
								'1928',
								'1929',
								'1930',
								'1931',
								'1932',
								'1933',
								'1934',
								'1935',
								'1936',
								'1937',
								'1938',
								'1939',
								'1940',
								'1941',
								'1942',
								'1943',
								'1944',
								'1945',
								'1946',
								'1947',
								'1948',
								'1949',
								'1950',
								'1951',
								'1952',
								'1953',
								'1954',
								'1955',
								'1956',
								'1957',
								'1958',
								'1959',
								'1960',
								'1961',
								'1962',
								'1963',
								'1964',
								'1965',
								'1966',
								'1967',
								'1968',
								'1969',
								'1970',
								'1971',
								'1972',
								'1973',
								'1974',
								'1975',
								'1976',
								'1977',
								'1978',
								'1979',
								'1980',
								'1981',
								'1982',
								'1983',
								'1984',
								'1985',
								'1986',
								'1987',
								'1988',
								'1989',
								'1990',
								'1991',
								'1992',
								'1993',
								'1994',
								'1995',
								'1996',
								'1997',
								'1998',
								'1999',
								'2000',
								'2001',
								'2002',
								'2003',
								'2004',
								'2005',
								'2006',
								'2007',
								'2008',
								'2009',
								'2010',
								'2011',
								'2012',
								'2013',
								'2014',
								'2015'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'1'},
			"Phone Number: ",
			$cgi->input({-name=>'Phone_Number',-type=>'text',-size=>'15'}),
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'2'},
			$cgi->submit({-name=>'Submit Form'}),
			$cgi->end_form()
		)
	),
);


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
