<?php
/*
Usage:
Diese Datei stellt grundlegende Funktionen bereit.
Functions:
mysqlist_*
create_db1: Erstellt die Datenbank für alle Listeneinträge
create_db2: Erstellt die Datenbank für alle Listenabschnitte
add_custom_menu: Erstellt den Menüpunkt "MySQList" im Admin Menü
loeschenBest: Erstellt das Formular, das zum Bestätigen des Leerens der Datenbank benötigt wird
showConvertTb: Zeigt das Formular für die Umwandlung von HTML Code
adminMenu: Zeigt die Seite im Admin Menü
shortcodes: Erstellt den Shortcode [mysqlist]
settings: Erstellt die Einstellungen und den Einstellungsabteil
settingSection: Kommentar für den Einstellungsabteil (bisher leer)
updateSettingTurquoise: Zeigt die Checkbox an, um Einzustellen ob neue Einstellungen Türkis angezeigt werden
updateSettingAdvertise: Zeigt die Checkbox an, um Einzustellen ob die Werbung "Generiert durch MySQList..." angezeigt wird
checkdb: Erstellt den Cronjob zur Überprüfung der Datenbank nach alten Einträgen
activation: Funktion, die bei der Aktivierung ausgefügrt wird
deactivation:Funktion, die bei der Deaktivierung ausgefügrt wird
*/

function create_db1() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'mysqlist_list';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`eintrag` TEXT NOT NULL ,
        `url` TEXT NOT NULL ,
        `date` DATE NOT NULL ,
        `lista` TEXT NOT NULL, 
        `added` DATE NOT NULL
	) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    
}

function create_db2() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'mysqlist_lists';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`name` TEXT NOT NULL ,
        `num` INT NOT NULL
	) $charset_collate;";
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function mysqlist_add_custom_menu() {
    //add an item to the menu
    add_menu_page (
        'MySQList',
        'MySQList',
        'manage_options',
        'mysqlist',
        'mysqlist_admin_menu',
        '',
        '23.56'
    );
}

function mysqlist_loeschenBest () {
    $path = plugins_url();
 echo "<h3><font color='red'>Willst du wirklich ALLE LISTENABSCHNITTE UND EINTRÄGE LÖSCHEN?</font></h3><br>";
    echo "Bitte bestätige diesen Schritt durch das Ausfüllen des Captchas:<br>";
 echo "<img src='".esc_url($path."/mysqlist/icons/captcha.jpg")."'><br>";
    echo "<form action='".esc_url( $_SERVER['REQUEST_URI'] )."' method='post'>";
 echo  "<input type='text' name='captcha'><input type='submit' value='Leeren' name='deleteX'>";
    echo "</form>";
    
}

function mysqlist_showConvertTb () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mysqlist_lists';
    $lists = $wpdb->get_results("SELECT * FROM `$table_name` ORDER BY `num`");
 ?>

<h3>Bitte gebe den HTML EINER Liste(nicht von der ganzen Seite!)</h3><br>
<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post">
<textarea name="html" placeholder="Code" rows="10" cols="50"></textarea><br>
    Speichern in Liste:<br>
<select name="liste">
                <?php 
                foreach ($lists as $list) {
                    echo '<option value="'.esc_html($list->name).'">'.esc_html($list->name).'</option>';
                }
                if (empty($lists)) {
                    echo "<option>Keine Listenabschnitte gefunden</option>";
                }
                ?>
            </select><br>
    <input type="submit" name="converttb">
</form>
<?php
}

