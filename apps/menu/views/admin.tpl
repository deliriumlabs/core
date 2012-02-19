<script>
window.onload=function(){
	mvcPost('core_menu::admin_lista','parent_uuid=0&deep=0','0');
}
function regenerar_menu(){
	mvcPost("core_menu","","menu");
}
function confirmar_eliminar(uuid,parent_uuid,deep){
	if(confirm("Confirma eliminación?\n\rSe eliminaran los menus recursivos tambien!")==true){
		mvcPost('core_menu::menu_eliminar','uuid='+uuid+'&parent_uuid='+parent_uuid+'&deep='+deep,parent_uuid,'regenerar_menu');
	}
}
</script>
<div id="0">
</div>
<br />
<br />
<br />
<br />