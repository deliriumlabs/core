/**
 * @author delirium
 */

/**
 * Basada en Prototype JavaScript framework, version 1.4.0
 * para mas detalles, http://prototype.conio.net/
 * 
 * Atajo para document.getElementById().  
 * @name $()
 * @param {String, Array} ...	uno o mas ids.
 * @return {Array} Regresa un array del elemento(s) correspondientes as id(s) especificados.
 */
function $() {
    var elements = new Array();

    for (var i = 0; i < arguments.length; i++) {
        var element = arguments[i];
        if (typeof element == 'string'){
            element = document.getElementById(element);
        }
        if (arguments.length == 1){
            return element;
        }
        elements.push(element);
    }

    return elements;
}

function $TagNames(list,obj) {
    if (!obj) var obj = document;
    var tagNames = list.split(',');
    var resultArray = new Array();
    for (var i=0;i<tagNames.length;i++){
        var tags = obj.getElementsByTagName(tagNames[i]);
        for (var j=0;j<tags.length;j++){
            resultArray.push(tags[j]);
        }
    }

    var testNode = new Array();
    testNode = resultArray[0];
    if(typeof testNode == "undefined"){
        return resultArray;
    }
    if (typeof testNode.sourceIndex != "undefined"){
        resultArray.sort(function (a,b) {
                return a.sourceIndex - b.sourceIndex;
                });
    }
    else if (testNode.compareDocumentPosition){
        resultArray.sort(function (a,b) {
                return 3 - (a.compareDocumentPosition(b) & 6);
                });
    }
    return resultArray;
}

function $value(id){
    return $(id).value;
}

function $hide(){
    for (var i = 0; i < arguments.length; i++) {
        var element = arguments[i];
        if (typeof element == 'string') {
            element = $(element);
        }
        if(isNull(element)){
            return false;
        }
        element.old_display = element.style.display!='' ? element.style.display : 'block';
        element.style.display = 'none';
    }
    return true;
}

function $show(){
    for (var i = 0; i < arguments.length; i++) {
        var element = arguments[i];
        if (typeof element == 'string') {
            element = $(element);
        }		
        if(isNull(element)){
            return false;
        }
        element.style.display = '';
    }
}

function $toggle(){
    for (var i = 0; i < arguments.length; i++) {
        var element = arguments[i];
        if (typeof element == 'string') {
            element = $(element);
        }
        if(isNull(element)){
            return false;
        }		
        element.style.display = element.style.display!='none' ? 'none' : '';
    }
    return true;
}

/**
 * Converts the argument "iterable" into an Array object.
 * @name $A()
 * @param {Object} iterable	Object to be converted to an Array.
 * @return {Array} Returns an Array.
 */
var $A = Array.from = function(iterable) {
    if (!iterable) return [];
    if (iterable.toArray) {
        return iterable.toArray();
    } else {
        var results = [];
        for (var i = 0; i < iterable.length; i++)
            results.push(iterable[i]);
        return results;
    }
}

Function.prototype.bind = function() {
    var __method = this,
        args = $A(arguments),
        object = args.shift();

    return function() {
        return __method.apply(object, args.concat($A(arguments)));
    }
}

function isset(obj){	
    return typeof obj=="undefined";
}

function isNull(val){
    return (val==null);
}

// REmover espacios a la izquierda
function LTrim( value ) {	
    var re = /\s*((\S+\s*)*)/;
    return value.replace(re, "$1");

}

// remover espacios a la derecha
function RTrim( value ) {

    var re = /((\s*\S+)*)\s*/;
    return value.replace(re, "$1");

}

// Remover espacios en blanco.
function trim( value ) {
    return LTrim(RTrim(value));
}

