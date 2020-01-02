DELIMITER //
CREATE TRIGGER tr_updateFallidos BEFORE UPDATE ON detalle_venta 
FOR EACH ROW 
BEGIN
	IF ( new.fallidos IS NOT NULL ) THEN 
		IF ( old.fallidos IS NULL ) THEN 
			UPDATE detalle_producto	
			SET fallidos = fallidos + new.fallidos 
			WHERE id = old.detalle_producto_id; 
	    ELSE 
        	IF ( new.fallidos > old.fallidos ) THEN 
				UPDATE detalle_producto	
				SET fallidos = fallidos + new.fallidos - old.fallidos 
				WHERE id = old.detalle_producto_id; 
            END IF;
		END IF;
	END IF;
	IF ( new.cantidad > old.cantidad ) THEN
		UPDATE detalle_producto
		SET substock = substock - (new.cantidad - old.cantidad)
		WHERE id = old.detalle_producto_id;
	END IF;
END
// DELIMITER ;