-- Smart School Quick Fees Create Uninstall DB
-- Version 1.0
-- https://smart-school.in
-- https://qdocs.net

-- --------------------------------------------------------

DELETE from `permission_group` where id=1300;

DELETE from `permission_category` where id=13001; 

DELETE from `sidebar_menus` where id=37;

DELETE from `sidebar_sub_menus` where id=217;

update `addons` set current_version =NULL, installation_by=NULL WHERE product_id='57220011';


