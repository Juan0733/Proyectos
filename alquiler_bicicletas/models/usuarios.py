import random
import string
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import os
from dotenv import load_dotenv
from conexion import *

class Usuarios:
    def __init__(self, db):
        self.db = db
        self.cursor = db.cursor()

    def consultar_usuario(self, correo, contrasena=False):
        if not contrasena:
            sql = f'SELECT nombres FROM usuarios WHERE correo = "{correo}";'
            self.cursor.execute(sql)
            usuario = self.cursor.fetchall()
            if not len(usuario) > 0:
                sql = f'SELECT nombres FROM administradores WHERE correo = "{correo}";'
                self.cursor.execute(sql)
                usuario = self.cursor.fetchall()
                if not len(usuario) > 0:
                    return False
                else:
                   return usuario[0][0] 
            else:
                return usuario[0][0]
        else:
            print(contrasena)        
            sql = f'SELECT pk_usuario, nombres, correo, estrato  FROM usuarios WHERE correo = "{correo}" AND contrasena = "{contrasena}";'
            self.cursor.execute(sql)
            usuario = self.cursor.fetchall()
            if not len(usuario) > 0:
                sql = f'SELECT nombres FROM administradores WHERE correo = "{correo}" AND contrasena = "{contrasena}";'
                self.cursor.execute(sql)
                usuario = self.cursor.fetchall()
                if not len(usuario) > 0:
                    return False
                else:
                    datos_usuario = {
                        'nombres': usuario[0][0],
                        'rol': 'Administrador'
                    }

                    return datos_usuario
                    
            else:
                datos_usuario = {
                    'id': usuario[0][0],
                    'nombres': usuario[0][1],
                    'correo': usuario[0][2],
                    'estrato': usuario[0][3],
                    'rol': 'Usuario'
                }
                            
                return datos_usuario
        
    def agregar_usuario(self, datos):
        sql = f"INSERT INTO usuarios(nombres, apellidos, estrato, correo, contrasena) VALUES ('{datos['nombres']}', '{datos['apellidos']}', '{datos['estrato']}', '{datos['correo']}', '{datos['contrasena']}');"
        self.cursor.execute(sql)
        self.db.commit()
    

    def enviar_correo(self, destinatario, codigo):
        
        remitente = os.getenv('USER')
        asunto = 'Código de verificación'
        mensaje = f'Tu código de verificación es: {codigo}'

        msg = MIMEMultipart()
        msg['Subject'] = asunto
        msg['From'] = remitente
        msg['To'] = destinatario
        msg.attach(MIMEText(mensaje, 'plain'))

        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login(remitente, os.getenv('PASS'))
        server.sendmail(remitente, destinatario, msg.as_string())
        server.quit()

        return True
    
usuario = Usuarios(db)



    