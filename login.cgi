#!/usr/local/bin/perl

use strict;
use Time::Local;
use POSIX;
use DBI;
use CGI;

my $mysql_db     = "sharingt_sharingtreeDB";
my $mysql_server = "localhost";
my $mysql_user   = "sharingtree";
my $mysql_pass   = "N.y\$;9qk\@DHr";
#
my $graphics     = "../graphics";

# web interface
my $cgi = new CGI;
my $web_login = $cgi->param('login');
my $web_pass  = $cgi->param('password');

my $DBHandle = database_connect($mysql_user, $mysql_pass, $mysql_db, $mysql_server);
my $results = get_login_data($DBHandle, $web_login);

my $Agency_Name = "";
my $userID = "";
my $password = "";

#print "\n\tDEBUG\n";
#print "web_login: ".$web_login."\n";
#print "web_pass ".$web_pass."\n";
#print "results: ".$results."\n";

my $elements = "";
while ($elements = $results->fetchrow_hashref) {
#    print "elements ".$elements."\n";

    $Agency_Name = $$elements{'Agency_Name'};
    $userID = $$elements{'userID'};
    $password = $$elements{'password'};
    $Agency_Name = $$elements{'Agency_Name'};

#    print "Agency_Name ".$Agency_Name."\n";
#    print "userID ".$userID."\n";
#    print "password ".$password."\n";
}
my @chars=split//, $password;
my $salt=$chars[0].$chars[1];

#print "\tDEBUG\n";

if ( ( $userID ne "" ) and ( crypt($web_pass, $salt) eq $password ) ) {
    # success
    main_screen($userID);
} else {
    #failure
    login_screen();
}
exit (0);

#######################################################################################

sub database_connect {
    my $mysql_user   = shift;
    my $mysql_pass   = shift;
    my $mysql_db     = shift;
    my $mysql_server = shift;

    my $DBHandle = DBI->connect ("DBI:mysql:database=$mysql_db;host=$mysql_server", $mysql_user, $mysql_pass, {'RaiseError' => 1});

    return $DBHandle;
}

sub get_login_data {
  my $DBHandle  = shift;
  my $web_login = shift;

  my $sql_statement = "select * from Account where userID = '$web_login'";

  my $sth = $DBHandle->prepare($sql_statement);
  $sth->execute();

  return $sth;
}

sub main_screen {
    my $userID = shift;
    #print $cgi->header();
    #print $cgi->font({face=>'arial',size=>'+3'},("foo: $userID"));
    exec("./main.cgi -B $userID");
}

sub login_screen {
    exec("../index.cgi");
}
