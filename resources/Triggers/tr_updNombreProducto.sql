DELIMITER //
CREATE TRIGGER `tr_updNombreProducto`
BEFORE UPDATE 
ON superproducto
FOR EACH ROW
BEGIN
	IF ( old.nombre <> new.nombre )  THEN
    		UPDATE producto p
    		set p.nombre = CONCAT(new.nombre, ' ', p.size, ' ', p.color)
    		WHERE p.superproducto_id = new.id;
    	END IF;
end
//
DELIMITER ;