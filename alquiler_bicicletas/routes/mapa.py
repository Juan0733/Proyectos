from conexion import *
from models.bicicletas import bicicleta

@app.route('/mostrarMapa', methods=['GET'])
def mostrar_mapa():
    if session.get('login') == True:
        return render_template('mapa.html')
    else:
        return redirect('/')

@app.route('/bicicletasRegion', methods=['GET'])
def bicicletas_region():
    if session.get('login') == True:
        bicicletas = bicicleta.bicicletas_x_regiones()
        if not bicicletas:
            respuesta = {
                'titulo': 'ERROR',
                'texto': 'No se encontraron resultados'
            }
        else:
            respuesta = {
                'titulo': 'OK',
                'datos': bicicletas
            }

        return jsonify(respuesta)
    else:
        return redirect('/')