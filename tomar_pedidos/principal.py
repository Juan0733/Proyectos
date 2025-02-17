from conexiondb import *
from routes.usuarios import consultarUsuario
from routes.mesas import consultarMesas
from routes.productos import consultar_productos
from routes.pedidos import consultar_pedidos



if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, port=4000)