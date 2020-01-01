DELIMITER //
CREATE TRIGGER tr_updateFallidos BEFORE UPDATE ON detalle_venta 
FOR EACH ROW 
BEGIN
    DECLARE fallidos_inicial INTEGER; 
	IF ( new.fallidos IS NOT NULL ) THEN 
		IF ( old.fallidos IS NULL ) THEN 
			SELECT fallidos INTO fallidos_inicial FROM detalle_producto WHERE id = old.detalle_producto_id; 
			UPDATE detalle_producto	dp SET dp.fallidos = fallidos_inicial + new.fallidos WHERE id = old.detalle_producto_id; 
	    ELSE 
        	IF ( new.fallidos > old.fallidos ) THEN 
				SELECT fallidos INTO fallidos_inicial FROM detalle_producto WHERE id = old.detalle_producto_id; 
				UPDATE detalle_producto	dp SET dp.fallidos = fallidos_inicial+new.fallidos-old.fallidos WHERE id = old.detalle_producto_id; 
            END IF;
		END IF;
	END IF;
END
// DELIMITER ;