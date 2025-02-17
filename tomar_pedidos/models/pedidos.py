from conexiondb import *

class Pedidos:
    
    def agregarPedido(self, data):
        cursor = conexion.cursor()
        sql = f"INSERT INTO pedidos(fk_mesa, fk_usuario, fecha, total) VALUES({data['mesa']}, {data['usuario']}, '{data['fecha']}', {data['total']});"
        cursor.execute(sql)
        conexion.commit()
        sql = f"SELECT pk_pedido FROM pedidos WHERE fk_mesa = {data['mesa']} AND finalizado = '0';"
        for item in data['detalle_pedido']:
            sql = f"INSERT INTO detalle_pedidos(fk_pedido, fk_producto, cantidad, total) VALUE((SELECT pk_pedido FROM pedidos WHERE fk_mesa = {data['mesa']} AND finalizado = '0'), {item['producto']}, {item['cantidad']}, {item['total']});"
            cursor.execute(sql)
            conexion.commit()
        conexion.commit()
        cursor.close()
        return True
    
    def modificarPedido(self, data):
        cursor = conexion.cursor()
        sql = f"UPDATE pedidos SET fecha = '{data['fecha']}', total = {data['total']} WHERE pk_pedido = {data['id_pedido']};"
        cursor.execute(sql)
        conexion.commit()
        sql = f"DELETE FROM detalle_pedidos WHERE fk_pedido = {data['id_pedido']};"
        cursor.execute(sql)
        conexion.commit()
        for item in data['detalle_pedido']:
            sql = f"INSERT INTO detalle_pedidos(fk_pedido, fk_producto, cantidad, total) VALUES({data['id_pedido']}, {item['producto']}, {item['cantidad']}, {item['total']});"
            cursor.execute(sql)
            conexion.commit()
        cursor.close()
        return True
    
    def consultarPedidos(self):
        cursor = conexion.cursor()
        sql = "SELECT pk_pedido, fk_mesa, total FROM pedidos WHERE finalizado = '0';"
        cursor.execute(sql)
        resultados = cursor.fetchall()
        if len(resultados) == 0:
            cursor.close()
            return False
        else:
            colum_names = [colum[0] for colum in cursor.description]
            pedidos = [dict(zip(colum_names, resultado)) for resultado in resultados]
            sql = "SELECT fk_pedido, fk_producto, productos.nombre as nombre_producto, detalle_pedidos.cantidad, detalle_pedidos.total FROM (detalle_pedidos INNER JOIN productos ON fk_producto = pk_producto) INNER JOIN pedidos ON fk_pedido = pk_pedido WHERE finalizado = '0';"
            cursor.execute(sql)
            resultados = cursor.fetchall()
            colum_names = [colum[0] for colum in cursor.description]
            cursor.close()
            detalle_pedidos = [dict(zip(colum_names, resultado)) for resultado in resultados]
            return {'pedidos': pedidos, 'detalle_pedidos': detalle_pedidos}
    
    def consultarPedido(self, mesa):
        cursor = conexion.cursor()
        sql = f"SELECT fk_pedido, fk_producto, productos.nombre as nombre_producto, detalle_pedidos.cantidad, detalle_pedidos.total FROM (detalle_pedidos INNER JOIN productos ON fk_producto = pk_producto) INNER JOIN pedidos ON fk_pedido = pk_pedido WHERE fk_pedido = (SELECT pk_pedido FROM pedidos WHERE fk_mesa = {mesa} AND finalizado = '0');"
        cursor.execute(sql)
        resultados = cursor.fetchall()
        colum_names = [colum[0] for colum in cursor.description]
        return [dict(zip(colum_names, resultado)) for resultado in resultados]

    def finalizarPedio(self, id_pedido, estado):
        cursor = conexion.cursor()
        sql = f"UPDATE pedidos SET finalizado = '{estado}' WHERE pk_pedido = {id_pedido};"
        cursor.execute(sql)
        conexion.commit()
        cursor.close()
        return True
    
    def eliminarPedido(sef, id_pedido):
        cursor = conexion.cursor()
        sql = f"DELETE FROM detalle_pedidos  WHERE fk_pedido = {id_pedido};"
        cursor.execute(sql)
        conexion.commit()
        sql = f"DELETE FROM pedidos WHERE pk_pedido = {id_pedido};"
        cursor.execute(sql)
        conexion.commit()
        cursor.close()
        return True
    
pedido = Pedidos()