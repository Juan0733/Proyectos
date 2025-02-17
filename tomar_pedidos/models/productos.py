from conexiondb import *

class Productos:

    def consultarCategorias(self):
        cursor = conexion.cursor()
        sql = "SELECT * FROM categorias;"
        cursor.execute(sql)
        resultados = cursor.fetchall()
        colum_names = [colum[0] for colum in cursor.description]
        cursor.close()
        if len(resultados) == 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]
        
    def consultarProductos(self):
        cursor = conexion.cursor()
        sql = 'SELECT * FROM productos;'
        cursor.execute(sql)
        resultados = cursor.fetchall()
        colum_names = [colum[0] for colum in cursor.description]
        cursor.close()
        if len(resultados) == 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]

    def consultarProductosCategoria(self, categoria):
        cursor = conexion.cursor()
        sql = f"SELECT * FROM productos WHERE fk_categoria = {categoria};"
        cursor.execute(sql)
        resultados = cursor.fetchall()
        colum_names = [colum[0] for colum in cursor.description]
        cursor.close()
        if len(resultados) == 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]
    

producto = Productos()