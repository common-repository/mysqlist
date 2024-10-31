<?php
/*
Usage:
Diese Datei ist für fast alle MySQL Befehle zuständig.
Functions:
mysqlist_*
addList: Fügt eine neue Liste hinzu
addElement: Fügt ein neues Element hinzu
readList: Liest die Liste für [mysqlist] aus
checkdbHook: Überprüft täglich die Datenbank nach alten Einträgen
deleteList: Löscht eine Liste
delete: Löscht ein Listenelement
deleteX: Löscht alles/leert die Datenbanktabellen

*/
function mysqlist_addList ($listname) {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'mysqlist_lists';
    $mylink = $wpdb->get_row( "SELECT MAX(num) FROM `{$table_name}`", ARRAY_A );
    $number = $mylink["MAX(num)"];
    $number++;
    
    $wpdb->insert($table_name, array('name' => $listname, 'num' => $number), array("%s", "%s"));
    return true;
}
function mysqlist_addElement ($eintrag, $url, $date, $list) {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'mysqlist_list';
    
    $date = date("Y-m-d", strtotime($date));
    $added = date("Y-m-d");
    $wpdb->insert( $table_name, array('eintrag' => $eintrag, 'url' => $url, 'date' => $date, 'lista' => $list, 'added' => $added), array("%s", "%s", "%s", "%s", "%s") );
    return true;
}


function mysqlist_readList () {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
	$table_name1 = $wpdb->prefix . 'mysqlist_lists';
	$table_name2 = $wpdb->prefix . 'mysqlist_list';
    $settingenabled = get_option( 'mysqlist_enable_turquoise', false );
    $settingad = get_option( 'mysqlist_enable_ad', false );

    $results = $wpdb->get_results("SELECT `name` FROM `{$table_name1}` ORDER BY `num`");
    $return = "";
    $threedays = date("Y-m-d", time() - (3 * 24 * 60 * 60));
    foreach ($results as $result) {
        $return .= "<h2>{$result->name}</h2>";
        $list = $wpdb->get_results("SELECT * FROM `{$table_name2}` WHERE `lista` = '{$result->name}' ORDER BY `date`");
        foreach ($list as $listitem) {
            if ($settingenabled == TRUE) {
                if ($listitem->added > $threedays) {
                 $color = '<span style="color: #33cccc;">';   
                } else {
                 $color = '<span>';   
                }
            } else {
             $color = '<span>';   
            }
            $date = $listitem->date;
            $listitem->eintrag = str_replace('<span style="color: #33cccc;">', "", $listitem->eintrag);
            if (empty($listitem->url)) {
             $url1 = "";
            $url2 = "";
            } else {
             $url1 = "<a href='".esc_url($listitem->url)."' target='_blank'>";   
             $url2 = "</a>";
            }
            if ($date == "1970-01-01") {
            $return .= "{$url1}{$color}".esc_html($listitem->eintrag)."{$url2}</span><br>";   
            } else {
            $date = date("d.m.Y", strtotime($date));
            $date = esc_html($date);
            $return .= "{$url1}{$color}".esc_html($listitem->eintrag)."({$date}){$url2}</span><br>";   
            }
        }
    }
    if (empty($return)) {
     $return = "Diese Liste hat zur Zeit keinen Inhalt. Bitte schaue später noch einmal vorbei.<br><br>";   
    }
    if ($settingad) {
    $return .= "<h6><small>Generated with MySQList by <a href='http://bennetthollstein.de'>Bennett Hollstein</a></small></h6><br>";
    }
    return $return;
}

function mysqlist_checkdbHook(  ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mysqlist_list';
    $wpdb->query("DELETE FROM `{$table_name}` WHERE `date` < Curdate() && `date` != '1970-01-01'");
}


function mysqlist_deleteList ($listname) {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
	$table_name1 = $wpdb->prefix . 'mysqlist_lists';
	$table_name2 = $wpdb->prefix . 'mysqlist_list';
    $wpdb->query($wpdb->prepare("DELETE FROM `{$table_name1}` WHERE name = '%s'", $listname));
    $wpdb->query($wpdb->prepare("DELETE FROM `{$table_name2}` WHERE lista = '%s'",$listname));
    return true;
}
function mysqlist_delete ($eintrag, $list) {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
	$table_name1 = $wpdb->prefix . 'mysqlist_list';
    $wpdb->query($wpdb->prepare("DELETE FROM `{$table_name1}` WHERE `eintrag` = '%s' && `lista` = '%s'", array($eintrag, $list)));
    return true;
}
function mysqlist_deleteX () {
    global $wpdb;
	$table_name1 = $wpdb->prefix . 'mysqlist_lists';
	$table_name2 = $wpdb->prefix . 'mysqlist_list';
    $wpdb->query("DELETE FROM `{$table_name1}`");
    $wpdb->query("DELETE FROM `{$table_name2}`");
}
?>