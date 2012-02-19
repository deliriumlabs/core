<script>
function list(){
	lista_perfiles.refresh();
	{window_id}.close();
}
</script>
<form id="perfil_new" class="form" method="POST" action="root.php" _do="perfiles::set_acl" _target="" _callback="list">
{perfiles}
</form>