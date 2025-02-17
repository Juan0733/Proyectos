from conexiondb import *

class Usuarios:

    def consultarUsuario(self, usuario, contrasena):
        cursor = conexion.cursor()
        sql = f"SELECT pk_usuario, nombre FROM usuarios WHERE nombre = '{usuario}' AND contrasena = '{contrasena}';"
        cursor.execute(sql)
        resultados = cursor.fetchall()
        colum_names = [column[0] for column in cursor.description]
        cursor.close()
        if len(resultados) == 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]
        
usuario = Usuarios()
