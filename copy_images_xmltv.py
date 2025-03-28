import os
import re
import requests
from lxml import etree
from datetime import datetime, timezone
from concurrent.futures import ThreadPoolExecutor
from PIL import Image
import dns.resolver  # Import du resolver dnspython
from functools import lru_cache  # Import pour le cache DNS

# Chemins
today_xml_file = f"/_/Structure/cache/tv/xmltv_{datetime.now().day}.xml"
backup_xml_file = "/_/Structure/cache/tv/xmltv_tnt_source.xml"
output_dir = "/_/Structure/cache/tv/images/cache/"
valid_formats = {"png", "jpg", "jpeg", "bmp", "avif", "webp"}
max_file_size = 1024 * 1024  # Taille maximale de 1024 Ko
min_file_size = 4096         # Taille minimale de 4096 octets
headers = {"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:112.0) Gecko/20100101 Firefox/112.0",
           "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
           "Accept-Encoding": "gzip, deflate",
           "Connection": "keep-alive"}
problematic_servers = set()

# Initialiser le resolver DNS avec un timeout
dns_resolver = dns.resolver.Resolver()
dns_resolver.timeout = 5  # Limite de temps par requête DNS
dns_resolver.lifetime = 5  # Limite de temps totale pour chaque résolution

# Cache DNS
@lru_cache(maxsize=512)  # Cache jusqu'à 512 hôtes résolus
def resolve_dns_with_cache(hostname):
    return resolve_dns(hostname)

def is_long_duration_program(start, stop):
    start_time = datetime.strptime(start, "%Y%m%d%H%M%S %z")
    stop_time = datetime.strptime(stop, "%Y%m%d%H%M%S %z")
    duration_minutes = (stop_time - start_time).total_seconds() / 60

    # Critère : durée minimale de 19 minutes
    return duration_minutes >= 19

def sanitize_filename_with_extension(filepath):
    base_name, file_extension = os.path.splitext(filepath)
    sanitized_name = re.sub(r'[\s\-_]', '', base_name)  # Enlever espaces, tirets, underscores
    converted_name = ''
    for char in sanitized_name.lower():
        if char.isalpha():
            converted_name += str(ord(char) - ord('a') + 1)
        elif char.isdigit():
            converted_name += char
        else:
            converted_name += '0'  # Ignorer les caractères non alphanumériques
    converted_name = converted_name[-16:]  # Limiter à 16 caractères à partir de la fin
    converted_name = converted_name.zfill(16)  # Compléter avec des zéros
    return converted_name + file_extension

def validate_image(file_path):
    try:
        with Image.open(file_path) as img:
            img.verify()
        return True
    except Exception:
        return False

def resolve_dns(hostname):
    try:
        # Étape 1 : Résolution des adresses IPv4
        ipv4_addresses = []
        try:
            answers_ipv4 = dns_resolver.resolve(hostname, "A")
            ipv4_addresses = [answer.address for answer in answers_ipv4]
        except dns.resolver.NoAnswer:
            pass

        # Étape 2 : Résolution des adresses IPv6
        ipv6_addresses = []
        try:
            answers_ipv6 = dns_resolver.resolve(hostname, "AAAA")
            ipv6_addresses = [answer.address for answer in answers_ipv6]
        except dns.resolver.NoAnswer:
            pass

        # Combiner IPv4 et IPv6 et retourner le résultat
        return ipv4_addresses + ipv6_addresses
    except (dns.resolver.NoAnswer, dns.resolver.NXDOMAIN, dns.exception.Timeout):
        problematic_servers.add(hostname)
        return []

def download_and_validate_image(link):
    try:
        hostname = link.split("/")[2]
        if hostname in problematic_servers:
            return

        # Résolution DNS avec cache
        resolved_addresses = resolve_dns_with_cache(hostname)
        if not resolved_addresses:
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

        # Vérifier la taille avec Content-Length
        head_response = requests.head(link, headers=headers, timeout=8)
        if "Content-Length" in head_response.headers:
            file_size = int(head_response.headers["Content-Length"])
            if file_size < min_file_size or file_size > max_file_size:
                return  # Ignorer les fichiers trop petits ou trop grands

        # Télécharger et vérifier localement
        response = requests.get(link, headers=headers, stream=True, timeout=8)
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
    if icon is not None and is_long_duration_program(start, stop):
        image_links.append(icon.get("src"))

with ThreadPoolExecutor(max_workers=8) as executor:
    executor.map(download_and_validate_image, image_links)
