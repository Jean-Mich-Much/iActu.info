import os
import re
import requests
from lxml import etree
from datetime import datetime, timedelta, timezone
from concurrent.futures import ThreadPoolExecutor
from PIL import Image

# Chemins
today_xml_file = f"/_/Structure/cache/tv/xmltv_{datetime.now().day}.xml"
backup_xml_file = "/_/Structure/cache/tv/xmltv_tnt_source.xml"
output_dir = "/_/Structure/cache/tv/images/cache/"
valid_formats = {"png", "jpg", "jpeg", "bmp", "avif", "webp"}
max_file_size = 512 * 1024  # Taille maximale de 512 Ko
min_file_size = 512         # Taille minimale de 512 octets
headers = {"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:112.0) Gecko/20100101 Firefox/112.0",
"Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
"Accept-Encoding": "gzip, deflate",
"Connection": "keep-alive"}
problematic_servers = set()

def is_prime_time_program(start, stop, now):
 now = now.replace(tzinfo=timezone.utc)
 start_time = datetime.strptime(start, "%Y%m%d%H%M%S %z")
 stop_time = datetime.strptime(stop, "%Y%m%d%H%M%S %z")
 duration_minutes = (stop_time - start_time).total_seconds() / 60
 today_14h = now.replace(hour=14, minute=0, second=0, microsecond=0)
 tomorrow_4h = (today_14h + timedelta(days=1)).replace(hour=4)
 next_day_14h = today_14h + timedelta(days=1)
 next_day_4h = tomorrow_4h + timedelta(days=1)
 if ((today_14h <= start_time < tomorrow_4h) or 
 (next_day_14h <= start_time < next_day_4h)) and duration_minutes > 14:
  return True
 return False

def sanitize_filename_with_extension(filepath):
 base_name,file_extension=os.path.splitext(filepath)
 sanitized_name=re.sub(r'[\s\-_]', '', base_name) # Enlever espaces, tirets, underscores
 converted_name=''
 for char in sanitized_name.lower():
  if char.isalpha():converted_name+=str(ord(char)-ord('a')+1)
  elif char.isdigit():converted_name+=char
  else:converted_name+='0' # Ignorer les caractères non alphanumériques
 converted_name=converted_name[-16:] # Limiter à 16 caractères à partir de la fin
 converted_name=converted_name.zfill(16) # Compléter avec des zéros
 return converted_name+file_extension

def validate_image(file_path):
 try:
  with Image.open(file_path) as img:
   img.verify()
  return True
 except Exception:
  return False

def download_and_validate_image(link):
 try:
  hostname = link.split("/")[2]
  if hostname in problematic_servers:
   return
  original_filename = link.split("/")[-1]
  file_extension = os.path.splitext(original_filename)[-1][1:].lower()
  if file_extension not in valid_formats:
   return
  filename = sanitize_filename_with_extension(original_filename)
  final_path = os.path.join(output_dir, filename)
  if os.path.exists(final_path):
   return
  tmp_path = final_path + "_tmp"
  
  # Étape 1 : Vérifier la taille avec Content-Length
  head_response = requests.head(link, headers=headers, timeout=10)
  if "Content-Length" in head_response.headers:
   file_size = int(head_response.headers["Content-Length"])
   if file_size < min_file_size or file_size > max_file_size:
    return  # Ignorer les fichiers trop petits ou trop grands
  else:
   pass  # Continuer si l'information n'est pas disponible

  # Étape 2 : Télécharger et vérifier localement
  response = requests.get(link, headers=headers, stream=True, timeout=10)
  if response.status_code == 200:
   with open(tmp_path, "wb") as tmp_file:
    for chunk in response.iter_content(8 * 1024):
     if chunk:
      tmp_file.write(chunk)
   local_size = os.path.getsize(tmp_path)  # Vérifier la taille locale
   if local_size < min_file_size or local_size > max_file_size:
    os.remove(tmp_path)  # Supprimer le fichier hors limites
    return
   if validate_image(tmp_path):
    os.rename(tmp_path, final_path)
   else:
    os.remove(tmp_path)
  else:
   return
 except requests.exceptions.RequestException:
  problematic_servers.add(hostname)
  return
 except Exception:
  return

# Créer uniquement le dossier de cache si inexistant
os.makedirs(output_dir, exist_ok=True)

# Vérifier quel fichier XML utiliser
xmltv_file = today_xml_file if os.path.exists(today_xml_file) else backup_xml_file

try:
 with open(xmltv_file, "rb") as file:
  tree = etree.parse(file)
  root = tree.getroot()
except FileNotFoundError:
 print(f"Erreur : Aucun fichier XML valide trouvé.")
 exit()

now = datetime.now()
programs = root.xpath("//programme")
image_links = []

for program in programs:
 start = program.get("start")
 stop = program.get("stop")
 icon = program.find("icon")
 if icon is not None and is_prime_time_program(start, stop, now):
  image_links.append(icon.get("src"))

with ThreadPoolExecutor(max_workers=16) as executor:
 executor.map(download_and_validate_image, image_links)
