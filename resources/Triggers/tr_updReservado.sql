DELIMITER //
CREATE TRIGGER detalle_venta_AI
AFTER INSERT 
ON detalle_venta
FOR EACH ROW
BEGIN
<<<<<<< HEAD
    DECLARE esCredito TINYINT(1);
    DECLARE miTipo TINYINT(2);
    SELECT tipo into miTipo FROM venta WHERE id = new.venta_id;
    IF(miTipo = '31' or miTipo = '32') THEN
      UPDATE detalle_producto dproducto
        set dproducto.reservados = dproducto.reservados + new.cantidad
=======
    DECLARE modo TINYINT(2);
    SELECT SUBSTRING(tipo, 1, 1) into modo FROM venta WHERE id = new.venta_id;
    IF ( modo = '2' ) THEN
        UPDATE detalle_producto dproducto
        SET dproducto.reservados = dproducto.reservados + new.cantidad
>>>>>>> 9c5e2011cc53517b64e59a31b583ccd4e65f823b
        WHERE dproducto.id = new.detalle_producto_id;
    END IF;
END
// DELIMITER ;