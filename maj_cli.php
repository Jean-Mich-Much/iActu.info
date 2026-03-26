<?php
declare(strict_types=1);
/* maj_cli.php
 * Lance les jobs RSS
 */

$tempo = __DIR__.'/maj_tempo';
@file_put_contents($tempo, '');
@chmod($tempo, 0664);

require __DIR__.'/Fondation/php/job.php';
require __DIR__.'/Fondation/php/parser/lit_rss.php';
require __DIR__.'/Fondation/php/fusion_rss.php';
$max = random_int(20, 40);

debut_job();

/* =========
      Parsers
============ */

$jobs=[];

/* 1.1 Technologie (principaux) */
$jobs[]=['parse',['Clubic','clubic','Technologie','tec','https://www.clubic.com/feed/rss',5,$max]];
$jobs[]=['parse',['Cowcotland','cowcotla','Technologie','tec','https://feeds.feedburner.com/cowcotland',5,$max]];
$jobs[]=['parse',['Developpez','developp','Technologie','tec','https://www.developpez.com/index/rss',5,$max]];
$jobs[]=['parse',['Frandroid','frandroi','Technologie','tec','https://www.frandroid.com/feed',5,$max]];
$jobs[]=['parse',['Ginjfo','ginjfo','Technologie','tec','https://www.ginjfo.com/feed',5,$max]];
$jobs[]=['parse',['Gnt','gnt','Technologie','tec','https://www.generation-nt.com/export/rss.xml',5,$max]];
$jobs[]=['parse',['Goodtech','goodtech','Technologie','tec','https://goodtech.info/feed/',5,$max]];
$jobs[]=['parse',['Hardware &amp; co','hardco','Technologie','tec','https://hardwareand.co/all-content?format=feed&type=rss',5,$max]];
$jobs[]=['parse',['Informaticien','informa','Technologie','tec','https://www.informaticien.be/index.ks?page=rss_news',5,$max]];
$jobs[]=['parse',['Kulturegeek','kultureg','Technologie','tec','http://feeds.feedburner.com/Kulturegeek',5,$max]];
$jobs[]=['parse',['Le comptoir','lecompto','Technologie','tec','https://www.comptoir-hardware.com/home.xml',5,$max]];
$jobs[]=['parse',['Le journal du geek','lejourn','Technologie','tec','https://feeds.feedburner.com/journaldugeek/cqdl',5,$max]];
$jobs[]=['parse',['Les numeriques','lesnumer','Technologie','tec','https://www.lesnumeriques.com/rss-news.xml',5,$max]];
$jobs[]=['parse',['Next','next','Technologie','tec','https://next.ink/feed/free',5,$max]];
$jobs[]=['parse',['Nextpit','nextpit','Technologie','tec','https://www.nextpit.fr/feed',5,$max]];
$jobs[]=['parse',['Numerama','numerama','Technologie','tec','https://www.numerama.com/feed/',5,$max]];
$jobs[]=['parse',['Overclocking','overcloc','Technologie','tec','https://overclocking.com/feed/',5,$max]];
$jobs[]=['parse',['Presse-citron','presseci','Technologie','tec','https://www.presse-citron.net/feed/',5,$max]];
$jobs[]=['parse',['Tom&#39;s guide','tomguide','Technologie','tec','https://www.tomsguide.fr/feed/',5,$max]];
$jobs[]=['parse',['Tom&#39;s hardware','tomhardw','Technologie','tec','https://www.tomshardware.fr/feed/',5,$max]];

/* 1.2 Tech. Autres sites */
$jobs[]=['parse',['Fredzone','fredzone','Tech. Autres sites','tecautres','https://www.fredzone.org/category/news/feed/',5,$max]];
$jobs[]=['parse',['Hardwarecooking','hardcook','Tech. Autres sites','tecautres','https://www.hardwarecooking.fr/feed/',5,$max]];
$jobs[]=['parse',['Iphon','iphon','Tech. Autres sites','tecautres','https://www.iphon.fr/feed',5,$max]];
$jobs[]=['parse',['It-connect','itconnec','Tech. Autres sites','tecautres','https://www.it-connect.fr/feed/',5,$max]];
$jobs[]=['parse',['Korben','korben','Tech. Autres sites','tecautres','https://korben.info/feed',5,$max]];
$jobs[]=['parse',['Pause hardware','pausehar','Tech. Autres sites','tecautres','https://pausehardware.com/feed/',5,$max]];
$jobs[]=['parse',['Pc astuces','pcastuce','Tech. Autres sites','tecautres','https://www.pcastuces.com/xml/pca_logitheque.xml',5,$max]];
$jobs[]=['parse',['Siecle digital','siecledi','Tech. Autres sites','tecautres','https://siecledigital.fr/feed/',5,$max]];
$jobs[]=['parse',['Sospc','sospc','Tech. Autres sites','tecautres','https://sospc.name/feed/',5,$max]];
$jobs[]=['parse',['Touslesdrivers','touslesd','Tech. Autres sites','tecautres','https://www.touslesdrivers.com/php/scripts/news_rss.php',5,$max]];
$jobs[]=['parse',['Vonguru','vonguru','Tech. Autres sites','tecautres','https://vonguru.fr/feed/',5,$max]];

