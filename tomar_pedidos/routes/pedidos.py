from conexiondb import *
from models.pedidos import pedido
from models.mesas import mesa

@app.route('/agregarPedido', methods=['POST'])
def agregar_pedido():
    try:
        data = request.get_json()
        if pedido.agregarPedido(data):
            if mesa.actualizarEstado(data['mesa'], 1):
                return jsonify({'msg': 'Success'})
    except Exception as e:
        print(str(e))
        return jsonify({'error': str(e)})


@app.route('/modificarPedido', methods = ['POST'])
def modificar_pedido():
    try:
        data = request.get_json()
        if pedido.modificarPedido(data):
            return jsonify({'msg': 'Success'})
    except Exception as e:
        print(str(e))
        return jsonify({'error': str(e)})
    
@app.route('/consultarPedidos', methods=['GET'])
def consultar_pedidos():
    try:
        pedidos = pedido.consultarPedidos()
        if pedidos:
            return jsonify(pedidos)
        else:
            return jsonify({'msg': 'Not found'})
    except Exception as e:
        return jsonify({'error': str(e)})
    
@app.route('/consultarPedido/<mesa>', methods = ['GET'])
def consultar_pedido(mesa):
    try:
        pedido_mesa = pedido.consultarPedido(mesa)
        return jsonify(pedido_mesa)
    except Exception as e:
        return jsonify({'error': str(e)})

@app.route('/finalizarPedido/<id_pedido>/<mesa_pedido>')
def finalizar_pedido(id_pedido, mesa_pedido):
    try:
        if pedido.finalizarPedio(id_pedido, 1):
            if mesa.actualizarEstado(mesa_pedido, 0):
                return jsonify({'msg': 'Success'})
    except Exception as e:
        return jsonify({'error': str(e)})

@app.route('/eliminarPedido/<id_pedido>/<mesa_pedido>')
def eliminar_pedido(id_pedido, mesa_pedido):
    try:
        if pedido.eliminarPedido(id_pedido):
            if mesa.actualizarEstado(mesa_pedido, 0):
                return jsonify({'msg': 'Success'})
    except Exception as e:
        return jsonify({'error': str(e)})