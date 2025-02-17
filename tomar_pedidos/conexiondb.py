from flask import Flask, request, jsonify
from flask_cors import CORS
from mysql.connector import connect

app = Flask(__name__)
CORS(app)

conexion = connect(
    host = 'localhost', 
    port = '3306',
    user = 'Juan',
    password = '00000',
    database = 'tomaPedido'
)                   
                   






    









    
