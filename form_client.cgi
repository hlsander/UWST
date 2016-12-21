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
                $cgi->td({-valign=>'top',-align=>'center',-width=>'5%'},
                [
                        "<a href=\"/cgi-bin/main.cgi?B=".$userID."\">Main Page</a>",
                        "<a href=\"/index.cgi\">Logout</a>"
                ]
                )
        ]
        )
);

print $cgi->start_form(-method=>'post',-action=>'/cgi-bin/submit_form_client.cgi'),
	$cgi->input({-name=>'userID',-type=>'hidden',-value=>$userID}),
	$cgi->input({-name=>'Entry_Date',-type=>'hidden',-value=>$Date});

print $cgi->table({-border=>0,width=>'100%'},
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'center',-width=>'75%',-colspan=>'3'},
			$cgi->font({face=>'arial',size=>'+4'},("Sharing Tree")),
			$cgi->br(),
			"GIFT REQUEST FORM"
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'3'},
			$cgi->br(),
			"Completion of this form allows the relase of your name, mailling address, phone number, date of birth, and your gift request(s) to the United Way's Sharing Tree program by the organizaiton or school selected below.",
			$cgi->p()
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'center',-width=>'75%',-colspan=>'3'},
			$cgi->popup_menu({	-name=>'agency',
						-values=>['Please Select Agency',
								'',
								'Arc of Midland',
								'Bullock Creek Public Schools',
								'Big Brothers Big Sisters Great Lakes Bay Region',
								'Cancer Services',
								'Community Mental Health - Midland',
								'Coleman Community Schools - Elementary',
								'Coleman Community Schools - High School',
								'Dept. of Human Services - Midland',
								'Disability Network of Mid-Michigan',
								'Early Head Start',
								'Education and Training Connection',
								'Family and Children\'s Services',
								'Lutheran Social Services - Midland',
								'Meridian Public Schools - Sanford Elementary',
								'Meridian Public Schools - Meridian Elementary',
								'Meridian Public Schools - Meridian Junior High',
								'Meridian Public Schools - Meridian High School',
								'Midland Area Homes',
								'Midland Public School - Adams Elementary',
								'Midland Public School - Carpenter Elementary',
								'Midland Public School - Chestnut Hill Elementary',
								'Midland Public School - Eastlawn Elementary',
								'Midland Public School - Jefferson Middle School',
								'Midland Public School - Midland High School',
								'Midland Public School - Northeast Middle School',
								'Midland Public School - Plymouth Elementary',
								'Midland Public School - Siebert Elementary',
								'Midland Public School - Woodcrest Elementary',
								'NEMSCA Head Start - Baptist',
								'NEMSCA Head Start - Coleman',
								'NEMSCA Head Start - Grace',
								'NEMSCA Head Start - MCESA2',
								'NEMSCA Head Start - MCESA3',
								'NEMSCA Head Start - MCESA4',
								'NEMSCA Head Start - Meridian1',
								'NEMSCA Head Start - Meridian2',
								'North Midland Family Center',
								'Personal Assistance Options',
								'Reese Community Living Endeavor',
								'Senior Services',
								'Shelterhouse',
								'West Midland Family Center',
								'Windover High School'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'center',-width=>'75%',-colspan=>'3'},
			$cgi->popup_menu({	-name=>'Tag',
						-values=>['Select Tag Type',
								'Green',
								'Red'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'3'},
			$cgi->br(),
			$cgi->b('Gift Recipient Information:'),
			"Please fill out completely.  This infromation will be kept confidential.",
			$cgi->p()
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%'},
			"First Name: ",
			$cgi->input({-name=>'First_Name',-type=>'text'}),
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%'},
			"M.I.: ",
			$cgi->input({-name=>'Middle_Initial',-type=>'text',-size=>'3'}),
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%'},
			"Last Name: ",
			$cgi->input({-name=>'Last_Name',-type=>'text'}),
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'1'},
			"Phone Number: ",
			$cgi->input({-name=>'Phone_Number',-type=>'text',-size=>'15'}),
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'2'},
			"Alternate Number: ",
			$cgi->input({-name=>'Alt_Number',-type=>'text',-size=>'15'}),
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'100%',-colspan=>'3'},
			"Address: ",
			$cgi->input({-name=>'Address',-type=>'text',-size=>'100'}),

		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%'},
			"City: ",
			$cgi->input({-name=>'City',-type=>'text'}),
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%'},
			"State: ",
			$cgi->input({-name=>'State',-type=>'text',-size=>'2',-value=>'MI'}),
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%'},
			"Zip Code: ",
			$cgi->input({-name=>'Zip',-type=>'text'}),
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'1'},
			"Township: ",
			$cgi->input({-name=>'Township',-type=>'text'}),
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'2'},
			"Date of Birth: ",
			$cgi->input({-name=>'DOB',-type=>'text',-size=>'15',-value=>'MM/DD/YYYY'}),
		),
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'33%',-colspan=>'1'},
			$cgi->popup_menu({	-name=>'Gender',
						-values=>['Select Gender',
								'Male',
								'Female'
							]
					})
		),
		$cgi->td({-valign=>'top',-align=>'left',-width=>'25%',-colspan=>'2'},
			"Age on Christmas Day: ",
			$cgi->input({-name=>'AoCD',-type=>'text',-size=>'2'}),
		),
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'3'},
			$cgi->br(),
			$cgi->b('Sharing Tree Tag Information:'),
			"Please fill out completely.",
			$cgi->p()
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'3'},
			$cgi->br(),
			$cgi->b('Clothing Sizes:'),
			"Provide number sizes and letter size. (Example: 14, Large Women's)",
			$cgi->p()
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'100%',-colspan=>'3'},
			"Pants: ",
			$cgi->input({-name=>'Pant_Text',-type=>'text'}),
			$cgi->popup_menu({	-name=>'Pant_Size',
						-values=>['Select Size',
								'Infants',
								'Toddler',
								'Boys',
								'Girls',
								'Juniors',
								'Misses',
								'Womens',
								'Mens',
								'Slim',
								'Regular',
								'Husky'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'100%',-colspan=>'3'},
			"Shoes: ",
			$cgi->input({-name=>'Shoe_Text',-type=>'text'}),
			$cgi->popup_menu({	-name=>'Shoe_Size',
						-values=>['Select Size',
								'Infants',
								'Toddler',
								'Boys',
								'Girls',
								'Womens',
								'Mens'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'100%',-colspan=>'3'},
			"Shirt: ",
			$cgi->input({-name=>'Shirt_Text',-type=>'text'}),
			$cgi->popup_menu({	-name=>'Shirt_Size',
						-values=>['Select Size',
								'Infants',
								'Toddler',
								'Boys',
								'Girls',
								'Juniors',
								'Misses',
								'Womens',
								'Mens'
							]
					})
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'3'},
			$cgi->b('First Choice Gift:'),
			"(Description should include color, style, name of toy, etc.) Maximum gift value of \$30.",
			$cgi->br(),
			$cgi->textarea({-name=>'Gift_One',-rows=>'5',-columns=>'100'}),
			$cgi->br(),
			"Gift Price: (don't include the \$ symbol)",
			$cgi->input({-name=>'Gift_One_Price',-type=>'text',-value=>''}),
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'left',-width=>'75%',-colspan=>'3'},
			$cgi->b('Second Choice Gift:'),
			"(Description should include color, style, name of toy, etc.) Maximum gift value of \$30.",
			$cgi->br(),
			$cgi->textarea({-name=>'Gift_Two',-rows=>'5',-columns=>'100'}),
			$cgi->br(),
			"Gift Price: (don't include the \$ symbol)",
			$cgi->input({-name=>'Gift_Two_Price',-type=>'text',-value=>''}),
		)
	),
	$cgi->Tr({-align=>left},
		$cgi->td({-valign=>'top',-align=>'center',-width=>'75%',-colspan=>'3'},
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
