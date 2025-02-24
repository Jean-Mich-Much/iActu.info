<?php phpinfo();phpinfo(INFO_MODULES); ?>
<?php
$status = opcache_get_status();
$formatted_status = [
    'Memory Usage' => $status['memory_usage'],
    'Interned Strings Usage' => $status['interned_strings_usage'],
    'Statistics' => $status['opcache_statistics']
];

// Fonction pour formater les nombres en français
function format_number($number) {
    return number_format($number, 0, ',', ' ');
}

// Fonction pour calculer les pourcentages
function calculate_percentage($used, $total) {
    if ($total > 0) {
        return round(($used / $total) * 100, 2);
    }
    return 0;
}

// Fonction pour formater les tableaux en HTML
function format_array_html($array, $level = 0) {
    $html = "<ul class='level-$level'>";
    foreach ($array as $key => $value) {
        $html .= "<li><strong>$key:</strong> ";
        if (is_array($value)) {
            $html .= format_array_html($value, $level + 1);
        } else {
            $html .= htmlspecialchars($value);
        }
        $html .= "</li>";
    }
    $html .= "</ul>";
    return $html;
}

// Formater les données avec pourcentages
$memory_usage = $formatted_status['Memory Usage'];
$interned_strings_usage = $formatted_status['Interned Strings Usage'];

$used_memory_percentage = calculate_percentage($memory_usage['used_memory'], $memory_usage['free_memory'] + $memory_usage['used_memory']);
$free_memory_percentage = calculate_percentage($memory_usage['free_memory'], $memory_usage['free_memory'] + $memory_usage['used_memory']);
$interned_used_memory_percentage = calculate_percentage($interned_strings_usage['used_memory'], $interned_strings_usage['buffer_size']);
$interned_free_memory_percentage = calculate_percentage($interned_strings_usage['free_memory'], $interned_strings_usage['buffer_size']);

$memory_usage['used_memory'] = format_number($memory_usage['used_memory']) . " ($used_memory_percentage%)";
$memory_usage['free_memory'] = format_number($memory_usage['free_memory']) . " ($free_memory_percentage%)";
$memory_usage['wasted_memory'] = format_number($memory_usage['wasted_memory']);
$memory_usage['current_wasted_percentage'] .= " %";

$interned_strings_usage['used_memory'] = format_number($interned_strings_usage['used_memory']) . " ($interned_used_memory_percentage%)";
$interned_strings_usage['free_memory'] = format_number($interned_strings_usage['free_memory']) . " ($interned_free_memory_percentage%)";
$interned_strings_usage['buffer_size'] = format_number($interned_strings_usage['buffer_size']);

$formatted_status['Memory Usage'] = $memory_usage;
$formatted_status['Interned Strings Usage'] = $interned_strings_usage;

// Affichage formaté
echo "<html>
<head>
<style>
body { font-family: Arial, sans-serif; }
.level-0 { list-style-type: none; margin: 0; padding: 0; }
.level-0 > li { margin: 10px 0; padding: 10px; background-color: #f0f0f0; border-radius: 5px; }
.level-1 { list-style-type: disc; padding-left: 20px; }
.level-2 { list-style-type: circle; padding-left: 20px; }
li strong { color: #333; }
</style>
</head>
<body>
<h1>Opcache Status</h1>
" . format_array_html($formatted_status) . "
</body>
</html>";
?>

<?php
$status = opcache_get_status();
$cached_scripts = $status['opcache_statistics']['num_cached_scripts'];
$used_memory = $status['memory_usage']['used_memory'];

// Calcul de la moyenne de consommation de RAM par script PHP
$average_memory_per_script = $cached_scripts > 0 ? $used_memory / $cached_scripts : 0;

echo "Nombre de scripts mis en cache : " . number_format($cached_scripts, 0, ',', ' ') . "\n";
echo "Mémoire utilisée par Opcache : " . number_format($used_memory, 0, ',', ' ') . " octets\n";
echo "Moyenne de consommation de RAM par script PHP : " . number_format($average_memory_per_script, 0, ',', ' ') . " octets\n";
?>
