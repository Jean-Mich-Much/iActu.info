#!/usr/bin/env python3
import os,time,subprocess,random,gzip,shutil,pwd,grp
gz="/_/Fondation/cache/tv/tv.gz";xml="/_/Fondation/cache/tv/tv.xml";tmp="/_/Fondation/cache/tv/tv.tmp.xml";log="/_/Fondation/cache/tv/tv.log"
url="https://xmltvfr.fr/xmltv/xmltv_tnt.xml.gz"
ua="Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0"
hdr=[
"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
"Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.5,en;q=0.3",
"Accept-Encoding: gzip, deflate, br",
"Connection: keep-alive",
"DNT: 1",
"Upgrade-Insecure-Requests: 1",
"Referer: https://www.google.fr/"
]
def ok(p): return os.path.exists(p) and time.time()-os.path.getmtime(p)<3600
try:
    if not ok(xml):
        time.sleep(random.uniform(0.025,0.125))
        subprocess.run(["curl","-sL",url,"-A",ua,
        "-H",hdr[0],"-H",hdr[1],"-H",hdr[2],"-H",hdr[3],"-H",hdr[4],"-H",hdr[5],"-H",hdr[6],
        "--compressed","--max-time","5","--max-filesize","24000000","-o",gz],check=True)
        with gzip.open(gz,"rb") as i,open(tmp,"wb") as o: shutil.copyfileobj(i,o)
        v=os.path.getsize(tmp)>1_000_000
        with open(tmp,"r",encoding="utf-8",errors="ignore") as f:
            d=f.read(); v=v and d.count("</programme>")>=100 and d.count("</tv>")==1
        if v: shutil.copyfile(tmp,xml)
        else: raise Exception("validation")
        uid=pwd.getpwnam("caddy").pw_uid; gid=grp.getgrnam("caddy").gr_gid
        for f in (gz,tmp,xml):
            if os.path.exists(f): os.chmod(f,0o664); os.chown(f,uid,gid)
except Exception as e:
    with open(log,"a") as L: L.write(f"{time.ctime()} - {e}\n")