/* 1.3 Tech. English */
$jobs[]=['parse',['Hothardware','hothardw','Tech. English','tecenglish','https://hothardware.com/rss/news',5,$max]];
$jobs[]=['parse',['Igor','igor','Tech. English','tecenglish','https://www.igorslab.de/en/feed/',5,$max]];
$jobs[]=['parse',['Pc perspective','pcperspe','Tech. English','tecenglish','https://pcper.com/feed/',5,$max]];
$jobs[]=['parse',['Techpowerup','techpowe','Tech. English','tecenglish','https://www.techpowerup.com/rss/reviews',5,$max]];
$jobs[]=['parse',['Uploadvr','uploadvr','Tech. English','tecenglish','https://www.uploadvr.com/rss/',5,$max]];

/* 2.1 Apple */
$jobs[]=['parse',['Consomac','consomac','Apple','apple','https://consomac.fr/rss/consomac.xml',5,$max]];
$jobs[]=['parse',['Mac4ever','mac4ever','Apple','apple','https://www.mac4ever.com/flux/rss/content/actu',5,$max]];
$jobs[]=['parse',['Macbidouille','macbidou','Apple','apple','https://macbidouille.com/rss',5,$max]];
$jobs[]=['parse',['Macg.co','macgco','Apple','apple','https://megaflux.macg.co/',5,$max]];

/* 2.2 Apple autres */
$jobs[]=['parse',['Competence mac','competen','Apple autres','appleautres','https://www.competencemac.com/xml/syndication.rss',5,$max]];
$jobs[]=['parse',['Cuk','cuk','Apple autres','appleautres','https://leblogducuk.ch/feed/',5,$max]];
$jobs[]=['parse',['Iphoneaddict','iphonead','Apple autres','appleautres','http://feeds.feedburner.com/Iphoneaddictfr',5,$max]];

/* 2.3 Apple english */
$jobs[]=['parse',['9to5mac','9to5mac','Apple english','appleenglish','https://9to5mac.com/feed/',5,$max]];
$jobs[]=['parse',['Macrumors','macrumor','Apple english','appleenglish','http://feeds.macrumors.com/MacRumors-All',5,$max]];

/* 3.1 Jeux */
$jobs[]=['parse',['Factornews','factorne','Jeux','jeux','https://www.factornews.com/rss.xml',5,$max]];
$jobs[]=['parse',['Gamekult','gamekult','Jeux','jeux','https://www.gamekult.com/feed.xml',5,$max]];
$jobs[]=['parse',['Gamergen','gamergen','Jeux','jeux','https://gamergen.com/rss',5,$max]];
$jobs[]=['parse',['Indiemag','indiemag','Jeux','jeux','https://www.indiemag.fr/feed/rss.xml',5,$max]];
$jobs[]=['parse',['JVFrance','jv','Jeux','jeux','https://www.jvfrance.com/feed/',5,$max]];
$jobs[]=['parse',['Nofrag','nofrag','Jeux','jeux','https://feeds.feedburner.com/nofrag/disj',5,$max]];

/* 3.2 Jeux autres */
$jobs[]=['parse',['Gameblog','gameblog','Jeux autres','jeuxautres','https://www.gameblog.fr/rssmap/rss_all.xml',5,$max]];
$jobs[]=['parse',['Insert coin','insertco','Jeux autres','jeuxautres','https://www.insert-coin.fr/feed/',5,$max]];
$jobs[]=['parse',['Jeuxactu','jeuxactu','Jeux autres','jeuxautres','https://www.jeuxactu.com/rss/ja.rss',5,$max]];
$jobs[]=['parse',['Jeuxonline','jeuxonli','Jeux autres','jeuxautres','https://www.jeuxonline.info/rss/actualites/rss.xml',5,$max]];
$jobs[]=['parse',['Playerone','playeron','Jeux autres','jeuxautres','https://playerone.tv/feed/',5,$max]];
$jobs[]=['parse',['Puissance nintendo','puissanc','Jeux autres','jeuxautres','http://feeds.feedburner.com/pn-majs',5,$max]];
$jobs[]=['parse',['Xbox gamer','xboxgame','Jeux autres','jeuxautres','https://www.xbox-gamer.net/rss.php',5,$max]];

