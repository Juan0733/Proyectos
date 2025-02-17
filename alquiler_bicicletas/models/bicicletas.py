from conexion import *

class Bicicletas:
    def __init__(self, db):
        self.db = db
        self.cursor = db.cursor()

    def consultar_bicicletas(self, estado, region=False, usuario=False):
        if estado == "disponible":
            if not region:
                sql = f"SELECT foto, pk_bicicleta, marca, color, precio_alquiler, estado , regiones.nombre as region FROM bicicletas INNER JOIN regiones ON fk_region = pk_region WHERE estado = '{estado}';"
            else:
                sql = f"SELECT foto, pk_bicicleta, marca, color, precio_alquiler, estado, regiones.nombre as region FROM bicicletas INNER JOIN regiones ON fk_region = pk_region WHERE estado = '{estado}' AND fk_region = '{region}';"
        else:
            if not usuario:
                if not region:
                    sql = f"SELECT nombres, apellidos, foto, pk_bicicleta, marca, color, precio_alquiler, regiones.nombre as region, estado FROM bicicletas INNER JOIN alquileres ON bicicletas.pk_bicicleta = alquileres.fk_bicicleta INNER JOIN usuarios ON alquileres.fk_usuario = usuarios.pk_usuario  INNER JOIN regiones ON fk_region = pk_region WHERE fecha_devolucion IS NULL;"
                else:
                    sql = f"SELECT nombres, apellidos, foto, pk_bicicleta, marca, color, precio_alquiler, regiones.nombre as region, estado FROM bicicletas INNER JOIN alquileres ON bicicletas.pk_bicicleta = alquileres.fk_bicicleta INNER JOIN usuarios ON alquileres.fk_usuario = usuarios.pk_usuario  INNER JOIN regiones ON fk_region = pk_region WHERE fecha_devolucion IS NULL AND fk_region = {region};"
            else:
                if not region:
                    sql = f"SELECT foto, pk_bicicleta, marca, color, precio_alquiler, regiones.nombre as region, estado FROM bicicletas INNER JOIN alquileres ON bicicletas.pk_bicicleta = alquileres.fk_bicicleta INNER JOIN usuarios ON alquileres.fk_usuario = usuarios.pk_usuario  INNER JOIN regiones ON fk_region = pk_region WHERE fecha_devolucion IS NULL AND fk_usuario = {usuario};"
                else:
                    sql = f"SELECT foto, pk_bicicleta, marca, color, precio_alquiler, regiones.nombre as region, estado FROM bicicletas INNER JOIN alquileres ON bicicletas.pk_bicicleta = alquileres.fk_bicicleta INNER JOIN usuarios ON alquileres.fk_usuario = usuarios.pk_usuario  INNER JOIN regiones ON fk_region = pk_region WHERE fecha_devolucion IS NULL AND fk_region = {region} AND fk_usuario = {usuario};"
        

        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]

        if not len(resultados) > 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]
        
    def consultar_bicicleta(self, bicicleta):
        sql = f"SELECT marca, color, precio_alquiler, pk_region, regiones.nombre FROM bicicletas INNER JOIN regiones ON fk_region = pk_region WHERE pk_bicicleta = {bicicleta}"
        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]
        return [dict(zip(colum_names, resultado)) for resultado in resultados]

        
    def consultar_regiones(self):
        sql = f"SELECT pk_region, nombre FROM regiones;"
        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]
        return [dict(zip(colum_names, resultado)) for resultado in resultados]
    
    def bicicletas_x_regiones(self):
        sql = "SELECT DISTINCT pk_region, nombre FROM bicicletas INNER JOIN regiones ON fk_region = pk_region WHERE estado <> 'inactiva';"
        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]
        regiones = [dict(zip(colum_names, resultado)) for resultado in resultados]
        bicicletas_region = []
        for region in regiones:
            sql = f"SELECT COUNT(*) FROM bicicletas WHERE estado = 'disponible' AND  fk_region = {region['pk_region']};"
            self.cursor.execute(sql)
            disponibles = self.cursor.fetchall()[0][0]
            sql = f"SELECT COUNT(*) FROM bicicletas WHERE estado = 'alquilada' AND  fk_region = {region['pk_region']};"
            self.cursor.execute(sql)
            alquiladas = self.cursor.fetchall()[0][0]
            total = disponibles + alquiladas
            bicicletas_region.append({'region': region['nombre'], 'disponibles': disponibles, 'alquiladas': alquiladas,'total': total})
        return bicicletas_region

    def agregar_bicicleta(self, datos):
        ahora = datetime.now()
        tiempo = ahora.strftime("%Y%m%d%H%M%S")
        nombre,extension = os.path.splitext(datos['foto'].filename)
        nuevonombre = "U" + tiempo + extension
        datos['foto'].save("uploads/"+nuevonombre)
        sql = f"INSERT INTO bicicletas(marca, color, precio_alquiler, foto, fk_region) VALUES('{datos['marca']}', '{datos['color']}', {datos['precio']}, '{nuevonombre}', '{datos['regional']}');"
        self.cursor.execute(sql)
        self.db.commit()

    def actualizar_bicicleta(self, datos):
        sql=f"UPDATE bicicletas SET marca = '{datos['marca']}', color = '{datos['color']}', precio_alquiler = {datos['precio']}, fk_region = '{datos['regional']}' WHERE pk_bicicleta = {datos['id']};"

        self.cursor.execute(sql)
        self.db.commit()

    def eliminar_bicicleta(self, id):
        sql = f"UPDATE bicicletas SET estado = 'inactiva' WHERE pk_bicicleta = {id};"
        self.cursor.execute(sql)
        self.db.commit()

    def registrar_alquiler(self, bicicleta, usuario):
        ahora = datetime.now(pytz.timezone('America/Bogota'))
        print(ahora, type(ahora))
        sql = f"INSERT INTO alquileres(fk_usuario, fk_bicicleta, fecha_alquiler) VALUES({usuario}, {bicicleta}, '{ahora}');"
        self.cursor.execute(sql)
        self.db.commit()

        sql = f"UPDATE bicicletas SET estado = 'alquilada' WHERE pk_bicicleta = {bicicleta}"
        self.cursor.execute(sql)
        self.db.commit()
    
    def calcular_total(self, bicicleta, estrato):
        ahora = datetime.now(pytz.timezone('America/Bogota'))
        sql = f"SELECT precio_alquiler, fecha_alquiler FROM alquileres INNER JOIN bicicletas ON fk_bicicleta = pk_bicicleta AND fk_bicicleta = {bicicleta} AND total IS NULL;"
        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        precio_alquiler = resultados[0][0]
        fecha_alquiler = pytz.timezone('America/Bogota').localize(resultados[0][1])
        fecha_str = fecha_alquiler.strftime('%d-%m-%Y %H:%M:%S')
        diferencia = ahora - fecha_alquiler
        horas = diferencia.total_seconds() / 3600
        total_inicial = horas * precio_alquiler
        descuento_aplica = 0
        if estrato == '1' or estrato == '2':
            descuento_aplica = 10
        elif estrato == '3' or estrato == '4':
            descuento_aplica = 5
        valor_descuento = total_inicial * descuento_aplica / 100
        total_final = total_inicial - valor_descuento
        datos_alquiler = {
            'bicicleta': bicicleta,
            'fecha_alquiler': fecha_str ,
            'numero_horas': round(horas, 1),
            'total_inicial': round(total_inicial, 2),
            'descuento_aplica': descuento_aplica,
            'valor_descuento':round(valor_descuento, 2),
            'total_final': round(total_final, 2),
            'fecha_devolucion': ahora.strftime('%Y-%m-%d %H:%M:%S')
        }

        return datos_alquiler
            

    def registrar_devolucion(self, datos_alquiler):
        sql = f"UPDATE alquileres SET fecha_devolucion = '{datos_alquiler['fecha_devolucion']}', total = {datos_alquiler['total_final']} WHERE fk_bicicleta = {datos_alquiler['bicicleta']} AND total IS NULL;"
        self.cursor.execute(sql)
        self.db.commit()
        sql = f"UPDATE bicicletas SET estado = 'disponible' WHERE pk_bicicleta = {datos_alquiler['bicicleta']};"
        self.cursor.execute(sql)
        self.db.commit()
        
bicicleta = Bicicletas(db)

        