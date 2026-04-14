<?php
$menu=[
["id"=>"technologie","label"=>"Tech.","ico"=>"🤖","url"=>"Page_technologie.php","title"=>" Technologie "],
["id"=>"apple","label"=>"Apple","ico"=>"🍏","url"=>"Page_Apple.php","title"=>" Apple "],
["id"=>"jeux","label"=>"Jeux","ico"=>"🕹️","url"=>"Page_jeux.php","title"=>" Jeux "],
["id"=>"sciences","label"=>"Sciences","ico"=>"🧪","url"=>"Page_sciences.php","title"=>" Sciences "],
["id"=>"actu","label"=>"Actu","ico"=>"🗞️","url"=>"Page_actualités.php","title"=>" Actualités "],
["id"=>"tv","label"=>"TV","ico"=>"📺","url"=>"Page_tv.php","title"=>" Programme TV "]
];


$jours=["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"];
$mois=["","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"];

$now=new DateTime();
$j=$jours[$now->format("w")];
$d=$now->format("j");
$m=$mois[$now->format("n")];
$txt_date="$j $d $m";
$txt_heure=$now->format("H:i");

$prev=(clone $now);$prev->modify("-1 day");$jp=$prev->format("j");
$next=(clone $now);$next->modify("+1 day");$js=$next->format("j");
?>

<div class="menu">
<div class="menu-left">
<a id="menu-logo" class="logo" href="index.php" title=" Accueil ">
<div class="logo-part logo-ico">🔖</div>
<div class="logo-part logo-iACTU">iACTU</div>
<div class="logo-part logo-barre">|</div>
<div class="logo-part logo-INFO">INFO</div>
<div class="logo-part logo-rocket">🚀</div>
</a>
</div>

<div class="menu-center">
<?php foreach($menu as $p):$active=($page_active===$p["id"]);?>
<a id="menu-item-<?php echo $p["id"];?>" class="menu-item <?php echo $active?"page_active":"";?>" href="<?php echo $p["url"];?>" title="<?php echo $p["title"];?>">
<span class="menu_ico"><?php echo $p["ico"];?></span>
<?php if($active):?><span class="menu_label"><?php echo $p["label"];?></span><?php endif;?>
</a>
<?php endforeach;?>
</div>

<div class="menu-right">
<a id="menu-prev" class="nav-prev" title=" Jour précédent " href="<?php echo $nom_page.$jp;?>.html">⬅️</a>
<span id="menu-date" class="nav-date">📅 <?php echo $txt_date;?></span>
<a id="menu-next" class="nav-next" title=" Jour suivant " href="<?php echo $nom_page.$js;?>.html">➡️</a>
<span id="menu-hour" class="nav-hour">⏰ Màj <?php echo $txt_heure;?></span>
</div>
</div>
