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

</div>

</div>
