DELIMITER //
CREATE TRIGGER `tr_upStockPorVenta`
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