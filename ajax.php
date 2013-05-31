<?php
define("STREAMS", 1);

session_start();
$sessid = session_id();

require_once("lib/auth.php");
require_once("lib/Playlist.php");
require_once("lib/config.php");
require_once("lib/ws-php-library.php");
require_once("lib/streams.lib.php");

$playlistObj = "{$auth->userDir}/playlist.obj";
if (!file_exists($playlistObj)) {
    $pl = new Playlist();
} else {
    $pl = unserialize($playlistObj);
}

if ($_GET['action'] == "createPlaylistJs" && file_exists($defaultMp3Dir . '/' . $_GET['dir']) 
        && is_dir($defaultMp3Dir . '/' . $_GET['dir'])) {
    $playlist = buildPlaylistFromDir($_GET['dir']);

    $pl->currentPlaylist = $playlist;
    file_put_contents($playlistObj, serialize($pl));

    $html = buildPlayerHtml($playlist, $_GET['dir']);

    ob_start();
    ob_implicit_flush(0);
    print($html);
    print_gzipped_page();
    die();
} else if ($_GET['action'] == "openDir") {
    $pageContent = openTheDir($_GET['dir']);

    ob_start();
    ob_implicit_flush(0);
    print($pageContent);
    print_gzipped_page();
    die();
} else if ($_GET['action'] == "search") {
    $pageContent = search($_GET['q']);

    ob_start();
    ob_implicit_flush(0);
    print($pageContent);
    print_gzipped_page();
    die();
}
