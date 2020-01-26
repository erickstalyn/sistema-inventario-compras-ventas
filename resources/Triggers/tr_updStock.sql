DELIMITER //
CREATE TRIGGER `tr_updStock` 
BEFORE UPDATE 
ON detalle_producto
FOR EACH ROW
BEGIN
	IF(new.substock <> old.substock) THEN
		UPDATE producto 
		set stock = stock + (new.substock-old.substock)
		where id = new.producto_id;
	END IF;
end
//
DELIMITER ;