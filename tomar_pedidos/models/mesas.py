from conexiondb import *
class Mesas:
    
    def consultarMesas(self):
        cursor = conexion.cursor()
        sql = f"SELECT * FROM mesas;"
        cursor.execute(sql)
        resultados = cursor.fetchall()
        colum_names = [column[0] for column in cursor.description]
        cursor.close()
        if len(resultados) == 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]
    
    def actualizarEstado(self, mesa, estado):
        cursor = conexion.cursor()
        sql = f"UPDATE mesas SET pedido = '{estado}' WHERE pk_mesa = {mesa};"
        cursor.execute(sql)
        conexion.commit()
        cursor.close()
        return True
        
mesa = Mesas()
