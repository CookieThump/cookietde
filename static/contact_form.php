<?php
// Datei für die Speicherung der Formulardaten definieren
define('DATA_FILE', '/var/lib/form_data/form_data.txt');

// Überprüfen, ob das Formular gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // "bot-field" prüfen, um Spam zu erkennen
    if (!empty($_POST['bot-field'])) {
        // Wenn das "bot-field" ausgefüllt ist, brechen wir ab
        die('Ungültige Anfrage erkannt.');
    }

    // Eingabefelder auslesen und säubern
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Eingaben validieren
    if (empty($name) || empty($email) || empty($message)) {
        die('Bitte füllen Sie alle Felder aus.');
    }

    // Daten in ein Format bringen, das gespeichert werden kann
    $entry = [
        'date' => date('Y-m-d H:i:s'),
        'name' => $name,
        'email' => $email,
        'message' => $message
    ];

    $entryLine = json_encode($entry) . PHP_EOL; // JSON Format für jede Zeile

    // Daten in die Datei schreiben
    try {
        file_put_contents(DATA_FILE, $entryLine, FILE_APPEND | LOCK_EX);
        echo 'Ihre Nachricht wurde erfolgreich gespeichert.';
    } catch (Exception $e) {
        die('Fehler beim Speichern Ihrer Nachricht: ' . $e->getMessage());
    }
} else {
    // Falls das Skript direkt aufgerufen wird
    die('Ungültiger Zugriff.');
}
?>
