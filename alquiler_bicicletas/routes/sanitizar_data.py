from conexion import *
from routes.usuarios import valida_login
from routes.usuarios import agregar_usuario
from routes.bicicletas import agregar_bicicleta
from routes.eventos import agregar_evento
import re
import hashlib


@app.route('/sanitizarCredenciales', methods=['POST'])
def sanitizar_credenciales():
    if request.method != 'POST':
        return redirect('/')
    else:
        #Validar usuario
        regex = r'^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,}$'
        if not re.search(regex, request.get_json()['correo']):
            correo = 0
        else: 
            correo = request.json['correo']
           
        #Validar contrasena
        regex = r'^[a-zA-Z0-9]{2,24}$'
        if not re.search(regex, request.get_json()['contrasena']):
            contrasena = 0
        else:
            contrasena = hashlib.sha512(request.json['contrasena'].encode('utf-8')).hexdigest()

        credenciales = {
            'correo': correo,
            'contrasena': contrasena
        }

        if not valida_login(credenciales):
            respuesta = {
                'titulo': "ERROR",
                'texto': 'El usuario o Contraseña son incorrectos.',
                'codigo': '100'
            }
        else:
            respuesta = {
                'titulo': "OK",
                'texto': "Operacion exitosa",
                'codigo': "200"
            }

        return jsonify(respuesta)
    

@app.route('/sanitizarInfoUsuario', methods=['POST'])
def sanitizar_info_usuario():
    if request.method != 'POST':
        return redirect('/')
    else:
        formmulario_valido = True

        #Validar nombres
        regex = r'^[a-zA-ZñÑ ]{2,24}$'
        if not re.search(regex, request.get_json()['nombres']):
            formmulario_valido = False
        else:
            nombres = request.json['nombres']

        #Validar apellidos
        regex = r'^[a-zA-ZñÑ ]{2,24}$'
        if not re.search(regex, request.get_json()['apellidos']):
            formmulario_valido = False
        else:
            apellidos = request.json['apellidos']

        #Validar estrato
        regex = r'^[1-6]{1}$'
        if not re.search(regex, request.get_json()['estrato']):
            formmulario_valido = False
        else:
            estrato = request.get_json()['estrato']

        #Validar usuario
        regex = r'^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,}$'
        if not re.search(regex, request.get_json()['correo']):
            formmulario_valido = False
        else: 
            correo = request.json['correo']
           
        #Validar contrasena
        regex = r'^[0-9a-zA-Z@#-_$&]{5,64}$'
        if not re.search(regex, request.get_json()['contrasena']):
            formmulario_valido = False
        else:
            contrasena = hashlib.sha512(request.json['contrasena'].encode('utf-8')).hexdigest()

        if not formmulario_valido:
            respuesta = {
                'titulo': 'ERROR',
                'texto': 'Los datos ingresados no cumplen con la estructura requerida',
                'codigo': '100'
            }
        else:
            datos = {
                'nombres': nombres,
                'apellidos': apellidos,
                'estrato': estrato,
                'correo': correo,
                'contrasena': contrasena
            }
            if not agregar_usuario(datos):
                respuesta = {
                    'titulo': 'ERROR',
                    'texto': 'El usuario ya se encuentra registrado. Inténtalo nuevamente.',
                    'codigo': "100"
                }
            else:
                respuesta = {
                    'titulo': 'OK',
                    'texto': 'Usuario registrado exitosamente.',
                    'codigo': "200"
                }

        
        return jsonify(respuesta)
    
@app.route('/sanitizarInfoBici', methods=['POST'])
def sanitizar_bici():
    if request.method != 'POST':
        return redirect('/')
    else:
        formmulario_valido = True

        #Validar marca
        regex = r'^[a-zA-ZñÑ0-9 ]{2,30}$'
        if not re.search(regex, request.form['marca']):
            formmulario_valido = False
        else:
            marca = request.form['marca']

        #Validar color
        colores = ['Rojo', 'Verde', 'Naranja', 'Amarillo', 'Azul', 'Blanco', 'Negro', 'Púrpura', 'Rosado']
        if not request.form['color'] in colores:
            formmulario_valido = False
        else:
            color = request.form['color']

        #Validar precio
        regex = r'^[0-9]{3,}$'
        if not re.search(regex, request.form['precio']):
            formmulario_valido = False
        else:
            precio = request.form['precio']

        #Validar foto
        if request.files['foto'].filename == "":
            formmulario_valido = False
        else: 
            foto = request.files['foto']
           
        #Validar regional
        regex = r'^[0-9]{1,2}$'
        if not re.search(regex, request.form['regional']):
            formmulario_valido = False
        else:
            regional = request.form['regional']

        if not formmulario_valido:
            respuesta = {
                'titulo': 'ERROR',
                'texto': 'Los datos ingresados no cumplen con la estructura requerida',
                'codigo': '100'
            }
        else:
            datos = {
                'marca': marca,
                'color': color,
                'precio': precio,
                'foto': foto,
                'regional': regional
            }
            if not agregar_bicicleta(datos):
                respuesta = {
                    'titulo': 'ERROR',
                    'texto': 'No se pudo realizar la operacion, intentelo nuevamente.',
                    'codigo': "100"
                }
            else:
                respuesta = {
                    'titulo': 'OK',
                    'texto': 'Se registro la bicicleta exitosamente.',
                    'codigo': "200"
                }

        
        return jsonify(respuesta)

        

@app.route('/sanitizarEvent', methods=['POST'])
def sanitizar_event():
    if request.method != 'POST':
        return redirect('/')
    else:
        formmulario_valido = True

        #Validar nombre
        regex = r'^[a-zA-ZñÑ0-9 ]{2,30}$'
        if not re.search(regex, request.form['nombre']):
            formmulario_valido = False
        else:
            nombre = request.form['nombre']

        #Validar descripcion
        regex = r'^[a-zA-ZñÑ0-9 ]{2,100}$'
        if not re.search(regex, request.form['descripcion']):
            formmulario_valido = False
        else:
            descripcion = request.form['descripcion']

        #Validar lugar
        regex = r'^[a-zA-ZñÑ0-9 ]{2,30}$'
        if not re.search(regex, request.form['lugar']):
            formmulario_valido = False
        else:
            lugar = request.form['lugar']

        #Validar fecha
        ahora = datetime.now()
        if not datetime.strptime(request.form['fecha'], '%Y-%m-%d %H:%M:%S') > ahora:
            formmulario_valido = False
        else:
            fecha = request.form['fecha']

        #Validar foto
        if request.files['foto_evento'].filename == "":
            formmulario_valido = False
        else: 
            foto = request.files['foto_evento']
           
        

        if not formmulario_valido:
            respuesta = {
                'titulo': 'ERROR',
                'texto': 'Los datos ingresados no cumplen con la estructura requerida',
                'codigo': '100'
            }
        else:
            datos = {
                'nombre': nombre,
                'descripcion': descripcion,
                'lugar': lugar,
                'fecha': fecha,
                'foto': foto
            }
            if not agregar_evento(datos):
                respuesta = {
                    'titulo': 'ERROR',
                    'texto': 'No se pudo realizar la operacion, intentelo nuevamente.',
                    'codigo': "100"
                }
            else:
                respuesta = {
                    'titulo': 'OK',
                    'texto': 'Se registro el evento exitosamente.',
                    'codigo': "200"
                }

        
        return jsonify(respuesta)

        
