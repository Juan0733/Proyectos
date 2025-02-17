from conexion import *
from models.eventos import evento


@app.route('/mostrarEventos', methods=['GET'])
def mostrar_eventos():
    if session.get('login') == True:
        evento.verificar_estado_eventos()
        if session.get('usuario')['rol'] == 'Administrador':
            eventos = evento.consultar_eventos('activos')
        elif session.get('usuario')['rol'] == 'Usuario':
            eventos = evento.consultar_eventos('activos', session['usuario']['id'])

        if not eventos:
            msg = "!No hay eventos disponibles¡"
            return render_template('eventos.html', msg=msg)
        else: 
            return render_template("eventos.html", eventos=eventos)
    else:
        return redirect('/')
    
@app.route('/consultarEventos/<filtro>', methods=['GET'])
def consultar_eventos(filtro):
    if session.get('login') == True:
        if session.get('usuario')['rol'] == 'Usuario':
            eventos = evento.consultar_eventos(filtro, session['usuario']['id'])
        elif session.get('usuario')['rol'] == 'Administrador':
            eventos = evento.consultar_eventos(filtro)
        
        if not eventos:
            respuesta = {"titulo": "ERROR", "texto": "!No se encontraron resultados¡"}
        else: 
            respuesta = {"titulo": "OK", "rol": session["usuario"]["rol"], "eventos": eventos}
        return jsonify(respuesta)
    

@app.route('/consultarEvento/<id_evento>', methods=['GET'])
def consultar_evento(id_evento):
    if session.get('login') == True and session.get('usuario')['rol'] == 'Administrador':
        datos_evento = evento.consultar_evento(id_evento)
        datos_evento[0]['fecha'] = datos_evento[0]['fecha'].strftime('%Y-%m-%d %H:%M:%S')
        respuesta = {'titulo': 'OK', 'datos': datos_evento}
        return jsonify(respuesta)
    else:
        return redirect('/')

@app.route('/agregarEvento', methods=['POST'])
def agregar_evento(datos):
    evento.agregar_evento(datos)
    return True

@app.route('/modificarEvento', methods=['POST'])
def modificar_evento():
    datos = request.get_json()
    evento.modificar_evento(datos)

    respuesta = {'titulo': 'OK',
                 'texto': '¡El evento se actualizo con exitó!'}
    
    return jsonify(respuesta)

@app.route('/eliminarEvento/<id_bicicleta>', methods=['GET'])
def eliminar_evento(id_bicicleta):
    if session.get('login') == True and session.get('usuario')['rol'] == 'Administrador':
        evento.eliminar_evento(id_bicicleta)
        if not evento:
            respuesta = {"titulo": "ERROR", "texto": "!No se encontraron resultados¡"}
        else: 
            respuesta = {"titulo": "OK", "texto": "!Eliminado correctamente¡"}
            
        return jsonify(respuesta)
    else:
        return redirect('/')
    
@app.route('/registrarParticipante/<id_evento>')
def registrar_participante(id_evento):
    if session.get('login') == True:
        participante = session['usuario']['id']
        if evento.consultar_participante(participante, id_evento):
            respuesta = {'titulo': 'ERROR',
                        'texto': '!Ya te encuentras registrado a este evento¡'}
        else:
            evento.registrar_participante(participante, id_evento)
            respuesta = {'titulo': 'OK',
                        'texto': '!El registro fue exitoso¡'}
            
        return jsonify(respuesta)
    else:
        return redirect('/')