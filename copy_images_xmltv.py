import os
import re
import requests
from lxml import etree
from datetime import datetime
from concurrent.futures import ThreadPoolExecutor
from PIL import Image
import dns.resolver
from functools import lru_cache
import sys

# 📁 Chemins / Paths
today_xml_file = f"/_/Structure/cache/tv/xmltv_{datetime.now().day}.xml"
backup_xml_file = "/_/Structure/cache/tv/xmltv_tnt_source.xml"
output_dir = "/_/Structure/cache/tv/images/cache/"
valid_formats = {"png", "jpg", "jpeg", "bmp", "avif", "webp", "heif", "svg", "gif"}
max_file_size = 1024 * 8192  # 8MB
min_file_size = 1024         # 1KB

# 🌐 En-têtes HTTP
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Connection": "keep-alive"
}

problematic_servers = set()

# ⏱️ DNS Resolver
dns_resolver = dns.resolver.Resolver()
dns_resolver.timeout = 15
dns_resolver.lifetime = 15

# 🧠 Cache DNS
@lru_cache(maxsize=512)
def resolve_dns_with_cache(hostname):
    return resolve_dns(hostname)

# ⏳ Vérifie si le programme dure plus de 18 min
def is_long_duration_program(start, stop):
    start_time = datetime.strptime(start, "%Y%m%d%H%M%S %z")
    stop_time = datetime.strptime(stop, "%Y%m%d%H%M%S %z")
    duration_minutes = (stop_time - start_time).total_seconds() / 60
    return duration_minutes >= 18

# 🧼 Nettoyage du nom de fichier
def sanitize_filename_with_extension(filepath):
    base_name, file_extension = os.path.splitext(filepath)
    sanitized_name = re.sub(r'[\s\-_]', '', base_name)
    converted_name = ''
    for char in sanitized_name.lower():
        if char.isalpha():
            converted_name += str(ord(char) - ord('a') + 1)
        elif char.isdigit():
            converted_name += char
        else:
            converted_name += '0'
    converted_name = converted_name[-16:].zfill(16)
    return converted_name + file_extension

# ✅ Vérifie si l'image est valide
def validate_image(file_path):
    try:
        with Image.open(file_path) as img:
            img.verify()
        return True
    except Exception:
        return False

# 🌍 Résolution DNS
def resolve_dns(hostname):
    try:
        ipv4_addresses = []
        try:
            answers_ipv4 = dns_resolver.resolve(hostname, "A")
            ipv4_addresses = [answer.address for answer in answers_ipv4]
        except dns.resolver.NoAnswer:
            pass

        ipv6_addresses = []
        try:
            answers_ipv6 = dns_resolver.resolve(hostname, "AAAA")
            ipv6_addresses = [answer.address for answer in answers_ipv6]
        except dns.resolver.NoAnswer:
            pass

        return ipv4_addresses + ipv6_addresses
    except (dns.resolver.NoAnswer, dns.resolver.NXDOMAIN, dns.exception.Timeout):
        problematic_servers.add(hostname)
        return []

# 🖼️ Traitement image
def process_image_to_webp(image_path, output_dir, base_name):
    try:
        with Image.open(image_path) as img:
            if img.mode != "RGB":
                img = img.convert("RGB")

            width, height = img.size
            target_ratio = 16 / 9
            current_ratio = width / height

            if current_ratio > target_ratio:
                new_width = int(height * target_ratio)
                left = (width - new_width) // 2
                img = img.crop((left, 0, left + new_width, height))
            else:
                new_height = int(width / target_ratio)
                top = (height - new_height) // 2
                img = img.crop((0, top, width, top + new_height))

            img = img.resize((192, 108), Image.LANCZOS)
            webp_path = os.path.join(output_dir, base_name + ".webp")
            img.save(webp_path, "WEBP")

        os.remove(image_path)
    except Exception:
        pass

# 📥 Téléchargement et validation
def download_and_validate_image(link):
    hostname = None
    try:
        hostname = link.split("/")[2]
        if hostname in problematic_servers:
            return

        resolved_addresses = resolve_dns_with_cache(hostname)
        if not resolved_addresses:
            return

        original_filename = link.split("/")[-1]
        file_extension = os.path.splitext(original_filename)[-1][1:].lower()
        if file_extension not in valid_formats:
            return

        filename = sanitize_filename_with_extension(original_filename)
        base_name = os.path.splitext(filename)[0]

        for existing_file in os.listdir(output_dir):
            if os.path.splitext(existing_file)[0] == base_name:
                return

        tmp_path = os.path.join(output_dir, base_name + "_tmp." + file_extension)

        head_response = requests.head(link, headers=headers, timeout=15)
        if "Content-Length" in head_response.headers:
            file_size = int(head_response.headers["Content-Length"])
            if file_size < min_file_size or file_size > max_file_size:
                return

        response = requests.get(link, headers=headers, stream=True, timeout=15)
        if response.status_code == 200:
            with open(tmp_path, "wb") as tmp_file:
                for chunk in response.iter_content(8 * 1024):
                    if chunk:
                        tmp_file.write(chunk)

            local_size = os.path.getsize(tmp_path)
            if local_size < min_file_size or local_size > max_file_size:
                os.remove(tmp_path)
                return

            if validate_image(tmp_path):
                final_path = os.path.join(output_dir, filename)
                os.rename(tmp_path, final_path)
                process_image_to_webp(final_path, output_dir, base_name)
            else:
                os.remove(tmp_path)
    except requests.exceptions.RequestException:
        if hostname:
            problematic_servers.add(hostname)
    except Exception:
        return

# 📂 Crée le dossier cache
os.makedirs(output_dir, exist_ok=True)

# 📄 Choix du fichier XML
xmltv_file = today_xml_file if os.path.exists(today_xml_file) else backup_xml_file

try:
    with open(xmltv_file, "rb") as file:
        tree = etree.parse(file)
        root = tree.getroot()
except FileNotFoundError:
    sys.exit(1)

# 🔍 Extraction des liens d'image
programs = root.xpath("//programme")
image_links = []

for program in programs:
    start = program.get("start")
    stop = program.get("stop")
    icon = program.find("icon")
    if icon is not None and icon.get("src") and is_long_duration_program(start, stop):
        image_links.append(icon.get("src"))

# ⚙️ Téléchargement parallèle
with ThreadPoolExecutor(max_workers=8) as executor:
    executor.map(download_and_validate_image, image_links)

# 🔁 Traitement des fichiers déjà présents
for file in os.listdir(output_dir):
    file_path = os.path.join(output_dir, file)
    base_name, ext = os.path.splitext(file)
    if ext.lower() != ".webp":
        process_image_to_webp(file_path, output_dir, base_name)
