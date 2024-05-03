<?php
@copy('copys.tmp', 'copys.time');
@chmod('copys.time', 0664);

if (@filemtime('News/copy/clubi.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_86&user=Gon1Kirua&token=flux&hours=912', 'News/copy/clubi.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/nextim.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_98&user=Gon1Kirua&token=flux&hours=912', 'News/copy/nextim.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/gennt.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_91&user=Gon1Kirua&token=flux&hours=912', 'News/copy/gennt.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/cowco.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_87&user=Gon1Kirua&token=flux&hours=912', 'News/copy/cowco.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/tomsh.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_105&user=Gon1Kirua&token=flux&hours=912', 'News/copy/tomsh.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/tomsg.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_104&user=Gon1Kirua&token=flux&hours=912', 'News/copy/tomsg.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/ginjf.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_92&user=Gon1Kirua&token=flux&hours=912', 'News/copy/ginjf.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/compt.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_95&user=Gon1Kirua&token=flux&hours=912', 'News/copy/compt.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/overc.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_101&user=Gon1Kirua&token=flux&hours=912', 'News/copy/overc.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/minim.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_97&user=Gon1Kirua&token=flux&hours=912', 'News/copy/minim.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/numer.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_100&user=Gon1Kirua&token=flux&hours=912', 'News/copy/numer.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/fredz.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_90&user=Gon1Kirua&token=flux&hours=912', 'News/copy/fredz.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/jourdg.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_94&user=Gon1Kirua&token=flux&hours=912', 'News/copy/jourdg.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/lesnum.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_96&user=Gon1Kirua&token=flux&hours=912', 'News/copy/lesnum.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/presc.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_102&user=Gon1Kirua&token=flux&hours=912', 'News/copy/presc.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/infor.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_93&user=Gon1Kirua&token=flux&hours=912', 'News/copy/infor.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/devel.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_88&user=Gon1Kirua&token=flux&hours=912', 'News/copy/devel.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/siedi.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_103&user=Gon1Kirua&token=flux&hours=912', 'News/copy/siedi.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/nexpi.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_99&user=Gon1Kirua&token=flux&hours=912', 'News/copy/nexpi.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/frand.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_89&user=Gon1Kirua&token=flux&hours=912', 'News/copy/frand.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get18.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_18&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get18.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get14.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_14&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get14.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get20.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_20&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get20.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get21.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_21&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get21.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/macge.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_75&user=Gon1Kirua&token=flux&hours=912', 'News/copy/macge.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/macbi.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_74&user=Gon1Kirua&token=flux&hours=912', 'News/copy/macbi.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/conso.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_72&user=Gon1Kirua&token=flux&hours=912', 'News/copy/conso.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/mc4ve.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_73&user=Gon1Kirua&token=flux&hours=912', 'News/copy/mc4ve.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get16.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_16&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get16.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get17.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_17&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get17.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/facto.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_54&user=Gon1Kirua&token=flux&hours=912', 'News/copy/facto.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/gamek.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_55&user=Gon1Kirua&token=flux&hours=912', 'News/copy/gamek.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/gameg.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_56&user=Gon1Kirua&token=flux&hours=912', 'News/copy/gameg.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/indie.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_57&user=Gon1Kirua&token=flux&hours=912', 'News/copy/indie.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/jeuxv.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_58&user=Gon1Kirua&token=flux&hours=912', 'News/copy/jeuxv.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/nfrag.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_59&user=Gon1Kirua&token=flux&hours=912', 'News/copy/nfrag.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get12.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_12&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get12.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/futur.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_36&user=Gon1Kirua&token=flux&hours=912', 'News/copy/futur.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/tecsci.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_39&user=Gon1Kirua&token=flux&hours=912', 'News/copy/tecsci.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/sciav.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_38&user=Gon1Kirua&token=flux&hours=912', 'News/copy/sciav.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/scipo.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=f_37&user=Gon1Kirua&token=flux&hours=912', 'News/copy/scipo.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get13.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_13&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get13.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get8.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_8&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get8.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get9.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_9&user=Gon1Kirua&token=flux&hours=912', 'News/copy/get9.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/retech.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_25&user=Gon1Kirua&token=flux&hours=48', 'News/copy/retech.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/reapp.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_23&user=Gon1Kirua&token=flux&hours=48', 'News/copy/reapp.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/rejeu.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_24&user=Gon1Kirua&token=flux&hours=48', 'News/copy/rejeu.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/reinfo.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_22&user=Gon1Kirua&token=flux&hours=48', 'News/copy/reinfo.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get4.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_4&user=Gon1Kirua&token=flux&hours=48', 'News/copy/get4.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get6.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_6&user=Gon1Kirua&token=flux&hours=48', 'News/copy/get6.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get5.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_5&user=Gon1Kirua&token=flux&hours=336', 'News/copy/get5.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get2.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_2&user=Gon1Kirua&token=flux&hours=48', 'News/copy/get2.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get10.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_10&user=Gon1Kirua&token=flux&hours=48', 'News/copy/get10.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/get3.xml') < (time() - 18000)) {
 @copy('https://iactu.info/recherche/p/i/?a=rss&get=c_3&user=Gon1Kirua&token=flux&hours=48', 'News/copy/get3.xml');
 @usleep(1000);
}
if (@filemtime('News/copy/xmltv_tnt.xml') < (time() - 72000)) {
 @copy('https://xmltvfr.fr/xmltv/xmltv_tnt.xml', 'News/copy/xmltv_tnt.xml');
}
