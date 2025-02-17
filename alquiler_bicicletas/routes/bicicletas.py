from conexion import *
from models.bicicletas import bicicleta

@app.route('/mostrarBicicletas', methods=['GET'])
def mostrar_bicicletas():
    if session.get('login') == True:
        bicicletas = bicicleta.consultar_bicicletas('disponible')
        regiones = bicicleta.consultar_regiones()
        if not bicicletas:
            msg = "!No hay bicicletas disponibles¡"
            return render_template('bicicletas.html', msg=msg, regiones=regiones )
        else: 
            return render_template("bicicletas.html", bicicletas=bicicletas, regiones=regiones)
    else:
        return redirect('/')

@app.route('/consultarBicicletas/<estado>/<region>', methods=['GET'])
def consultar_bicicletas(estado, region):
    if session.get('login') == True:
        if region == 'false':
            region = False
        if not session['usuario']['rol'] == 'Usuario':
            usuario = False
        else:
            usuario = session['usuario']['id']
        
        bicicletas = bicicleta.consultar_bicicletas(estado, region, usuario)

        if not bicicletas:
            respuesta = {"titulo": "ERROR", "texto": "!No se encontraron resultados¡"}
        else: 
            respuesta = {"titulo": "OK", "rol": session["usuario"]["rol"], "bicicletas": bicicletas}
            
        return jsonify(respuesta)
    else:
        return redirect('/')

@app.route('/agregarBicicleta', methods=['POST'])
def agregar_bicicleta(datos):
    bicicleta.agregar_bicicleta(datos)
    return True
    

@app.route('/consultarBicicleta/<id_biciceta>', methods=['GET'])
def consultar_bicicleta(id_bicicleta):
    if session.get('login') == True and session.get('usuario')['rol'] == 'Administrador':
        datos_bicicleta = bicicleta.consultar_bicicleta(id_bicicleta)
        respuesta = {'titulo': 'OK', 'datos': datos_bicicleta}
        return jsonify(respuesta)
    else:
        return redirect('/')
    
@app.route('/actualizarBicicleta', methods=['POST'])
def actualizar_bicicleta():
    datos = request.get_json()
    print(datos)
    bicicleta.actualizar_bicicleta(datos)

    respuesta = {'titulo': 'OK',
                 'texto': '¡La bicicleta se actualizo exitosamente!'}
    return jsonify(respuesta)

@app.route('/eliminarBicicleta/<id>', methods=['GET'])
def eliminar_bicicleta(id):
    if session.get('login')  == True and session.get('usuario')['rol'] == 'Administrador':
        bicicleta.eliminar_bicicleta(id)
        return redirect('/mostrarBicicletas')
    else:
        return redirect('/')

@app.route('/alquilarBicicleta/<id_bicicleta>', methods=['GET'])
def alquilar_bicicleta(id_bicicleta):
    if session.get('login') == True:
        bicicleta.registrar_alquiler(id_bicicleta, session['usuario']['id'])
        respuesta = {'titulo': 'OK', 'texto': '¡La bicicleta ha sido alquilada con exitó, recuerda de volverla!'}
        return jsonify(respuesta)
    else:
        return redirect('/')

@app.route('/devolucionBicicleta/<id_bicicleta>', methods=['GET'])
def devolucion_bicicleta(id_bicicleta):
    if session.get('login') == True:
        datos = bicicleta.calcular_total(id_bicicleta, session['usuario']['estrato'])
        session['datos_alquiler'] = datos
        respuesta = {'titulo': 'OK', 'datos': datos}
        return jsonify(respuesta)

@app.route('/registrarDevolucion', methods=['GET'])
def registrar_devolucion():
    if session.get('login') == True:
        bicicleta.registrar_devolucion(session['datos_alquiler'])
        session.pop('datos_alquiler',None)
        respuesta = {'titulo': 'OK', 'texto': '¡La devolución fue exitosa!'}
        return jsonify(respuesta)
    else:
        return redirect('/')
    


     


        


    
    
