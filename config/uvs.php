<?php

return [

    /*
    |--------------------------------------------------------------------------
    | UVS Installationsverzeichnis
    |--------------------------------------------------------------------------
    |
    | Die beiden Installationen liegen im Server-Grundverzeichnis:
    |   API: /uvs_api
    |   UVS: /uvs_dev
    |
    | Der UVS-Pfad ist fuer diese Installation bewusst fest im Code verankert
    | und wird nicht aus der .env gelesen. Fuer IIS wird der physische
    | Geschwisterordner von uvs_api verwendet, nicht der URL-Pfad /uvs_dev.
    |
    */

    'root' => dirname(base_path()) . DIRECTORY_SEPARATOR . 'uvs_dev',

    /*
    |--------------------------------------------------------------------------
    | Freigegebene Dokumentverzeichnisse
    |--------------------------------------------------------------------------
    |
    | Nur Dateien unterhalb dieser Verzeichnisse duerfen ausgeliefert werden.
    | Die Zuordnung erfolgt ueber den Dokumenttyp aus dem Webhook-Payload.
    | Pfade sind relativ zu 'root'. Daraus entstehen exakt:
    |   /uvs_dev/data/pdf/angebote
    |   /uvs_dev/data/pdf/vertraege
    |
    */

    'document_dirs' => [
        'angebot' => 'data/pdf/angebote',
        'vertrag' => 'data/pdf/vertraege',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gueltigkeitsdauer der signierten Dokument-URL (Minuten)
    |--------------------------------------------------------------------------
    |
    | Make ruft die URL unmittelbar nach dem Webhook ab. Der Wert ist bewusst
    | grosszuegig gewaehlt, damit auch ein wiederholter oder eingereihter
    | Make-Lauf die Datei noch erreicht.
    |
    */

    'document_url_ttl' => (int) env('UVS_DOCUMENT_URL_TTL', 30),

];
