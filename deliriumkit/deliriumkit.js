/**
 * @author Ruben Omar Cobos Leal
 * @version 1.0
 * @projectDescription
 * .......................
 */

/**
 * Esta es la clase principal
 * @constructor
 * 
 */
function deliriumkit(settings){
	/**
	 * Incluir el archivo con las funciones basicas
	 */
	if (settings) {
		for (option in settings){ 
			this.settings[option] = settings[option]; 
		}
	}	
}

/**
 * Version de la libreria
 */
deliriumkit.prototype.version="1.0";

/**
 * Autor de la libreria
 */
deliriumkit.prototype.author="Ruben Omar Cobos Leal";

/**
 * Path a este archivo
 * TODO : variable "mode[MDI,STANDARD]"{by titus} para decidir si se trabaja en modo MDI(ventanas) o en modo STANDARD(website) 
 */
deliriumkit.prototype.settings={
	path:'deliriumkit/',
	skin:'default'
}
/**
 * Funcion para incluir un archivo de javascript
 * @param {string} [jsFile] ruta del archivo a incluir.
 */
function include_javascript_file(jsFile){
	document.write("<" + "script src=\"" + jsFile + "\"></" + "script>");
}

function include_css(cssFile){
	
	document.write("<" + "link href=\"" + delirium_skin() + 'css/' + cssFile + "\" rel=\"stylesheet\" type=\"text/css\" />");
}

function delirium_set_path(path){
	deliriumkit.prototype.settings['path']=path;
}

function delirium_set_skin(skin){
	deliriumkit.prototype.settings['skin']=skin;
}
function delirium_skin(){
	return deliriumkit.prototype.settings['path']+'skin/'+deliriumkit.prototype.settings['skin']+'/';
}
function delirium_init(){
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/uuid.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/keyboard.js');
	//include_javascript_file(deliriumkit.prototype.settings['path']+'lib/functions.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/screen.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/String.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/ajax.js');	
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/Suggest.js');
	//include_javascript_file(deliriumkit.prototype.settings['path']+'lib/Search.js');
	include_javascript_file('raw.php?do=system::get_js_search&timestamp={timestamp}');
	include_javascript_file('raw.php?do=system::get_js_funciones&timestamp={timestamp}');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/Dom.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/Window.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/Animations.js');
	include_javascript_file(deliriumkit.prototype.settings['path']+'lib/Transitions.js');	
	//include_css('deliriumkit.css');
}
var debug_enabled = false;
function set_debug(st){
    debug_enabled = st;
}
function debug(msg){
    if(!debug_enabled){
        return false;
    }
	try{
		console.debug(msg);
	}catch(e){}
}
