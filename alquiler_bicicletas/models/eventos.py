from conexion import *

class Eventos:
    def __init__(self, db):
        self.db = db
        self.cursor = db.cursor()

    def consultar_eventos(self, filtro, usuario=False):
        if filtro == "activos":
            if usuario:
                sql = f"SELECT pk_evento, nombre, descripcion, DATE_FORMAT(fecha, '%d-%m-%Y %h:%i %p') as fecha, lugar, foto, estado FROM eventos WHERE estado = 'activo' AND NOT EXISTS(SELECT pk_participante FROM participantes WHERE fk_evento = eventos.pk_evento AND fk_usuario = {usuario}) ORDER BY fecha;"
            else:
                sql = f"SELECT pk_evento, nombre, descripcion, DATE_FORMAT(fecha, '%d-%m-%Y %h:%i %p') as fecha, lugar, foto, estado FROM eventos WHERE estado = 'activo' ORDER BY fecha;"

        elif filtro == "participando":
            sql = f"SELECT pk_evento, nombre, descripcion, DATE_FORMAT(fecha, '%d-%m-%Y %h:%i %p') as fecha, lugar, foto FROM eventos INNER JOIN participantes ON pk_evento = fk_evento WHERE estado = 'activo' AND fk_usuario = {usuario} ORDER BY fecha;"
            
        elif filtro == 'vencidos':
            if usuario:
                sql = f"SELECT pk_evento, nombre, descripcion, DATE_FORMAT(fecha, '%d-%m-%Y %h:%i %p') as fecha, lugar, foto FROM eventos INNER JOIN participantes ON pk_evento = fk_evento WHERE estado = 'vencido' AND fk_usuario = {usuario} ORDER BY fecha;"
            else:
                sql = f"SELECT pk_evento, nombre, descripcion, DATE_FORMAT(fecha, '%d-%m-%Y %h:%i %p') as fecha, lugar, foto FROM eventos WHERE estado = 'vencido' ORDER BY fecha;"

        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]

        if not len(resultados) > 0:
            return False
        else:
            return [dict(zip(colum_names, resultado)) for resultado in resultados]
        
    def consultar_evento(self, evento):
        sql = f"SELECT nombre, descripcion, lugar, fecha FROM eventos WHERE pk_evento = {evento};"
        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()
        colum_names = [colum[0] for colum in self.cursor.description]
        return [dict(zip(colum_names, resultado)) for resultado in resultados]
    
    def verificar_estado_eventos(self):
        ahora = datetime.now(pytz.timezone('America/Bogota'))
        sql = f"UPDATE eventos SET estado = 'vencido' WHERE fecha < '{ahora}' AND estado = 'activo';"
        self.cursor.execute(sql)
        self.db.commit()

    def agregar_evento(self, datos):
        ahora = datetime.now()
        tiempo = ahora.strftime("%Y%m%d%H%M%S")
        nombre,extension = os.path.splitext(datos['foto'].filename)
        nuevonombre = "U" + tiempo + extension
        datos['foto'].save("uploads/"+nuevonombre)
        sql = f"INSERT INTO eventos(nombre, descripcion, fecha, lugar, foto) VALUES('{datos['nombre']}', '{datos['descripcion']}', '{datos['fecha']}', '{datos['lugar']}', '{nuevonombre}');"
        self.cursor.execute(sql)
        self.db.commit()

    def modificar_evento(self, datos):
        sql = """
            UPDATE eventos 
            SET nombre = %s, 
                descripcion = %s, 
                fecha = %s, 
                lugar = %s 
            WHERE pk_evento = %s
        """
        valores = (datos['nombre'], datos['descripcion'], datos['fecha'], datos['lugar'], datos['id'])
        self.cursor.execute(sql, valores)
        self.db.commit()

    def consultar_participante(self, participante, evento):
        sql = f"SELECT fk_evento FROM participantes WHERE fk_usuario = {participante} AND fk_evento = {evento}"
        self.cursor.execute(sql)
        resultados = self.cursor.fetchall()

        if not len(resultados) > 0:
            return False
        else:
            return True
    
    def eliminar_evento(self,id):
        sql = f"UPDATE eventos SET estado = 'inactivo' WHERE pk_evento = {id};"
        self.cursor.execute(sql)
        self.db.commit()


    def registrar_participante(self, partipante, evento):
        sql = f"INSERT INTO participantes(fk_usuario, fk_evento) VALUES({partipante}, {evento});"
        self.cursor.execute(sql)
        self.db.commit()
        
evento = Eventos(db)