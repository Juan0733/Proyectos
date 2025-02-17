from random import randint
from flask import Flask,  send_from_directory, render_template,request,redirect,send_from_directory,session, url_for, jsonify
from flask_cors import CORS
from mysql.connector import connect
from datetime import datetime
import os
import pytz

app = Flask(__name__)
CORS(app)
app.secret_key = str(randint(10000,99999))
db = connect(host = "localhost",
        port = "3306",
        user = "root",
        password = "",
        database = "bicicletas"
)

app.config['CARPETAU'] = os.path.join('uploads')



