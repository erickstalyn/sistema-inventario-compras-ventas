DELIMITER //
CREATE TRIGGER tr_insertProducto
AFTER INSERT
ON producto
FOR EACH ROW
BEGIN
	INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
	values(0, new.precio_menor, new.precio_mayor, 1, new.id);

	INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
	values(0, new.precio_menor, new.precio_mayor, 2, new.id);


	INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
	values(0, new.precio_menor, new.precio_mayor, 3, new.id);

	INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
	values(0, new.precio_menor, new.precio_mayor, 4, new.id);

	INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
	values(0, new.precio_menor, new.precio_mayor, 5, new.id);


	INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
	values(0, new.precio_menor, new.precio_mayor, 6, new.id);
end
//
DELIMITER ;
