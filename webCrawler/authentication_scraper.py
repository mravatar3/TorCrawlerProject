import requests
from bs4 import BeautifulSoup
import html5lib

login_url = 'http://192.168.0.33/login.php'
url = 'http://192.168.0.33/login.php'

username = 'root'
password = 'Anusklep20!'

payload = {'username': username, 'password': password}

session = requests.Session()
post = session.post(login_url, payload)
req = session.get(url)

html = req.text
soup = BeautifulSoup(html, 'html5lib')