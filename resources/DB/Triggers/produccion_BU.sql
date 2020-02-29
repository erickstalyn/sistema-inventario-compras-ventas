DELIMITER //
CREATE TRIGGER produccion_BU
BEFORE UPDATE 
ON produccion
FOR EACH ROW
BEGIN
	IF(new.fecha_fin is not NULL) THEN
	UPDATE detalle_producto dproducto
	JOIN detalle_produccion dproduccion
    	ON dproduccion.producto_id = dproducto.producto_id
    	AND dproduccion.produccion_id = new.id
	AND dproducto.centro_id = new.almacen_id

	set dproducto.substock = dproducto.substock + dproduccion.cantidad;
	END IF;
end
//
DELIMITER ;