function validate(data,type){
    if(!type){
        return [true,''];
    }	
    for(x=0;x<type.length;x++){
        switch(type[x]){
            case "noempty":
                if(trim(data)==""){
                    return [false,'%VALIDA_NO_PUEDE_ESTAR_VACIO%'];
                }
            break;
            case "nospaces":
                if(data.indexOf(" ")>-1){
                    return [false,'%VALIDA_NO_ESPACIOS%'];
                }
            break;
            case "username":

                if(trim(data)==""){
                    return [true,''];
                }
            re = /^\w+$/; 
            if(!re.test(data)) {				
                return [false, '%VALIDA_USUARIO_NO_ESPACIOS_NO_ESPECIALES%'];
            }
            break;
            case "currency":
                if(trim(data)==""){
                    return [true,''];
                }
                var OK = /^[0-9][0-9]{0,2}(,?[0-9]{3})*(\.[0-9]+)?$/.test(data);

                if(!OK){
                    return [false, '%VALIDA_SOLO_MONEDA%'];
                }
            break;
            case "number":
            case "solonumeros":
                if(trim(data)==""){
                    return [true,''];
                }
                var OK = /^[0-9][0-9]{0,2}(,?[0-9]{3})*(\.[0-9]+)?$/.test(data);

                if(!OK){
                    return [false, '%VALIDA_SOLO_NUMEROS%'];
                }

                /*
                if(isNaN(data)){
                    return [false,'%VALIDA_SOLO_NUMEROS%'];	
                }
                */

                break;
            case "noidzero":
                if(trim(data)=="" || isNaN(data)){
                    return [true,''];
                }

            if(data==0){
                return [false,'%VALIDA_SELECCIONE_OPCION%'];	
            }
            break;
            case "date":
            case "fecha":
                if(trim(data)==""){
                    return [true,''];
                }

                var V, DObj = NaN;
                data=data.replace(/\//g,"-");
                V = data.match(/^(\d\d)-(\d\d)-(\d{4})$/);
                if (V) {
                    V = (DObj = new Date(V[3], --V[2], V[1])).getMonth() == V[2];
                }
                if(V=="null"){
                    V=false;
                }
                if(!V){
                    return [false, '%VALIDA_FECHA%.'];
                }
                break;

            case "hora":
            case "time":
                if(trim(data)==""){
                    return [true,''];
                }

                var OK = /^\d{1,2}:\d{2}(\s)?([ap]m)?([AP]M)?$/.test(data);

                if(!OK){
                    return [false, '%VALIDA_HORA%'];
                }

                break;
                
            case "mysqldate":
                case "fechamysql":
                if(trim(data)==""){
                    return [true,''];
                }

            var V, DObj = NaN;
            data=data.replace(/\//g,"-");
            V = data.match(/^(\d{4})-(\d\d)-(\d\d)$/);
            if (V) {
                V = (DObj = new Date(V[1], --V[2], V[3])).getMonth() == V[2];
            }
            if(V=="null"){
                V=false;
            }
            if(!V){
                return [false, '%VALIDA_FECHA%'];
            }
            break;

            case "email":
                if(trim(data)==""){
                    return [true,''];
                }

            var emailReg = "^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$";
            var regex = new RegExp(emailReg);
            var OKemail = regex.test(data);

            if(!OKemail){
                return [false, '%VALIDA_EMAIL%'];
            }
            break;
            default:
            validate_opt=type[x].split(",");

            if(validate_opt.length>1){
                if(validate_opt.length > 2){
                    fix_opt = type[x].split(",");
                    fix_opt.splice(0,1);
                    fix_opt = fix_opt.join();
                    validate_opt[1] = fix_opt.replace(/\$|\,/g,'');
                    //alert(validate_opt[1]);
                    //validate_opt[0] = opt;
                }
                try{
                    validate_to=eval(validate_opt[1].replace(/\,/g,''));
                }catch(e){
                    validate_to=validate_opt[1];
                }	
                switch(validate_opt[0]){
                    case ">":
                        case "gt":
                        validate_to=validate_to.toString().replace(/\$|\,/g,'');
                    data=data.toString().replace(/\$|\,/g,'');
                    if(!(Number(data)>validate_to)){
                        return [false,'%VALIDA_DATO_MAYOR% '+ validate_to +' .'];
                    }
                    break;
                    case ">=":
                        case "gteq":
                        validate_to=validate_to.toString().replace(/\$|\,/g,'');
                    data=data.toString().replace(/\$|\,/g,'');
                    if(!(Number(data)>=validate_to)){
                        return [false,'%VALIDA_MAYOR_IGUAL% '+ validate_to +' .'];
                    }
                    break;

                    case "<":
                        case "lt":
                        validate_to=validate_to.toString().replace(/\$|\,/g,'');
                    data=data.toString().replace(/\$|\,/g,'');
                    if(Number(data)>=validate_to ){
                        return [false,'%VALIDA_MENOR% '+ validate_to +' .'];
                    }
                    break;

                    case "<=":
                        case "lteq":
                        validate_to=validate_to.toString().replace(/\$|\,/g,'');
                    data=data.toString().replace(/\$|\,/g,'');
                    if(Number(data)>validate_to ){
                        return [false,'%VALIDA_MENOR_IGUAL% '+ validate_to +' .'];
                    }
                    break;

                    case "=":
                        case "eq":

                        if(!(data==validate_to)){
                            return [false,'%VALIDA_IGUAL% '+ validate_to +' .'];
                        }
                    break;
                }
            }
            break;
        }
    }	

    return [true,''];

}

function validateForm(id){
    var form=$(id);
    var elements = form.elements;	
    var opts="";

    for(var j=0;j<elements.length;j++){		
        var validar=null;
        try{
            validar=elements[j].getAttribute('validate').split("|");
        }catch(x){}	

        if(opts!=''){
            opts+="&";
        }
        switch(elements[j].type){

            case "checkbox":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+elements[j].value;
                }
            break;

            case "radio":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+elements[j].value;
                }
            break;

            case "select":
                var sel=elements[j];
            if(elements[j].checked){
                opts+=sel.id+'='+sel.options[sel.selectedIndex].value;
            }
            break;

            default:
            opts+=elements[j].id+'='+elements[j].value;
            break;																																	
        }			

        var valida_result=validate(elements[j].value,validar);		
        if(!valida_result[0]){												
            alert(valida_result[1]);
            elements[j].normal_border_color=elements[j].style.borderColor;
            elements[j].style.borderColor='red';
            elements[j].focus();
            return false;
        }else{
            try{	
                elements[j].style.borderColor=elements[j].normal_border_color;
            }catch(fsbc){}
        }	

    }
    return true;

}

