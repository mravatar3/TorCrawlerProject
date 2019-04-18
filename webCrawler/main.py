import threading
from queue import Queue
from spider import Spider
from domain import *
from general import *
import mysql.connector

PROJECT_NAME = 'startpagina'
HOMEPAGE = 'http://startpagina.nl/'
DOMAIN_NAME = get_domain_name(HOMEPAGE)
NUMBER_OF_THREADS = 12
queue = Queue()
Spider(PROJECT_NAME, HOMEPAGE, DOMAIN_NAME)

# Create worker threads (will die when main exits)
def create_workers():
    for _ in range(NUMBER_OF_THREADS):
        t = threading.Thread(target=work)
        t.daemon = True
        t.start()


# Do the next job in the queue
def work():
    while True:
        url = queue.get()
        Spider.crawl_page(threading.current_thread().name, url)
        queue.task_done()


# Each queued link is a new job
def create_jobs():
    mySQLConnection = mysql.connector.connect(
        database="project",
        host="192.168.0.33",
        user="projectUser",
        passwd="Anusklep20!",
        auth_plugin='mysql_native_password'
    )

    sqlTest = "select link from project.queue"
    cursor = mySQLConnection.cursor()
    cursor.execute(sqlTest)
    wachtrijURL = [item[0] for item in cursor.fetchall()]
    mySQLConnection.close()
    cursor.close()

    for link in wachtrijURL:
        queue.put(link)
    queue.join()
    crawl()


# Check if there are items in the queue, if so crawl them
def crawl():
    mySQLConnection = mysql.connector.connect(
        database="project",
        host="192.168.0.33",
        user="projectUser",
        passwd="Anusklep20!",
        auth_plugin='mysql_native_password'
    )

    sqlTest = "select link from project.queue"
    cursor = mySQLConnection.cursor()
    cursor.execute(sqlTest)
    wachtrijURL = [item[0] for item in cursor.fetchall()]
    mySQLConnection.close()
    cursor.close()

    queued_links = wachtrijURL
    if len(queued_links) > 0:
        print(str(len(queued_links)) + ' links in the queue')
        create_jobs()


create_workers()
crawl()
