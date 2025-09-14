<?php
$status = opcache_get_status();
$formatted_status = [
    'Utilisation de la mémoire' => $status['memory_usage'],
    'Utilisation des chaînes' => $status['interned_strings_usage'],
    'Statistiques' => $status['opcache_statistics']
];

function format_nombre_humain($number) {
    if ($number >= 1048576) {
        return number_format(round($number / 1048576, 2), 2, ',', ' ') . ' Mo'; // Convertir en Mo avec une virgule
    }
    if ($number >= 1024) {
        return number_format(round($number / 1024, 2), 2, ',', ' ') . ' Ko'; // Convertir en Ko avec une virgule
    }
    return number_format($number, 0, ',', ' ') . ' octets'; // Sinon, octets
}

function formater_pourcentage($number) {
    $rounded = round($number, 2); // Arrondi à deux décimales
    if ($rounded >= 100) {
        $rounded = 100; // Fixer à 100 % si nécessaire
    }
    return number_format($rounded, 2, ',', ' ') . ' %'; // Format français avec virgule
}

function formater_date_si_necessaire($cle, $valeur) {
    if (str_contains($cle, 'Heure') && is_numeric($valeur)) {
        $date = new DateTime();
        $date->setTimestamp($valeur);
        $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        $mois = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        $jour_semaine = $jours[$date->format('w')];
        $mois_nom = $mois[$date->format('n') - 1];
        return ucfirst("$jour_semaine " . $date->format('d') . " $mois_nom " . $date->format('Y') . " à " . $date->format('H\\hi'));
    }
    return $valeur;
}

function traduire_et_corriger_texte($texte) {
    $replacements = [
        'used_memory' => 'Mémoire utilisée',
        'free_memory' => 'Mémoire libre',
        'wasted_memory' => 'Mémoire gaspillée',
        'current_wasted_percentage' => 'Mémoire gaspillée (pourcentage)',
        'buffer_size' => 'Taille du tampon',
        'number_of_strings' => 'Nombre de chaînes',
        'num_cached_scripts' => 'Scripts mis en cache',
        'num_cached_keys' => 'Clés mises en cache',
        'max_cached_keys' => 'Nombre maximum de clés en cache',
        'hits' => 'Hits',
        'start_time' => 'Heure de début',
        'last_restart_time' => 'Dernière heure de redémarrage',
        'oom_restarts' => 'Redémarrages dus au manque de mémoire',
        'hash_restarts' => 'Redémarrages dus au hash',
        'manual_restarts' => 'Redémarrages manuels',
        'misses' => 'Échecs',
        'blacklist_misses' => 'Échecs de liste noire',
        'blacklist_miss_ratio' => 'Ratio d’échec de liste noire',
        'opcache_hit_rate' => 'Taux de succès d’Opcache',
        'last_reHeure de début' => 'Dernière heure de redémarrage',
        'blacklist_Échecs' => 'Échecs sur liste noire'
    ];
    return str_replace(array_keys($replacements), array_values($replacements), $texte);
}

function formater_tableau_html($tableau) {
    $html = '<table>';
    foreach ($tableau as $cle => $valeur) {
        $html .= '<tr>';
        $html .= '<td><strong>' . htmlspecialchars(traduire_et_corriger_texte($cle)) . '</strong></td>';
        if (is_array($valeur)) {
            $html .= '<td>' . formater_tableau_html($valeur) . '</td>';
        } else {
            $html .= '<td>' . htmlspecialchars(formater_valeur($cle, $valeur)) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

function formater_valeur($cle, $valeur) {
    if (str_contains($cle, 'pourcentage') || $cle === 'Taux de succès d’Opcache') {
        return formater_pourcentage($valeur);
    }
    if (str_contains($cle, 'Mémoire') || str_contains($cle, 'Taille')) {
        return format_nombre_humain($valeur);
    }
    if (str_contains($cle, 'Heure')) {
        return formater_date_si_necessaire($cle, $valeur);
    }
    if ($cle == 'Échecs') {
        return $valeur; // Laisser les échecs brut
    }
    return $valeur;
}

foreach ($formatted_status as $section => &$values) {
    foreach ($values as $key => $value) {
        unset($values[$key]);
        $values[traduire_et_corriger_texte($key)] = $value;
    }
}

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Opcache</title>
<style>
:root {
    --c1: rgba(8, 8, 8, 1);
    --c2: rgba(255, 255, 255, 0.8);
    --c3: rgba(255, 192, 32, 0.9);
    --c4: rgba(255, 144, 32, 0.9);
    --c5: rgba(32, 192, 255, 0.9);
    --c6: rgba(28, 28, 36, 1);
    --c7: rgba(54, 54, 62, 1);
    --c8: rgba(30, 30, 38, 1);
    --c9: rgba(18, 18, 26, 1);
    --c10: rgba(32, 176, 128, 1);
    --c11: rgba(168, 168, 176, 0.8);
    --c12: rgba(68, 68, 76, 0.8);
}
body {
    background-color: var(--c1);
    color: var(--c2);
    font-family: Arial, sans-serif;
    font-variation-settings: 'wght' 500;
    margin: 20px;
}
h1 {
    color: var(--c3);
    text-align: center;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: var(--c8);
    color: var(--c2);
}
table tr {
    border: 1px solid var(--c7);
}
table td {
    padding: 10px;
    border: 1px solid var(--c7);
}
table td strong {
    color: var(--c10);
}
</style>
</head>
<body>
<h1>Opcache</h1>
" . formater_tableau_html($formatted_status) . "
</body>
</html>";
