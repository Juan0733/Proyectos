from conexion import *

class Informes:
    def __init__(self, db):
        self.db = db
        self.cursor = db.cursor()

    def consultar_informes(self, mes, anio):
        sql = f"SELECT DATE_FORMAT(fecha_alquiler, '%d-%m-%Y %h:%i %p') as fecha_alquiler, DATE_FORMAT(fecha_devolucion, '%d-%m-%Y %h:%i %p') as fecha_devolucion, usuarios.nombres, usuarios.apellidos, marca, total FROM alquileres INNER JOIN usuarios ON fk_usuario = pk_usuario INNER JOIN bicicletas ON fk_bicicleta = pk_bicicleta WHERE total IS NOT NULL AND MONTH(fecha_devolucion) = {mes} AND YEAR(fecha_devolucion) = {anio} ORDER BY fecha_alquiler;"

        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]
        

        if not len(resultados) > 0 :
            return False
        else:
            informes = {'items': [dict(zip(colum_names, resultado)) for resultado in resultados]}
            total_general = 0
            for informe in informes['items']:
                total_general += informe['total']
            informes['total_general'] = total_general
            print(informes)
            return informes
        

informe = Informes(db)