/* 3.3 Jeux english */
$jobs[]=['parse',['Dsogaming','dsogamin','Jeux english','jeuxenglish','https://www.dsogaming.com/feed/',5,$max]];
$jobs[]=['parse',['Ign','ign','Jeux english','jeuxenglish','https://feeds.feedburner.com/ign/all',5,$max]];
$jobs[]=['parse',['Pc gamer','pcgamer','Jeux english','jeuxenglish','https://www.pcgamer.com/feeds.xml',5,$max]];

/* 4.1 Linux */
$jobs[]=['parse',['Distrowatch','distrowa','Linux','linux','https://distrowatch.com/news/dwd.xml',5,$max]];
$jobs[]=['parse',['Gamingonlinux','gamingon','Linux','linux','https://www.gamingonlinux.com/article_rss.php',5,$max]];
$jobs[]=['parse',['Linuxfr','linuxfr','Linux','linux','https://linuxfr.org/news.atom',5,$max]];
$jobs[]=['parse',['Phoronix','phoronix','Linux','linux','https://www.phoronix.com/rss.php',5,$max]];
$jobs[]=['parse',['Ubuntu','ubuntu','Linux','linux','https://www.omgubuntu.co.uk/feed',5,$max]];

/* 5.1 Realite virtuelle */
$jobs[]=['parse',['Le monde vr','lemondev','Realite virtuelle','vr','https://www.lemonde.fr/realite-virtuelle/rss_full.xml',5,$max]];
$jobs[]=['parse',['Realitevirtuelle','realitev','Realite virtuelle','vr','https://realitevirtuelle.com/feed/',5,$max]];
$jobs[]=['parse',['Stylistme','stylistm','Realite virtuelle','vr','https://stylistme.com/feed',5,$max]];

/* 6.1 Infos france */
$jobs[]=['parse',['France 24 france','france24','Infos france','infosfr','https://www.france24.com/fr/france/rss',5,$max]];
$jobs[]=['parse',['Huffingtonpost','huffingt','Infos france','infosfr','https://www.huffingtonpost.fr/france/rss_headline.xml',5,$max]];
$jobs[]=['parse',['Le monde eco france','lemondee','Infos france','infosfr','https://www.lemonde.fr/economie-francaise/rss_full.xml',5,$max]];

/* 6.2 Infos monde */
$jobs[]=['parse',['Bfm','bfm','Infos monde','infosmonde','https://www.bfmtv.com/rss/international/',5,$max]];
$jobs[]=['parse',['Courrier inter.','courrier','Infos monde','infosmonde','https://www.courrierinternational.com/feed/rubrique/geopolitique/rss.xml',5,$max]];
$jobs[]=['parse',['France 24 afrique','fr24afri','Infos monde','infosmonde','https://www.france24.com/fr/afrique/rss',5,$max]];
$jobs[]=['parse',['France 24 ameriques','fr24amer','Infos monde','infosmonde','https://www.france24.com/fr/am%C3%A9riques/rss',5,$max]];
$jobs[]=['parse',['France 24 asie','fr24asie','Infos monde','infosmonde','https://www.france24.com/fr/asie-pacifique/rss',5,$max]];
$jobs[]=['parse',['France 24 moyen-orient','fr24moy','Infos monde','infosmonde','https://www.france24.com/fr/moyen-orient/rss',5,$max]];
$jobs[]=['parse',['Journal de montreal','journald','Infos monde','infosmonde','https://www.journaldemontreal.com/monde/rss.xml',5,$max]];
$jobs[]=['parse',['L&#39;express','lexpress','Infos monde','infosmonde','https://www.lexpress.fr/arc/outboundfeeds/rss/monde.xml',5,$max]];
$jobs[]=['parse',['Le figaro monde','lefigaro','Infos monde','infosmonde','https://www.lefigaro.fr/rss/figaro_international.xml',5,$max]];
$jobs[]=['parse',['Le monde international','lemondei','Infos monde','infosmonde','https://www.lemonde.fr/international/rss_full.xml',5,$max]];
$jobs[]=['parse',['Rfi europe','rfieurop','Infos monde','infosmonde','https://feeds.feedburner.com/rfi/europe',5,$max]];
$jobs[]=['parse',['Sud ouest international','sudouest','Infos monde','infosmonde','https://www.sudouest.fr/international/rss.xml',5,$max]];

