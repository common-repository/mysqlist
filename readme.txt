=== MySQList ===
Contributors: vantezzen
Tags: list, mysql, easy
Donate link: http://bennetthollstein.de
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Mit MySQList kann man ganz einfach Listen mit Ablaufdatum und Verlinkungen erstellen.

== Description ==
MySQList ist ein Wordpress Plugin, welches das Erstellen einer Liste von Dingen mit einem Ablaufdatum, wie z.B. Anmeldungen oder Termine ganz einfach macht.

== Installation ==
1. Lade das Plugin aus der Wordpress Plugin Seite herunter. Du kannst auch die ZIP von bennetthollstein.de herunterladen und danach im Adminmenü deiner Wordpress
    Installation das Plugin unter "Plugins" > "Installieren" > "Plugin hochladen" hochladen.
2. Aktiviere das Plugin im Pluginmenu in Wordpress

Nun siehst du auf der Linken Seite deines Adminmenüs einen neuen Menüpunkt "MySQList". Unter diesem kannst du:
- Listenabschnitte hinzufügen
- Listenelemente hinzufügen
- Listenabschnitte löschen
- Die Reihenfolge der Listenabschnitte auswählen.
- ...

Um die Liste auf der Wordpress Seite einzubauen, mache folgendes:
- Gehe im Adminmenü unter "Seiten" auf "Erstellen"
- Nun kannst du einen Kommentar etc. zu der Liste hinzuschreiben
- Füge an der Stelle, an der die Liste sein soll ein "[mysqlist]" ein.
- Danach kannst du erneut schreiben was du willst.
Hier ein Beispiel, wie das ganze Aussehen könnte:
"Willkommen bei meiner Liste der aktuellen Termine in unserer AG:
[mysqlist]
Wenn ihr noch weitere Fragen habt, schreibt uns einfach über das Formular"

== Frequently Asked Questions ==
= Kann ich das Datum auch in einer anderen Schreibweise angeben =

Mit Version 1.1 kam nun auch eine neue Verarbeitung des Datums dazu. D.H. du kannst dein Datum nun schreiben in: "01.01.2016", "01-01-2016", "2016.01.01", "2016-01-01"

== Screenshots ==
1. Administrator Menü
2. Ansicht einer Liste

== Changelog ==
= Zukünftige Versionen =
* Übersichtlichere Einstellungsseite
* Vorgenerierte Liste. Dies Entlastet den MySQL und Webserver
* Mehrere Sprachen

= 1.2.2 =
* Schwieriger Fehler gelöst: Fehler bei dem Ausgeben der Liste behoben
* Option "Bei der Deinstallation ALLE Listen und Einträge löschen" hinzugefügt
* uninstall.php verbessert
* uninstall.php -> Tabellen werden nun nur gelöscht wenn die Funktion "Bei der Deinstallation..." aktiviert ist
* uninstall.php -> Optionen werden nun gelöscht

= 1.2.1 =
* Bessere ReadMe.txt
* Neuer Uninstall Prozess

= 1.2 „Security Update“ =
* WPDB->PREPARE wird nun genutzt
* WPDB->INSERT wird nun statt WPDB->QUERY benutzt
* ESC_* Sequenzen eingebaut
* Option "Unter der Liste \'Generiert durch MySQList von Bennett Hollstein\' anzeigen" hinzugefügt
* Übersichtlichere PHP Dateien

= 1.1 =
* Neue Verarbeitung des Datums
* URL und Datum nun optional
* "ALLES LÖSCHEN" Funktion hinzugefügt
* Einstellungsmenü hinzugefügt
* Einstellung "Neue Beiträge in Türkis anzeigen" hinzugefügt
* Verbesserte Verarbeitung der Anfragen
* "convert.php" hinzugefügt zum Konvertieren von einer alten HTML Liste zu einer MYSQList
* Unbenutzte Funktionen gelöscht

= 1.0 =
* [mysqlist] hinzugefügt
* Funktionen "Listenabschnitt löschen" und "Reihenfolge ändern" hinzugefügt

= 0.9 =
* Menü hinzugefügt

= 0.5 =
* Tabellen werden automatisch erstellt

== Upgrade Notice ==
= 1.2.2 =
- Entfernt einen schwerwiegenden Fehler bei dem Ausgeben der Liste über [mysqlist]
- Fügt die Option "Bei der Deinstallation ALLE Listen und Einträge löschen" hinzu
- Verbesserte Deinstallation

= 1.2.1 =
Das Update enthält eine neue unistall.php, welche es ermöglicht, das Plugin volkommen zu deinstallieren.

= 1.2 =
Das Update behebt viele Sicherheitslücken. Zum Beispiel:
- Alle Eingaben werden vor dem Speichern nach Fehlern durchsucht
- Zur Speicherung der Dateien wird eine sichere Methode genutzt
- Und viele Mehr...

= 1.1 =
Viele neue Features, wie zum Beispiel:
- Neue Datumsverarbeitung: Das Datum kann nun in fast jedem Format angegeben werden
- Die Felder "URL" und "Datum" sind nun optional
- Eine Option zur Markierung neuer Einträge wurde hinzugefügt
- Alte HTML Tables können nun umgewandelt werden

== Support ==
Bitte geben Sie Feedback über das Formular unter bennetthollstein.de/kontakt
Probleme melden Sie bitte das Formular unter penntetollstein.de/problem