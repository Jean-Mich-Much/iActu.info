<?php
const TV_EXCLUDE=['CanalPlusSport.fr','CanalPlusCinema.fr','PlanetePlus.fr'];

const TV_ORDER=[
'TF1.fr','France2.fr','France3.fr','France4.fr','France5.fr','M6.fr','Arte.fr',
'LaChaineParlementaire.fr','W9.fr','TMC.fr','NT1.fr','Gulli.fr','BFMTV.fr','CNews.fr',
'LCI.fr','FranceInfo.fr','CStar.fr','T18.fr','NOVO19.fr','TF1SeriesFilms.fr',
'LEquipe21.fr','6ter.fr','Numero23.fr','RMCDecouverte.fr','Cherie25.fr','ParisPremiere.fr'
];

const TV_MAP=[
'TF1.fr'=>'1 - TF1',
'France2.fr'=>'2 - France 2',
'France3.fr'=>'3 - France 3',
'France4.fr'=>'4 - France 4',
'France5.fr'=>'5 - France 5',
'M6.fr'=>'6 - M6',
'Arte.fr'=>'7 - Arte',
'LaChaineParlementaire.fr'=>'8 - LCP',
'W9.fr'=>'9 - W9',
'TMC.fr'=>'10 - TMC',
'NT1.fr'=>'11 - TFX',
'Gulli.fr'=>'12 - Gulli',
'BFMTV.fr'=>'13 - BFM TV',
'CNews.fr'=>'14 - CNews',
'LCI.fr'=>'15 - LCI',
'FranceInfo.fr'=>'16 - Franceinfo',
'CStar.fr'=>'17 - CStar',
'T18.fr'=>'18 - T18',
'NOVO19.fr'=>'19 - NOVO19',
'TF1SeriesFilms.fr'=>'20 - TF1 Séries Films',
'LEquipe21.fr'=>'21 - L’Équipe',
'6ter.fr'=>'22 - 6ter',
'Numero23.fr'=>'23 - RMC Story',
'RMCDecouverte.fr'=>'24 - RMC Découverte',
'Cherie25.fr'=>'25 - RMC Life',
'ParisPremiere.fr'=>'26 - Paris Première'
];

function tv($src){
$xml=@simplexml_load_file($src);
if(!$xml)return[];

$now=new DateTimeImmutable();
$today=$now->format('Y-m-d');
$tomorrow=$now->modify('+1 day')->format('Y-m-d');

$min=19;
$maxNow=5;

$channels=[];
foreach($xml->programme as$x){
$ch=(string)$x['channel'];
if(in_array($ch,TV_EXCLUDE,true))continue;
if(!isset(TV_MAP[$ch]))continue;

try{
$ds=(string)$x['start'];
$df=(string)$x['stop'];
$d=new DateTimeImmutable($ds);
$f=new DateTimeImmutable($df);

$diff=$d->diff($f);
$dur=$diff->i+($diff->h*60);
if($dur<$min)continue;

$ti=trim((string)$x->title);
$de=trim((string)$x->desc);
$cat=[];
foreach($x->category as$c)$cat[]=trim((string)$c);
$cat=implode(', ',$cat);

$channels[$ch][]=[
'start'=>$d,
'end'=>$f,
'duree'=>$dur,
'titre'=>$ti,
'desc'=>$de,
'cat'=>$cat
];

}catch(Exception){
continue;}}

foreach($channels as$ch=>&$list){
usort($list,static function($a,$b){
return$a['start']<=>$b['start'];});}
unset($list);

$out=[];
foreach(TV_ORDER as$ch){
if(!isset($channels[$ch]))continue;
$list=$channels[$ch];

$out[$ch]=[
'ce_soir'=>[],
'en_ce_moment'=>[],
'demain_soir'=>[]
];

$primeCount=0;
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
$fp=(int)$p['end']->format('Hi');
$hd=(int)$p['start']->format('Hi');
if($sd===$today&&$fp>=2129){
if(
($hd>=1149&&$fp>=2129)||($hd>=1249&&$fp>=2129)||($hd>=1349&&$fp>=2129)||
($hd>=1449&&$fp>=2129)||($hd>=1549&&$fp>=2129)||($hd>=1649&&$fp>=2129)||
($hd>=1749&&$fp>=2129)||($hd>=1849&&$fp>=2129)||($hd>=1949&&$fp>=2129)
){
$out[$ch]['ce_soir'][]=$p;
$primeCount++;
if($primeCount>=2)break;}}}

if(count($out[$ch]['ce_soir'])===1){
foreach($list as$p){
if($p['start']->format('Y-m-d')===$today&&$p['end']->format('Y-m-d')===$tomorrow&&$p['duree']>=$min){
$out[$ch]['ce_soir'][]=$p;
break;}}
if(count($out[$ch]['ce_soir'])===1){
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
if($sd!==$tomorrow)continue;
$h=(int)$p['start']->format('Hi');
if($h>=0&&$h<=459&&$p['duree']>=$min){
$out[$ch]['ce_soir'][]=$p;
break;}}}}