/* 6.3 Sciences */
$jobs[]=['parse',['Futura-sciences','futurasc','Sciences','sciences','https://www.futura-sciences.com/rss/actualites.xml',5,$max]];
$jobs[]=['parse',['Sciences et avenir','scietave','Sciences','sciences','https://feeds.feedburner.com/sciencesetavenir/iactu',5,$max]];
$jobs[]=['parse',['Techno-science','technosc','Sciences','sciences','https://www.techno-science.net/include/news.xml',5,$max]];
$jobs[]=['parse',['Ca m&#39;interesse','caminter','Sciences','sciences','https://feed.prismamediadigital.com/v1/cam/rss?limit=20',5,$max]];

/* 6.4 Sciences autres */
$jobs[]=['parse',['Cnrs','cnrs','Sciences autres','sciencesautres','https://lejournal.cnrs.fr/rss',5,$max]];
$jobs[]=['parse',['Le monde sciences','lemondds','Sciences autres','sciencesautres','https://www.lemonde.fr/sciences/rss_full.xml',5,$max]];
$jobs[]=['parse',['Radio-canada sciences','radiocan','Sciences autres','sciencesautres','https://feeds.feedburner.com/radio-canada/wkogvs6Kv4D',5,$max]];

/* 6.5 Sciences english */
$jobs[]=['parse',['Discover mag','discover','Sciences english','sciencesenglish','https://www.discovermagazine.com/rss/all',5,$max]];
$jobs[]=['parse',['Physics world','physicsw','Sciences english','sciencesenglish','https://physicsworld.com/feed/',5,$max]];
$jobs[]=['parse',['Spacenews','spacene','Sciences english','sciencesenglish','https://spacenews.com/feed/',5,$max]];
$jobs[]=['parse',['Wired science','wiredsci','Sciences english','sciencesenglish','https://www.wired.com/feed/science/rss',5,$max]];

/* 6.6 Insolites */
$jobs[]=['parse',['7sur7','7sur7','Insolites','insolites','https://feeds.feedburner.com/7sur7/iactu',5,$max]];
$jobs[]=['parse',['La depeche insolite','ladepech','Insolites','insolites','https://www.ladepeche.fr/insolite/rss.xml',5,$max]];
$jobs[]=['parse',['Rmc insolite','rmcinsol','Insolites','insolites','https://rmc.bfmtv.com/rss/actualites/insolite/',5,$max]];

/* 6.7 Cinema tv */
$jobs[]=['parse',['Allocine','allocine','Cinema tv','cinetv','https://www.allocine.fr/rss/news.xml',5,$max]];
$jobs[]=['parse',['Cineserie','cineseri','Cinema tv','cinetv','https://www.cineserie.com/feed/',5,$max]];
$jobs[]=['parse',['Toutelatele','toutelat','Cinema tv','cinetv','https://toutelatele.ouest-france.fr/flux-general-tltv.php3',5,$max]];

/* 6.8 Sports */
$jobs[]=['parse',['L\'equipe','lequipe','Sports','sports','https://dwh.lequipe.fr/api/edito/rss?path=/',5,$max]];
$jobs[]=['parse',['Le figaro sport','lefigars','Sports','sports','https://www.lefigaro.fr/rss/figaro_sport.xml',5,$max]];
$jobs[]=['parse',['Le monde sport','lemondsp','Sports','sports','https://www.lemonde.fr/sport/rss_full.xml',5,$max]];
$jobs[]=['parse',['Maxifoot','maxifoot','Sports','sports','http://rss.maxifoot.com/football-general.xml',5,$max]];
$jobs[]=['parse',['Top mercato','topmerca','Sports','sports','https://www.topmercato.com/rss.php',5,$max]];

/* 6.9 Infos english */
$jobs[]=['parse',['Financial times','financia','Infos english','infosenglish','https://www.ft.com/rss/home/uk',5,$max]];
$jobs[]=['parse',['Foxnews','foxnews','Infos english','infosenglish','https://moxie.foxnews.com/feedburner/latest.xml',5,$max]];
$jobs[]=['parse',['National post','nationap','Infos english','infosenglish','https://nationalpost.com/feed',5,$max]];
$jobs[]=['parse',['New york post','nypost','Infos english','infosenglish','https://nypost.com/feed/',5,$max]];
$jobs[]=['parse',['Nyt','nyt','Infos english','infosenglish','https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml',5,$max]];
$jobs[]=['parse',['The guardian','guardian','Infos english','infosenglish','https://www.theguardian.com/international/rss',5,$max]];
$jobs[]=['parse',['The independent','independ','Infos english','infosenglish','http://www.independent.co.uk/rss',5,$max]];

shuffle($jobs);

foreach($jobs as [$func,$args]){
job_call($func,$args);
}

fin_job();
