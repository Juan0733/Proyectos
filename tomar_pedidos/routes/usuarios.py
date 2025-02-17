from conexiondb import *
from models.usuarios import usuario

@app.route('/consultarUsuario', methods=['POST'])
def consultarUsuario():
    try:
        data = request.get_json()
        resultados = usuario.consultarUsuario(data['usuario'], data['contrasena'])
        if resultados:
          return jsonify(resultados)  
        else:
            return jsonify({'msg': 'Not found'})
            
    except Exception as e:
        print(e)
        return jsonify({'error': str(e)})