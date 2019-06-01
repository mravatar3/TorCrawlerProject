import urllib.request
from link_finder import LinkFinder
from domain import *
import mysql.connector
from bs4 import BeautifulSoup
import re
import sys
import time

class Spider:
    project_name = ''
    base_url = str(sys.argv[1])
    domain_name = ''
    queue_file = ''
    crawled_file = ''
    queue = set()
    crawled = set()
    database = "project"
    host = "192.168.0.33"
    user = "projectUser"
    passwd = "Anusklep20!"
    proxy_support = urllib.request.ProxyHandler({'http': 'http://127.0.0.1:8118',
                                                 'https': 'https://127.0.0.1:8118'})

    def __init__(self, base_url, domain_name):
        Spider.base_url = base_url
        Spider.domain_name = domain_name
        self.boot()
        self.crawl_page('Eerste crawler: ', Spider.base_url)

    @staticmethod
    def boot():
        print('De banenplukkers worden gestart (workers)')

    @staticmethod
    def crawl_page(thread_name, page_url):
        try:
            # hier controleren of de pagina URL niet al in de cralwed tabel staat van MySQL anders krijgen we dubbelen
            mysqlConnect = mysql.connector.connect(database=Spider.database, host=Spider.host, user=Spider.user,
                                                   passwd=Spider.passwd, auth_plugin='mysql_native_password')
            sqlTest = "select link from project.crawled WHERE link = '" + page_url + "';"
            cursor = mysqlConnect.cursor()
            cursor.execute(sqlTest)
            alCrawled = cursor.fetchone()
            cursor.close()

            if alCrawled:
                print(thread_name + 'Pagina is al gecrawled drop! do nothing')
            else:
                print(thread_name + ' bezig met crawlen van: ' + page_url)
                # print('Wachtrij: ' + str(len(Spider.queue)) + ' | Crawled:  ' + str(len(Spider.crawled))) Deze eruit want duurt te lang
                Spider.add_links_to_queue(Spider.gather_links(page_url))

                response = urllib.request.build_opener(Spider.proxy_support)
                urllib.request.install_opener(response)
                with urllib.request.urlopen(page_url) as response:
                    if 'text/html' in response.getheader('Content-Type'):
                        html_bytes = response.read()
                        html = html_bytes.decode("utf-8")
                        match = re.search('<title>(.*?)</title>', html)
                        title = match.group(1) if match else 'No title'

                # Hier vullen we de MySQL crawled tabel met links die verwerkt zijn
                sql_insert_query = "INSERT INTO project.crawled(link, content, date, title) VALUES ('"+page_url+"', "+repr(Spider.text_grabber(page_url))+", '"+time.strftime('%Y-%m-%d %H:%M:%S')+"', '"+title+"');"
                cursor = mysqlConnect.cursor()
                cursor.execute(sql_insert_query)
                mysqlConnect.commit()
                cursor.close()
        except:
            print("Fout opgetreden tijdens crawlen")

        # Hier halen we de queue link uit de MySQL queue tabel
        # Spider.queue.remove(page_url)
        sql_delete_query = "DELETE FROM project.queue WHERE link = '" + page_url + "';"
        cursor = mysqlConnect.cursor()
        cursor.execute(sql_delete_query)
        mysqlConnect.commit()
        cursor.close()


    # Converts raw response data into readable information and checks for proper html formatting
    @staticmethod
    def gather_links(page_url):
        html_string = ''
        try:
            response = urllib.request.build_opener(Spider.proxy_support)
            urllib.request.install_opener(response)
            with urllib.request.urlopen(page_url) as response:
                if 'text/html' in response.getheader('Content-Type'):
                    html_bytes = response.read()
                    html_string = html_bytes.decode("utf-8")
            finder = LinkFinder(Spider.base_url, page_url)
            finder.feed(html_string)
        except Exception as e:
            print(str(e))
            return set()
        return finder.page_links()

    # Saves queue data to project files
    @staticmethod
    def add_links_to_queue(links):
        try:
            for url in links:
                if (url in Spider.queue) or (url in Spider.crawled):
                    continue
                if Spider.domain_name != get_domain_name(url):
                    continue
                Spider.queue.add(url)

                # Hier vullen we de MySQL queue tabel met nieuwe linksjes aan de wachtrij
                mysqlConnect = mysql.connector.connect(database=Spider.database, host=Spider.host, user=Spider.user,
                                                       passwd=Spider.passwd, auth_plugin='mysql_native_password')
                sql_insert_query = "INSERT INTO queue(link) VALUES ('"+url+"');"
                cursor = mysqlConnect.cursor()
                cursor.execute(sql_insert_query)
                mysqlConnect.commit()
                cursor.close()
                mysqlConnect.close()
        except:
            print("Fout opgetreden")

    @staticmethod
    def text_grabber(paginaurl):
        try:
            response = urllib.request.build_opener(Spider.proxy_support)
            urllib.request.install_opener(response)
            with urllib.request.urlopen(paginaurl) as response:
                if 'text/html' in response.getheader('Content-Type'):
                    html_bytes = response.read()
                    html = html_bytes.decode("utf-8")

            cleanhtml = Spider.clean_html(html)
            zonderspaties = " ".join(re.split(r"\s+", cleanhtml))
            tekstdb = BeautifulSoup(zonderspaties, "lxml")
            return(tekstdb.get_text())
        except:
            print("Fout opgetreden tijdens text grabber")

    @staticmethod
    def clean_html(html):
        try:
            # First we remove inline JavaScript/CSS:
            cleaned = re.sub(r"(?is)<(script|style).*?>.*?(</\1>)", "", html.strip())
            # Then we remove html comments. This has to be done before removing regular
            # tags since comments can contain '>' characters.
            cleaned = re.sub(r"(?s)<!--(.*?)-->[\n]?", "", cleaned)
            # Next we can remove the remaining tags:
            cleaned = re.sub(r"(?s)<.*?>", " ", cleaned)
            # Finally, we deal with whitespace
            cleaned = re.sub(r"&nbsp;", " ", cleaned)
            cleaned = re.sub(r"  ", " ", cleaned)
            cleaned = re.sub(r"  ", " ", cleaned)
            return cleaned.strip()
        except:
            print("Fout opgetreden tijdens schoonmaken HTML")