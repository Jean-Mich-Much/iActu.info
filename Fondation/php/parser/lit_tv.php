<?php
function tv($src){
$xml=@simplexml_load_file($src);
if(!$xml)return[];

$now=new DateTime();
$today=$now->format('Y-m-d');
$tomorrow=(clone$now)->modify('+1 day')->format('Y-m-d');

$min=19;
$maxPrime=2;
$maxNow=5;

$channels=[];

/* PARSE XML */
foreach($xml->programme as$x){
try{
$ds=(string)$x['start'];
$df=(string)$x['stop'];
$d=new DateTime($ds);
$f=new DateTime($df);

$ch=(string)$x['channel'];
if(!isset($channels[$ch]))$channels[$ch]=[];

$dur=$d->diff($f)->i+($d->diff($f)->h*60);
if($dur<$min)continue;

$ti=trim((string)$x->title);
$de=trim((string)$x->desc);
$de=$de!==''?$de:'Aucune description disponible';

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

}catch(Exception$e){continue;}}

/* TRI */
foreach($channels as$ch=>&$list){
usort($list,function($a,$b){
return$a['start']<=>$b['start'];});}

$out=[];

/* TRAITEMENT */
foreach($channels as$ch=>$list){
$out[$ch]=[
'ce_soir'=>[],
'en_ce_moment'=>[],
'demain_soir'=>[]
];

/* PRIME DU JOUR */
$primeCount=0;
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
$fp=(int)$p['end']->format('Hi');
$hd=(int)$p['start']->format('Hi');

if($sd===$today&&$fp>=2129){
if(
($hd>=1149&&$fp>=2129)||
($hd>=1249&&$fp>=2129)||
($hd>=1349&&$fp>=2129)||
($hd>=1449&&$fp>=2129)||
($hd>=1549&&$fp>=2129)||
($hd>=1649&&$fp>=2129)||
($hd>=1749&&$fp>=2129)||
($hd>=1849&&$fp>=2129)||
($hd>=1949&&$fp>=2129)
){
$out[$ch]['ce_soir'][]=$p;
$primeCount++;
if($primeCount>=2)break;}}}

/* SECOND PRIME SUBTIL : SI 1 SEUL */
if(count($out[$ch]['ce_soir'])===1){

/* 1) PROGRAMME AVANT MINUIT MAIS FINISSANT APRÈS */
foreach($list as$p){
if(
$p['start']->format('Y-m-d')===$today &&
$p['end']->format('Y-m-d')===$tomorrow &&
$p['duree']>=$min
){
$out[$ch]['ce_soir'][]=$p;
break;
}}

/* 2) SINON : PROGRAMME APRÈS MINUIT (00:00 → 04:59) */
if(count($out[$ch]['ce_soir'])===1){
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
if($sd!==$tomorrow)continue;
$h=(int)$p['start']->format('Hi');
if($h>=0&&$h<=459&&$p['duree']>=$min){
$out[$ch]['ce_soir'][]=$p;
break;
}}
}
}

/* EN CE MOMENT */
$nowList=[];
$after=[];
foreach($list as$p){
if($p['start']<=$now&&$p['end']>$now)$nowList[]=$p;
elseif($p['start']>$now)$after[]=$p;}
$out[$ch]['en_ce_moment']=array_slice(array_merge($nowList,$after),0,$maxNow);

/* DEMAIN SOIR */
$primeCount=0;
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
$fp=(int)$p['end']->format('Hi');
$hd=(int)$p['start']->format('Hi');

if($sd===$tomorrow&&$fp>=2129){
if(
($hd>=1149&&$fp>=2129)||
($hd>=1249&&$fp>=2129)||
($hd>=1349&&$fp>=2129)||
($hd>=1449&&$fp>=2129)||
($hd>=1549&&$fp>=2129)||
($hd>=1649&&$fp>=2129)||
($hd>=1749&&$fp>=2129)||
($hd>=1849&&$fp>=2129)||
($hd>=1949&&$fp>=2129)
){
$out[$ch]['demain_soir'][]=$p;
$primeCount++;
if($primeCount>=2)break;}}}

/* SECOND PRIME SUBTIL POUR DEMAIN SOIR */
if(count($out[$ch]['demain_soir'])===1){

/* 1) AVANT MINUIT MAIS FINISSANT APRÈS */
foreach($list as$p){
if(
$p['start']->format('Y-m-d')===$tomorrow &&
$p['end']->format('Y-m-d')!==$tomorrow &&
$p['duree']>=$min
){
$out[$ch]['demain_soir'][]=$p;
break;
}}

/* 2) APRÈS MINUIT */
if(count($out[$ch]['demain_soir'])===1){
foreach($list as$p){
$sd=$p['start']->format('Y-m-d');
if($sd!==$tomorrow)continue;
$h=(int)$p['start']->format('Hi');
if($h>=0&&$h<=459&&$p['duree']>=$min){
$out[$ch]['demain_soir'][]=$p;
break;
}}
}
}

}

return$out;}

/* NOM CHAÎNE */
function nom_chaine($id){
$r=str_replace(
['.fr','NT1','LEquipe21','Numero23','RMCDecouverte','Cherie25','ParisPremiere','CanalPlusSport','CanalPlusCinema','PlanetePlus','CanalPlus','France2','France3','France5','France4','LaChaineParlementaire','BFMTV','TF1SeriesFilms','FranceInfo'],
['','TFX','La chaine l’Équipe','RMC STORY','RMC Découverte','Chérie 25','Paris Première','Canal+ Sport','Canal+ Cinéma','Planete+','Canal+','France 2','France 3','France 5','France 4','LCP','BFM TV','TF1 Séries-Films','France Info'],
$id);
return trim($r);}

/* AFFICHAGE */
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
$c=htmlspecialchars($p['cat']);
$d=htmlspecialchars($p['desc']);
echo'<div class="news-item"><div class="n title">🍿 '.$h.' - '.$t.'</div><div class="n meta">⏱ '.$p['duree'].' mn - '.$c.'</div><div class="n desc">📜 '.$d.'</div></div>';
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
$c=htmlspecialchars($p['cat']);
$d=htmlspecialchars($p['desc']);
echo'<div class="news-item"><div class="n title">🍿 '.$h.' - '.$t.'</div><div class="n meta">⏱ '.$p['duree'].' mn - '.$c.'</div><div class="n desc">📜 '.$d.'</div></div>';
}
echo'</div>';

echo'</div>';}

echo'</div></div>';}

/* HEURE */
function tv_heure($d){
return$d->format('H:i');}
