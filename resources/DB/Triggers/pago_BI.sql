DELIMITER //
CREATE TRIGGER pago_BI
BEFORE INSERT 
ON pago
FOR EACH ROW
BEGIN
    DECLARE tf integer;
    IF(new.abasto_id is not NULL) THEN
        UPDATE abasto
        set total_faltante = total_faltante - new.monto
        where id = new.abasto_id;
    ELSE
        UPDATE venta
        set total_faltante = total_faltante - new.monto
        where id = new.venta_id;

        SELECT total_faltante INTO tf FROM venta WHERE id = new.venta_id;
        IF(tf = 0) THEN
            UPDATE detalle_producto dproducto
            JOIN detalle_venta dventa
                ON dventa.detalle_producto_id = dproducto.id
                AND dventa.venta_id = new.venta_id
            SET dproducto.reservados = dproducto.reservados - dventa.cantidad;
	        UPDATE venta 
            SET total_faltante = NULL
            WHERE id = new.venta_id;
        END IF;
    END IF;
end
//
DELIMITER ;