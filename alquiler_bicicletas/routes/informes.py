from conexion import *
from models.informes import informe

@app.route('/mostrarInformes')
def mostrar_informes():
    if session.get('login') == True and session.get('usuario')['rol'] == 'Administrador':
        ahora = datetime.now(pytz.timezone('America/Bogota'))
        mes = int(ahora.strftime('%m')) 
        anio = int(ahora.strftime('%Y'))
        informes = informe.consultar_informes(mes, anio)
        meses = [{'id': 1, 'nombre': 'Enero'}, {'id': 2, 'nombre': 'Febrero'}, {'id': 3, 'nombre': 'Marzo'}, {'id': 4, 'nombre': 'Abril'}, {'id': 5, 'nombre': 'Mayo'}, {'id': 6, 'nombre': 'Junio'}, {'id': 7, 'nombre': 'Julio'}, {'id': 8, 'nombre': 'Agosto'}, {'id': 9, 'nombre': 'Septiembre'}, {'id': 10, 'nombre': 'Octubre'}, {'id': 11, 'nombre': 'Noviembre'}, {'id': 12, 'nombre': 'Diciembre'}]
        anios = [{'id': 1, 'valor': anio}]
        for i in range(1, 6):
            anios.append({'id': i+1, 'valor': anio-i})
    
        if not informes:
            msg = "¡No se encontraron informes para este mes!"
            return render_template('informes.html', meses=meses, mes_actual=mes, anios=anios, msg=msg)
        else:
            print(informes)
            return render_template('informes.html', informes=informes, meses=meses, mes_actual=mes, anios=anios)
    else:
        return redirect('/')
        
@app.route('/consultarInformes/<mes>/<anio>')
def consultar_informes(mes, anio):
    if session.get('login') == True and session.get('usuario')['rol'] == 'Administrador':
        informes = informe.consultar_informes(mes, anio)
        if not informes:
            respuesta = {'titulo': 'ERROR', 'texto': '¡No se encontraron informes para este mes!'}
        else:
            respuesta = {'titulo': 'OK', 'informes': informes}
        return jsonify(respuesta)
    else:
        return redirect('/')
