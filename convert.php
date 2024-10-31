<?php
/*
Usage:
Diese Datei ist für die Umwandlung eines HTML Codes ins MySQList Format zuständig.
Functions:
mysqlist_*
getStringBetween: Meldet den Stringteil zwischen $from und $to zurück
replace: Erstetzt unnötige HTML Teile durch ""
split: Teilt den String an der ) Stelle
converttb: Konvertiert den HTML Code in das MySQList Format
*/
function mysqlist_getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}

function myslqist_replace($text) {
    $replace = array('<span style="color: #33cccc;">','<span style="color: #000000;">', '</span>','<li>','</li>','target="_blank" rel="nofollow"',"FB", "Fb", "Blogger", 'style="color: #000000;" ', '"color: #000000;\"', '</a>', 'ca. ', '<span style="color: #33cccc;">', '<a style= href=');
    $text2 = str_replace($replace, "", $text);
    return $text2;
}

function mysqlist_split($text) {
    $neu = explode(")", $text);
    return $neu;
}
function mysqlist_converttb ($text, $liste) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mysqlist_list';
    $text = myslqist_replace($text);
    $neu = mysqlist_split($text);
$replace = array('<a style= href="', '<span style="color: #33cccc;">', '<span style="color: #000000;">', '<li>', 'style="color: #33cccc; "', ' target="_blank" rel="nofollow"', '<a style="color: #33cccc;" href=', '<span style="color: #33cccc;">');  
foreach ($neu as $texxt) {
 $texxt = $texxt.')';
 $texxt = str_replace($replace, '', $texxt);
 $link = mysqlist_getStringBetween($texxt, '"', '"');
 $link = substr($link, 0, -1);
 $eintrag = mysqlist_getStringBetween($texxt, '">', '(');
 $enddate = mysqlist_getStringBetween($texxt, '(', ')');
 $enddate = str_replace("/", "", $enddate);
 $enddate = str_replace("//", "", $enddate);
 $date = date("Y-m-d", strtotime($enddate));
 $added = date("Y-m-d", time() - (7 * 24 * 60 * 60));
 $wpdb->insert($table_name, array('eintrag' => $eintrag, 'url' => $link, 'date' => $date, 'lista' => $liste, 'added' => $added), array("%s", "%s", "%s", "%s", "%s"));
}
}
?>