function validateDiv(id){
    var div=$(id);
    var elements=$TagNames("input,select,textarea",div);

    var opts="";	

    for(var j=0;j<elements.length;j++){		
        var validar=null;
        try{
            validar=elements[j].getAttribute('validate').split("|");
        }catch(x){}	

        if(opts!=''){
            opts+="&";
        }
        switch(elements[j].type){

            case "checkbox":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+elements[j].value;
                }
            break;

            case "radio":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+elements[j].value;
                }
            break;

            case "select":
                var sel=elements[j];
            if(elements[j].checked){
                opts+=sel.id+'='+sel.options[sel.selectedIndex].value;
            }
            break;

            default:
            opts+=elements[j].id+'='+elements[j].value;
            break;																																	
        }			

        var valida_result=validate(elements[j].value,validar);		
        if(!valida_result[0]){												
            alert(valida_result[1]);
            elements[j].normal_border_color=elements[j].style.borderColor;
            elements[j].style.borderColor='red';
            elements[j].focus();
            return false;
        }else{
            try{	
                elements[j].style.borderColor=elements[j].normal_border_color;
            }catch(fsbc){}
        }	

    }
    return true;

}

function bind(el, func){
    return function() { func.call(el); }
} 

function parseJson(jsonString){	
    return eval("("+jsonString+")");
}

