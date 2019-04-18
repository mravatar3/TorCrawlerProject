import mysql.connector

mySQLConnection = mysql.connector.connect(
    database="project",
    host="192.168.0.33",
    user="projectUser",
    passwd="Anusklep20!",
    auth_plugin='mysql_native_password'
)

sqlTest = "select link from project.queue LIMIT 1"
cursor = mySQLConnection.cursor()
cursor.execute(sqlTest)
data = cursor.fetchone()
url = ''.join(data)