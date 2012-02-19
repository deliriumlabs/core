<div id="login_form">
<form method="POST" id="view_login" name="view_login" action="raw.php?do=usuarios::login" _do="usuarios::login" _target="body" >
	<b>%CHR_USUARIO%:</b>	
	<input type="text" id="chr_username" name="chr_username" validate="noempty" value="" />
	<br />
	<br />	
	<b>%CHR_PASSWD%:</b>	
	<input type="password" id="chr_passwd" name="chr_passwd" validate="noempty" value="" />

    <br />
	<br />
	
	
	<div class="panel_buttons">
	   <span class="button">
	       <input type="submit" value="LOGIN" >   	
	   </span>		
	</div>	
	<div class="panel_buttons">
		<img src="media/logo_login.jpg" width="266" border="0"/>
	</div>
{notificaciones}
</form>
<br />
<br />
<div style="margin:0pt auto;width:165px;">
<!--img src="media/image-logo-01.gif" border="0" style="" /-->
</div>
</div>
