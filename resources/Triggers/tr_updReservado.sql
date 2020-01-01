DELIMITER //
CREATE TRIGGER tr_updReservado
AFTER INSERT 
ON detalle_venta
FOR EACH ROW
BEGIN
    DECLARE esCredito TINYINT(1);
    DECLARE miTipo TINYINT(2);
    SELECT tipo into miTipo FROM VENTA WHERE id = new.venta_id;
    IF(miTipo = '31' or miTipo = '32') THEN
      UPDATE detalle_producto dproducto
        set dproducto.reservados = dproducto.reservados + new.cantidad
        WHERE dproducto.id = new.detalle_producto_id;
    END IF;


end
//
DELIMITER ;