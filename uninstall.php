<?php
// Nur deinstalliren, wenn dieSeite über das Administratormenü aufgerufen wurde
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    echo "Bitte deinstalliere das Plugin über das Administratormenü.";
    exit();
}
$option = get_option( 'mysqlist_full_deinstallation' ,false);

// Datenbanken löschen
if ($option !== TRUE) {
global $wpdb;
$table_name1 = $wpdb->prefix . 'mysqlist_list';
$table_name2 = $wpdb->prefix . 'mysqlist_lists';
$wpdb->query("DROP TABLE IF EXISTS `{$table_name1}`");
$wpdb->query("DROP TABLE IF EXISTS `{$table_name2}`");
}
//Optionen löschen
delete_option("mysqlist_full_deinstallation");
delete_option("mysqlist_enable_ad");
delete_option("mysqlist_enable_turquoise");
delete_site_option("mysqlist_full_deinstallation");  
delete_site_option("mysqlist_enable_ad");  
delete_site_option("mysqlist_enable_turquoise");  

return true;