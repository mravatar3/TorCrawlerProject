from urllib.request import urlopen
from link_finder import LinkFinder
from domain import *
from general import *
import mysql.connector

class Spider:
    project_name = ''
    base_url = 'https://startpagina.nl'
    domain_name = ''
    queue_file = ''
    crawled_file = ''
    queue = set()
    crawled = set()
    database = "project"
    host = "192.168.0.33"
    user = "projectUser"
    passwd = "Anusklep20!"

    def __init__(self, project_name, base_url, domain_name):
        Spider.project_name = project_name
        Spider.base_url = base_url
        Spider.domain_name = domain_name
        self.boot()
        self.crawl_page('First spider', Spider.base_url)

    # Creates directory and files for project on first run and starts the spider
    @staticmethod
    def boot():
        create_project_dir(Spider.project_name)
        create_data_files(Spider.project_name, Spider.base_url)


    # Updates user display, fills queue and updates files
    @staticmethod
    def crawl_page(thread_name, page_url):
        # hier controleren of de pagina URL niet al in de cralwed tabel staat van MySQL anders krijgen we dubbelen

        mysqlConnect = mysql.connector.connect(database=Spider.database, host=Spider.host, user=Spider.user,
                                               passwd=Spider.passwd, auth_plugin='mysql_native_password')
        sqlTest = "select link from project.crawled WHERE link = '" + page_url + "';"
        cursor = mysqlConnect.cursor()
        cursor.execute(sqlTest)
        alCrawled = cursor.fetchone()
        cursor.close()

        if alCrawled:
            print('Pagina is al gecrawled drop! do nothing')
        else:
            print(thread_name + ' now crawling ' + page_url)
            print('Queue ' + str(len(Spider.queue)) + ' | Crawled  ' + str(len(Spider.crawled)))
            Spider.add_links_to_queue(Spider.gather_links(page_url))

            # Hier vullen we de MySQL crawled tabel met links die verwerkt zijn
            sql_insert_query = "INSERT INTO project.crawled(link) VALUES ('"+page_url+"');"
            cursor = mysqlConnect.cursor()
            cursor.execute(sql_insert_query)
            mysqlConnect.commit()
            cursor.close()

        # Hier halen we de queue link uit de MySQL queue tabel
        Spider.queue.remove(page_url)
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
            response = urlopen(page_url)
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
