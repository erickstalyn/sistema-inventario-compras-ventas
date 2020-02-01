DELIMITER //
CREATE TRIGGER detalle_venta_BI
BEFORE INSERT 
ON detalle_venta
FOR EACH ROW
BEGIN
    UPDATE detalle_producto dp
    set substock = substock - new.cantidad
    where id = new.detalle_producto_id;
end
//
DELIMITER ;