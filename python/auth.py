from flask import Flask, request
from flask.ext.api import status
import psycopg2
import config

app = Flask(__name__)

@app.route('/auth')
def auth():
	if request.args.get('name') is None or request.args.get('swfurl') is None:
		message = 'Malformed request'
		return message, status.HTTP_400_BAD_REQUEST
	
	username = request.args.get('name')
	idhash = request.args.get('swfurl').split("?")[-1]
	
	conn = psycopg2.connect(database=config.database, user=config.user, password=config.password, host=config.host)
	cur = conn.cursor()
	cur.execute("SELECT FROM %s WHERE username=%s AND idhash=%s", (config.usertablename, username, idhash))
	
	if len(cur.fetchall()) == 0:
		return 'Incorrect credentials', status.HTTP_401_UNAUTHORIZED

	return 'OK', status.HTTP_200_OK

if __name__== '__main__':
	app.run()