function mysqlist_admin_menu() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mysqlist_lists';
    $table_name2 = $wpdb->prefix . 'mysqlist_list';
    
    
    
    if ( isset( $_POST['submitted1'] ) ) {
        //Neue Liste
        $_POST["name"] = sanitize_text_field($_POST["name"]);
        $issetlist = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$table_name}` WHERE name = '%s'", $_POST['name']));
        $numm = "";
        foreach ($issetlist as $issetl) {
         $numm .= $issetl->num;   
        }
        if (!empty($numm)) {
            echo "<div id='warning' style='background-color:red; padding: 25px 50px;'>Es gibt bereits einen Abschnitt mit diesem Namen.</div>";  
        } else {
        
        if (@mysqlist_addList($_POST["name"])) {
         echo "<div id='success' style='background-color:lightgreen; padding: 25px 50px;'>Listenabschnitt erfolgreich erstellt. </div>";  
        } else {
         echo "<div id='warning' style='background-color:red; padding: 25px 50px;'>Es gab einen unbekannten Fehler beim Erstellen.</div>";   
        }
        }
        }
        if ( isset( $_POST['submitted2'] ) ) {
        //Neuer Listeneintrag
        if (@mysqlist_addElement($_POST["eintrag"], $_POST["url"], $_POST["date"], $_POST["list"])) {
         echo "<div id='success' style='background-color:lightgreen; padding: 25px 50px;'>Listenelement erfolgreich hinzugefügt. </div>";
        } else {
         echo "<div id='warning' style='background-color:red; padding: 25px 50px;'>Es gab einen unbekannten Fehler beim Erstellen.</div>";   
        }
        }
        
        if ( isset( $_POST['submitted3'] ) ) {
            if ($_POST["list"] == $_POST["list2"]) {
             if (@mysqlist_deleteList($_POST["list"])) {
                 echo "<div id='success' style='background-color:lightgreen; padding: 25px 50px;'>Listenelement erfolgreich gelöscht. </div>";
             } else {
                 echo "<div id='warning' style='background-color:red; padding: 25px 50px;'>Es gab einen unbekannten Fehler beim Erstellen.</div>";   
             }
             } else {
                 echo "<div id='warning' style='background-color:red; padding: 25px 50px;'>Die Namen stimmen nicht überein. Bitte gebe zwei mal den gleichen Listennamen ein.</div>";  
            }
            }
        
        if ( isset( $_POST['submitted4'] ) ) {
               foreach ($lists as $list) {
                    $name = htmlentities(str_replace(" ", "_", $list->name));
                    $wpdb->query($wpdb->prepare("UPDATE `{$table_name}` SET num = '%s' WHERE name = '%s'", array($_POST[$name], $list->name)));   
               }
            echo "<div id='success' style='background-color:lightgreen; padding: 25px 50px;'>Datenbank erfolgreich aktualisiert.</div>";
        }
        
        if ( isset($_GET["delete"]) ) {
         if (@mysqlist_delete($_GET["delete"], $_GET["list"])) {
             echo "<div id='success' style='background-color:lightgreen; padding: 25px 50px;'>Eintrag erfolgreich gelöscht. </div>";
         }
            
        }
    if ( isset( $_POST["deleteX"] ) ) {
        if ($_POST["captcha"] == "following finding") {
     mysqlist_deleteX();
        echo "<div id='success' style='background-color:lightgreen; padding: 25px 50px;'>Datenbank erfolgreich geleert.</div>";
        } else {
            echo "<div id='warning' style='background-color:red; padding: 25px 50px;'>Captcha falsch.</div>";
        }
    }
    
    if ( isset( $_POST["converttb"] ) ) {
        mysqlist_converttb($_POST["html"], $_POST["liste"]);
        }
    
    
    $lists = $wpdb->get_results("SELECT * FROM `$table_name` ORDER BY `num`");
    $lists2 = $wpdb->get_results("SELECT * FROM `$table_name2` ORDER BY `lista`");
    
    ?>
    <div class="wrap">
        <?php if (isset($_GET["loeschen"])) { mysqlist_loeschenBest(); } ?>
        <?php if (isset($_GET["converttb"])) { mysqlist_showConvertTb(); } ?>
        <h2>MySQList</h2><br>
        Willkommen zu "MySQList". Auf dieser Seite kannst du neue Einträge und neue Listenabschnitte erstellen.<br><br>
        <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <h3>Neuer Listenabschnitt</h3> 
            <input type="text" name="name" autocomplete="off" placeholder="Name" required><br><br>
            <input type="submit" value="Hinzufügen" name="submitted1"><br>
        </form>
        <br>
        <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <h3>Neuer Listeneintrag</h3>
            <input type="text" name="eintrag" placeholder="Eintrag"><br>
            <input type="text" name="url" placeholder="Url"><small>Optional, bitte mit "http://" anfangen.</small><br>
            <input type="date" name="date" placeholder="Ablaufdatum"><small>Optional, Gültige Formate: "DD.MM.YYYY", "DD-MM-YYYY", "YYYY-MM-DD"</small><br>
            <select name="list">
                <?php 
                foreach ($lists as $list) {
                    echo '<option value="'.$list->name.'">'.esc_html($list->name).'</option>';
                }
                if (empty($lists)) {
                    echo "<option>Bitte füge zuerst eine neue Liste hinzu</option>";
                }
                ?>
            </select><br><br>
            <input type="submit" value="Hinzufügen" name="submitted2"><br>
        </form>
        <br>
        <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post">
            <h3>Listenabschnitt löschen</h3> 
            <select name="list">
                <?php 
                foreach ($lists as $list) {
                    echo '<option value="'.$list->name.'">'.esc_html($list->name).'</option>';
                }
                if (empty($lists)) {
                    echo "<option>Keine Listenabschnitte gefunden</option>";
                }
                ?>
            </select><br><br>
            <label for="list2">Bitte bestätige deine Auswahl, indem du den Listennamen erneut eingibts:</label><br>
            <input type="text" name="list2"><br><br>
            <font color="red">Dadurch wird die Liste INKLUSIVE ihrer Einträge gelöscht! Bitte beachte, dass gleichnamige Listen ebenfalls gelöscht werden!</font><br>
            <input type="submit" value="Löschen" name="submitted3"><br>
        </form>
        <br>
        <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post">
            <h3>Reihenfolge verändern</h3>
            <table>
                <tr><td>Name</td><td>Nummer</td></tr>
                <?php 
                foreach ($lists as $list) {
                    $name = htmlentities(str_replace(" ", "_", $list->name));
                    $num = esc_html($list->num);
                    echo "<tr><td>".esc_html($list->name)."</td><td><input type='text' name='{$name}' value='{$num}'></td></tr>";
                }
                ?>
            </table>
            <input type="submit" value="Ändern" name="submitted4">
        </form>
        <br>
        <h3>Listenelemente löschen</h3>
            <table>
                <tr><td>Eintrag</td><td>Liste</td><td></td></tr>
                <?php 
                foreach ($lists2 as $list) {
                    $name = htmlentities(str_replace(" ", "_", $list->eintrag));
                    echo "<tr><td>".esc_html($list->eintrag)."</td><td>".esc_html($list->lista)."</td><td><a href='?page=mysqlist&delete={$list->eintrag}&list={$list->lista}'>Löschen</a></td></tr>";
                }
                ?>
            </table>
        <br><br>
        <a href="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>&loeschen"><font color="red">ALLES LÖSCHEN</font></a>
        <br><br><br><br>
        MySQList by <a href="<?php esc_url("http://bennetthollstein.de") ?>">Bennett Hollstein</a>
    </div>
    <?php
}
function mysqlist_shortcodes() {
    add_shortcode( 'mysqlist', 'mysqlist_readList' );
}

function mysqlist_settings () {
    add_settings_section(
'mysqlist_setting_section',
'MySQList Einstellungen',
'mysqlist_settingsSection',
'general'
);
 add_settings_field(
'mysqlist_enable_turquoise',
'Neue Einträge <span style="color: #33cccc;">Türkis</span> färben',
'mysqlist_updateSettingTurquoise',
'general',
'mysqlist_setting_section'
);
add_settings_field(
'mysqlist_enable_ad',
'Unter der Liste "Generiert durch MySQList von Bennett Hollstein" anzeigen',
'mysqlist_updateSettingAdvertise',
'general',
'mysqlist_setting_section'
);
add_settings_field(
'mysqlist_full_deinstallation',
'Bei der Deinstallation ALLE Listen und Einträge löschen',
'mysqlist_updateSettingFullD',
'general',
'mysqlist_setting_section'
);
register_setting( 'general', 'mysqlist_enable_turquoise' );
register_setting( 'general', 'mysqlist_enable_ad' );
}

function mysqlist_settingsSection() {
}

function mysqlist_updateSettingTurquoise() {
   $options = get_option( 'mysqlist_enable_turquoise' );

    $html = '<input type="checkbox" id="checkbox" name="mysqlist_enable_turquoise[checkbox]" value="1"' . checked( 1, $options['checkbox'], false ) . '/>';

    echo $html;
}

function mysqlist_updateSettingAdvertise() {
   $options = get_option( 'mysqlist_enable_ad' );

    $html = '<input type="checkbox" id="checkbox2" name="mysqlist_enable_ad[checkbox2]" value="1"' . checked( 1, $options['checkbox2'], false ) . '/>';

    echo $html;
}
function mysqlist_updateSettingFullD() {
   $options = get_option( 'mysqlist_full_deinstallation' );

    $html = '<input type="checkbox" id="checkbox3" name="mysqlist_full_deinstallation[checkbox3]" value="1"' . checked( 1, $options['checkbox3'], false ) . '/>';
    $html .= '<label for="checkbox3"><small>Ist diese Option deaktiviert werden die Listen auch bei einer erneuten Installation erhalten bleiben</small></label>';
    echo $html;
}


function mysqlist_checkdb() {
	if ( ! wp_next_scheduled( 'mysqlist_checkdbHook' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'daily', 'mysqlist_checkdbHook' );
	}
}
 

function mysqlist_activation() {
    create_db1();
    create_db2();
    wp_schedule_event(time(), 'daily', 'mysqlist_checkdb');
    flush_rewrite_rules();
}

function mysqlist_deactivation() {
 
    // Our post type will be automatically removed, so no need to unregister it
 
    // Clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
 
}
?>