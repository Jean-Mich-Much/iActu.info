<?php
declare(strict_types=1);
/* maj_fusion_cli.php
 * Lance les jobs de fusions RSS
 */
 
$tempo = __DIR__.'/maj_fusion_tempo';
@file_put_contents($tempo, '');
@chmod($tempo, 0664);

$hasard = random_int(8, 24);
sleep($hasard);
require __DIR__.'/Fondation/php/job.php';
require __DIR__.'/Fondation/php/fusion_rss.php';
$max = random_int(20, 25);

debut_job();

/* =============
          Fusions
=============== */
$jobs = [
['Réalité virtuelle','vr',5,$max],
['Linux','linux',5,$max],
['Technologie','tec',120,240],
['Autres','tecautres',5,$max],
['English','tecenglish',5,$max],
['Apple','apple',20,60],
['Autres','appleautres',5,$max],
['English','appleenglish',5,$max],
['Jeux','jeux',30,90],
['Autres','jeuxautres',5,$max],
['English','jeuxenglish',5,$max],
['Sciences','sciences',20,60],
['Autres','sciencesautres',5,$max],
['English','sciencesenglish',5,$max],
['France','infosfr',5,$max],
['Monde','infosmonde',5,$max],
['Insolites','insolites',5,$max],
['TV - Cinéma','cinetv',5,$max],
['Sports','sports',5,$max],
['English','infosenglish',5,$max],
];

shuffle($jobs);
foreach ($jobs as $args) {
job_call('fusion_rss', $args);
}

fin_job();
