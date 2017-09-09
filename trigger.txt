DROP TRIGGER IF EXISTS `comprar_ticket`;
DELIMITER //
CREATE TRIGGER `comprar_ticket` BEFORE INSERT ON comedortickets FOR EACH ROW
BEGIN
DECLARE unico int;
DECLARE saldo decimal(10,2);
DECLARE restantes int;
DECLARE tickets_restantes int;
select usuarios.saldo INTO @saldo from usuarios where usuarios.id = new.idUsuario;
select usuarios.tickets_restantes INTO @tickets_restantes from usuarios where usuarios.id = new.idUsuario;
select (comedormenus.cantidad-comedormenus.vendidos) INTO @restantes from comedormenus where comedormenus.idMenu = new.idMenu;
select count(*) INTO @unico from comedortickets where comedortickets.idUsuario = new.idUsuario and comedortickets.idMenu = new.idMenu;
if @unico>0 then
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Solo puede comprar un unico Ticket por Menu';
end if;
if @saldo <= new.precio then
begin
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT =  'No posee suficiente saldo';
end;
end if;
if @restantes <= 0 then
begin
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT =  'No quedan tickets para el menu a comprar';
end;
end if;
if @tickets_restantes <= 0 then
begin
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT =  'No le quedan tickets restantes';
end;
end if;
	update comedormenus set comedormenus.vendidos = comedormenus.vendidos + 1 where comedormenus.idMenu = new.idMenu;
	update usuarios set usuarios.saldo = usuarios.saldo - new.precio, usuarios.tickets_restantes = usuarios.tickets_restantes - 1 where usuarios.id = new.idUsuario;
end //
DELIMITER ;