function loadcssfile(filename){
    var timestamp = new Date();
    var fileref=document.createElement("link");
    fileref.setAttribute("rel", "stylesheet");
    fileref.setAttribute("type", "text/css");
    fileref.setAttribute("href", filename+'?uid='+timestamp.getTime());
    fileref.id=filename;

    if($(filename)!=null){
        $(filename).parentNode.replaceChild(fileref,$(filename));		
    }else{							
        if (typeof fileref != "undefined") {
            document.getElementsByTagName("head")[0].appendChild(fileref);
        }
    }
}

function loadjsfile(filename){
    var timestamp = new Date();
    var fileref=document.createElement("script");
    fileref.setAttribute("type", "text/javascript");
    fileref.setAttribute("src", filename+'?uid='+timestamp.getTime());
    fileref.id=filename;

    if($(filename)!=null){
        $(filename).parentNode.replaceChild(fileref,$(filename));		
    }else{							
        if (typeof fileref != "undefined") {
            document.getElementsByTagName("head")[0].appendChild(fileref);
        }
    }
}

function form2query(form_id){

    var elements = $(form_id).elements;
    var opts="";

    for(var j=0;j<elements.length;j++){		
        if(opts!=''){
            opts+="&";
        }
        switch(elements[j].type){

            case "checkbox":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+encode(elements[j].value);
                }
            break;

            case "radio":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+encode(elements[j].value);
                }
            break;

            case "select":
                var sel=elements[j];
            if(elements[j].checked){
                opts+=sel.id+'='+encode(sel.options[sel.selectedIndex].value);
            }
            break;

            default:
                //opts+=elements[j].id+'='+encodeURIComponent(elements[j].value);
                opts+=elements[j].id+'='+encode(elements[j].value);
            break;																																	
        }
    }
    return opts;
}

function div2query(div_id){

    var elements = $TagNames("input,textarea,checkbox,radio,select",$(div_id));
    var opts="";

    for(var j=0;j<elements.length;j++){		
        if(opts!=''){
            opts+="&";
        }
        switch(elements[j].type){

            case "checkbox":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+encode(elements[j].value);
                }
            break;

            case "radio":
                if(elements[j].checked){
                    opts+=elements[j].id+'='+encode(elements[j].value);
                }
            break;

            case "select":
                var sel=elements[j];
            if(elements[j].checked){
                opts+=sel.id+'='+encode(sel.options[sel.selectedIndex].value);
            }
            break;

            default:
                //opts+=elements[j].id+'='+encodeURIComponent(elements[j].value);
                opts+=elements[j].id+'='+encode(elements[j].value);
            break;																																	
        }
    }
    return opts;
}

function div2obj(div_id){
    objs = $TagNames("input,textarea,checkbox,radio,select",$(div_id));
    return objs;
}
//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license

if (!Array.prototype.filter)
{
  Array.prototype.filter = function(fun /*, thisp*/)
  {
    var len = this.length;
    if (typeof fun != "function")
      throw new TypeError();

    var res = new Array();
    var thisp = arguments[1];
    for (var i = 0; i < len; i++)
    {
      if (i in this)
      {
        var val = this[i]; // in case fun mutates this
        if (fun.call(thisp, val, i, this))
          res.push(val);
      }
    }

    return res;
  };
}

function encode(str){
    if( typeof(str) == 'undefined' ){
        str="";
    }
    str = str.replace(/\+/g,String.fromCharCode(8));
    str = escape(str);
    str = str.replace(/\%20/g,"+");
    str = str.replace(/\%08/g,"%2B");
    return str;
}

function decode(str) {
    str = str.replace(/\+/g, ' ');
    str = unescape(str);
    return str;
}


function send_form(form){
    var form = $(form);
    var old_action = form.action;
    form.action = 'raw.php?do='+form.getAttribute('_do').replace( /"/g, ''  );									
    form.submit();
    form.action = old_action;
}