if(count($out[$ch]['ce_soir'])===0){
$best=null;
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
$h=(int)$p['start']->format('Hi');
if($sd===$today&&$h>=1939&&$h<=2139){
if($best===null||$p['duree']>$best['duree'])$best=$p;
}}
if($best!==null)$out[$ch]['ce_soir'][]=$best;
if($best!==null){
$end=$best['end'];
foreach($list as$p){
if($p['start']>=$end&&$p['duree']>=19){
$out[$ch]['ce_soir'][]=$p;
break;}}}
}

$nowList=[];
$after=[];
foreach($list as$p){
if($p['start']<=$now&&$p['end']>$now)$nowList[]=$p;
elseif($p['start']>$now)$after[]=$p;}
$out[$ch]['en_ce_moment']=array_slice([...$nowList,...$after],0,$maxNow);

$primeCount=0;
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
$fp=(int)$p['end']->format('Hi');
$hd=(int)$p['start']->format('Hi');
if($sd===$tomorrow&&$fp>=2129){
if(
($hd>=1149&&$fp>=2129)||($hd>=1249&&$fp>=2129)||($hd>=1349&&$fp>=2129)||
($hd>=1449&&$fp>=2129)||($hd>=1549&&$fp>=2129)||($hd>=1649&&$fp>=2129)||
($hd>=1749&&$fp>=2129)||($hd>=1849&&$fp>=2129)||($hd>=1949&&$fp>=2129)
){
$out[$ch]['demain_soir'][]=$p;
$primeCount++;
if($primeCount>=2)break;}}}

if(count($out[$ch]['demain_soir'])===1){
foreach($list as$p){
if($p['start']->format('Y-m-d')===$tomorrow&&$p['end']->format('Y-m-d')!==$tomorrow&&$p['duree']>=$min){
$out[$ch]['demain_soir'][]=$p;
break;}}
if(count($out[$ch]['demain_soir'])===1){
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
if($sd!==$tomorrow)continue;
$h=(int)$p['start']->format('Hi');
if($h>=0&&$h<=459&&$p['duree']>=$min){
$out[$ch]['demain_soir'][]=$p;
break;}}}}

if(count($out[$ch]['demain_soir'])===0){
$best=null;
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
$h=(int)$p['start']->format('Hi');
if($sd===$tomorrow&&$h>=1939&&$h<=2139){
if($best===null||$p['duree']>$best['duree'])$best=$p;
}}
if($best!==null)$out[$ch]['demain_soir'][]=$best;
if($best!==null){
$end=$best['end'];
foreach($list as$p){
if($p['start']>=$end&&$p['duree']>=19){
$out[$ch]['demain_soir'][]=$p;
break;}}}
}

}

return$out;}

function nom_chaine($id){
return TV_MAP[$id]??$id;}

function afficher_page_tv($tv){
echo'<div class="news-wrapper"><div class="news-grid">';
foreach($tv as$ch=>$sec){
$nom=htmlspecialchars(nom_chaine($ch));
echo'<div class="news-box">';
echo'<div class="news-header"><div class="news-title"><a class="news-title-link">🚀 '.$nom.'</a></div></div>';

echo'<div class="news-section"><div class="section-title">🎬 Ce soir</div>';
foreach($sec['ce_soir'] as$p){
$h=$p['start']->format('H:i');
$t=htmlspecialchars($p['titre']);
$c=trim($p['cat']);
$lc=strtolower($c);
$showCat=$c!==''&&!str_starts_with($lc,'inconnu');
$d=trim($p['desc']);
$showDesc=str_word_count($d)>=3;
echo'<div class="news-item"><div class="n title">🍿 '.$h.' - '.$t.'</div><div class="n meta">⏱ '.$p['duree'].' mn'.($showCat?' - '.htmlspecialchars($c):'').'</div>'.($showDesc?'<div class="n desc">📜 '.htmlspecialchars($d).'</div>':'').'</div>';
}
echo'</div>';

echo'<div class="news-section"><div class="section-title">🕒 En ce moment</div>';
foreach($sec['en_ce_moment'] as$p){
$h=$p['start']->format('H:i');
$t=htmlspecialchars($p['titre']);
echo'<div class="news-item"><div class="n title">🏷️ '.$h.' - '.$t.'</div></div>';
}
echo'</div>';

echo'<div class="news-section"><div class="section-title">🌙 Demain soir</div>';
foreach($sec['demain_soir'] as$p){
$h=$p['start']->format('H:i');
$t=htmlspecialchars($p['titre']);
$c=trim($p['cat']);
$lc=strtolower($c);
$showCat=$c!==''&&!str_starts_with($lc,'inconnu');
$d=trim($p['desc']);
$showDesc=str_word_count($d)>=3;
echo'<div class="news-item"><div class="n title">🍿 '.$h.' - '.$t.'</div><div class="n meta">⏱ '.$p['duree'].' mn'.($showCat?' - '.htmlspecialchars($c):'').'</div>'.($showDesc?'<div class="n desc">📜 '.htmlspecialchars($d).'</div>':'').'</div>';
}
echo'</div>';

echo'</div>';}
echo'</div></div>';}

function tv_heure($d){
return$d->format('H:i');}
