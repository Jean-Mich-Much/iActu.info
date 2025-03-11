<?php
$fichier_source='Structure/cache/tv/xmltv_cp.xml';
$fichier_aujourdhui="Structure/cache/tv/xmltv_" . date('j') . ".xml";
$fichier_demain="Structure/cache/tv/xmltv_" . (date('j') + 1) . ".xml";
$fichier_apresdemain="Structure/cache/tv/xmltv_" . (date('j') + 2) . ".xml";
$contenu = file_get_contents($fichier_source);
if (substr_count($contenu,'</tv>')===1 && substr_count($contenu,'<programme start')>1024)
{@copy($fichier_source,$fichier_aujourdhui);@copy($fichier_source,$fichier_demain);@copy($fichier_source,$fichier_apresdemain);}
