from conexion import *
from models.usuarios import usuario

@app.route('/validaLogin')
def valida_login(credenciales):
    respuesta = usuario.consultar_usuario(credenciales['correo'], credenciales['contrasena'])
    if not respuesta:
        return False
    else:
        session['usuario'] = respuesta
        session['login'] = True 
        return True

@app.route('/registrarUsuario')
def registrar():
    return render_template('registrarUsuario.html')

@app.route('/agregarUsuario', methods=['POST'])
def agregar_usuario(datos):
    if not usuario.consultar_usuario(datos['correo']):
        usuario.agregar_usuario(datos)
        return True
    return False


