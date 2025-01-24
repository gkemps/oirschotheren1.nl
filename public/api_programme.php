<?php
require_once "functions/layout.inc.php";

$dry_run = true;
if ($_GET['dry_run'] == 'false') {
    $dry_run = false;
}

$db = new DB();

$all_matches = [];

$next_page = true;
$page = 1;
while ($next_page) {
    $past_matches_url = "https://publicaties.hockeyweerelt.nl/mc/teams/N5781/matches/official?&competition_id=N11&show_all=1&page={$page}";

    $past_matches = json_decode(file_get_contents($past_matches_url), true);

    foreach ($past_matches['data'] as $match) {
        $all_matches[$match['id']] = $match;
    }

    $next_page = $past_matches['meta']['current_page'] != $past_matches['meta']['last_page'];
    $page++;
}

$next_page = true;
$page = 1;
while ($next_page) {
    $future_matches_url = "https://publicaties.hockeyweerelt.nl/mc/teams/N5781/matches/upcoming?&show_all=1&page={$page}";

    $future_matches = json_decode(file_get_contents($future_matches_url), true);

    foreach ($future_matches['data'] as $match) {
        $all_matches[$match['id']] = $match;
    }

    $next_page = $future_matches['meta']['current_page'] != $future_matches['meta']['last_page'];
    $page++;
}

ksort($all_matches);

$i = 1;
$team = new Team();

# loop through the matches and save to database
foreach ($all_matches as $id => $match) {

    // Create a DateTime object in UTC timezone
    $dateTimeUTC = new DateTime($match['datetime']);

    // Create a DateTimeZone object for Europe/Amsterdam
    $amsterdamTimeZone = new DateTimeZone('Europe/Amsterdam');

    // Change the timezone of the DateTime object to Europe/Amsterdam
    $dateTimeUTC->setTimezone($amsterdamTimeZone);

    // Format the DateTime object to the desired output string
    $formattedDate = $dateTimeUTC->format('Y-m-d');

    $home_team_name = str_replace(" H1", "", $match['home_team']['name']);
    $away_team_name = str_replace(" H1", "", $match['away_team']['name']);

    $home_team = $team->findTeam($home_team_name);
    $away_team = $team->findTeam($away_team_name);

    print $home_team->toString() . " - " . $away_team->toString();

    if (isset($match['home_score'])) {
        print " (" . $match['home_score'] . " - " . $match['away_score'] . ")";
    }

    print " [" . $formattedDate . " (" . ceil($i / 6) . ")] <br />";

    if (isset($match['home_score'])) {
        $sql = "INSERT INTO heren1.programma (Datum, Thuis, Uit, Scorethuis, Scoreuit, Gespeeld, Speelronde) VALUES ('" . $formattedDate . "', " . $home_team->getId() . ", " . $away_team->getId() . ", " . $match['home_score'] . ", " . $match['away_score'] . ", 'ja', " . ceil($i / 6) . ")";
    } else {
        $sql = "INSERT INTO heren1.programma (Datum, Thuis, Uit, Gespeeld, Speelronde) VALUES ('" . $formattedDate . "', " . $home_team->getId() . ", " . $away_team->getId() . ", 'nee', " . ceil($i / 6) . ")";
    }

    if (!$dry_run) {
        $db->query($sql);
    } else {
        print $sql . "<br />";
    }

    $i++;
}