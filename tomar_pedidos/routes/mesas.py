from conexiondb import *
from models.mesas import mesa

@app.route('/consultarMesas', methods=['GET'])
def consultarMesas():
    try:
        mesas = mesa.consultarMesas()
        if mesas:
            return jsonify(mesas)
        else:
            return jsonify({'msg': 'Not found'})
    except Exception as e:
        print(e)
        return jsonify({'error':str(e)})

