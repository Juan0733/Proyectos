from conexiondb import *
from models.productos import producto

@app.route('/consultarCategorias')
def consultar_categorias():
    try:
        categorias = producto.consultarCategorias()
        if categorias:
            print(categorias)
            return jsonify(categorias)
        else:
            return jsonify({'msg': 'Not found'})
    except Exception as e:
        print(str(e))
        return jsonify({'error': str(e)})
    
@app.route('/consultarProductos', methods=['GET'])
def consultar_productos():
    try:
        productos = producto.consultarProductos()
        categorias = producto.consultarCategorias()
        if productos:
            return jsonify({'productos': productos, 'categorias': categorias})
        else:
            return jsonify({'msg': 'Not found'})
    except Exception as e:
        print(e)
        return jsonify({'error': str(e)})
    

@app.route('/consultarProductosCategoria/<categoria>')
def consultar_productos_categoria(categoria):
    try:
        productos = producto.consultarProductosCategoria(categoria)
        if productos:
            return jsonify(productos)
        else:
            return jsonify({'msg': 'Not found'})
    except Exception as e:
        print(str(e))
        return jsonify({'error': str(e)})
    