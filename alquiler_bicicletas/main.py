from conexion import *
from routes.sanitizar_data import sanitizar_credenciales
from routes.bicicletas import mostrar_bicicletas
from routes.eventos import mostrar_eventos
from routes.informes import mostrar_informes
from routes.mapa import mostrar_mapa

@app.route("/")
def index():
    return render_template("index.html")

@app.route('/uploads/<nombre>')
def uploads(nombre):
    return send_from_directory(app.config['CARPETAU'],nombre)



if __name__=="__main__":
    app.run(host="0.0.0.0", debug=True, port=5080)