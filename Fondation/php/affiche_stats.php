<?php
$stats_dir=__DIR__.'/../stats';
$day=date('Ymd');

$total_file=$stats_dir.'/total_'.$day.'.txt';
$total_visiteurs=0;
if(file_exists($total_file)){
$val=trim(@file_get_contents($total_file));
if(ctype_digit($val)){$total_visiteurs=(int)$val;}
}

$pages=[];
$dh=@opendir($stats_dir);
if($dh){
while(($f=readdir($dh))!==false){
if($f==='.'||$f==='..'){continue;}
if(!str_starts_with($f,'ip_')){continue;}
if(!str_ends_with($f,'.txt')){continue;}
if(str_contains($f,'Total')){continue;}
if(!str_contains($f,$day)){continue;}

$name=str_replace(['ip_','_'.$day.'.txt'],'',$f);
$count_file=$stats_dir.'/'.$name.'_'.$day.'.txt';

$count=0;
if(file_exists($count_file)){
$val=trim(@file_get_contents($count_file));
if(ctype_digit($val)){$count=(int)$val;}
}

$pages[$name]=$count;
}
closedir($dh);
}

arsort($pages);
?>

<div class="stats-wrapper">

<div class="stats-grid">

<div class="stats-box">
<div class="stats-header">
<div class="stats-title">Visiteurs uniques du jour</div>
<div class="stats-count"><?php echo $total_visiteurs; ?></div>
</div>
</div>

<div class="stats-box">
<div class="stats-header">
<div class="stats-title">Pages les plus visitées</div>
</div>

<div class="stats-section">
<div class="stats-table">
<?php
foreach($pages as $page=>$count){
echo '<div class="stats-row">';
echo '<div class="stats-col-page">'.htmlspecialchars($page).'</div>';
echo '<div class="stats-col-count">'.$count.'</div>';
echo '</div>';
}
?>
</div>
</div>

<div class="stats-section">
<div class="stats-box stats-rgpd">
<div class="stats-rgpd-title">🪧 Données & confidentialité</div>

<div class="stats-rgpd-text">
Les adresses IP ne sont jamais enregistrées telles quelles : elles sont immédiatement anonymisées dès leur arrivée sur le serveur.<br>
L’empreinte générée est irréversible et ne permet en aucun cas de retrouver l’adresse IP d’origine.<br><br>

Pour être très clair :<br>
• Votre adresse IP réelle n’est jamais stockée.<br>
• Avant d’être écrite dans un fichier, elle est mélangée avec votre navigateur, la date du jour et une clé secrète.<br>
• Le résultat est ensuite converti par une fonction mathématique (SHA‑256) produisant une empreinte totalement irréversible.<br>
• Même avec les fichiers, personne ne peut retrouver votre IP d’origine.<br>
• Vous pouvez d’ailleurs consulter le fichier du jour : <a href="https://iactu.info/Fondation/stats/ip_iActu_<?php echo date('Ymd'); ?>.txt" target="_blank">voir le fichier</a>.<br>
• Comme vous pourrez le constater, les données sont totalement illisibles.<br><br>

C’est ce que l’on appelle une anonymisation irréversible, ce qui signifie que ces données ne sont plus des données personnelles au sens du RGPD.<br><br>

👉 Les statistiques comptent uniquement des visites anonymes, et chaque visiteur n’est enregistré qu’une seule fois par jour, quel que soit le nombre de ses passages.<br>

</div>
</div>
</div>

</div>

</div>
