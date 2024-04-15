cd /_/News/copy
wget -O "xmltv_tnt.zip" "https://xmltvfr.fr/xmltv/xmltv_tnt.zip" -q --limit-rate=4096k --no-check-certificate
sleep 0.5
unzip xmltv_tnt.zip
rm -f xmltv_tnt.zip > /dev/null 2>&1
