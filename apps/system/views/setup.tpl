<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="undefined">
    <head>
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
        <title>%TITULO%</title>

        <!--Archivo para el deliriumkit-->

        <script type="text/javascript" src="core/deliriumkit/deliriumkit.js" ></script>
        <script type="text/javascript">	
            delirium_set_path('core/deliriumkit/');
            delirium_init();
            var dk= new deliriumkit();	
        </script>

        <!--Archivo para el estilo de la aplicacion-->
        <link href="media/skin/default/style.css" rel="stylesheet" type="text/css" />
        <style type="text/css" media="screen">
            input[type=text]{ 
                border-radius:10px; 
                -moz-border-radius:10px; 
                -webkit-border-radius:10px; 
                line-height:20px;
                width:150px;
            }

            #body { 
                width: 100%;
                height: 100%; 
                display: table; 
            }

            #valign{
                display: table-cell; 
                vertical-align: middle; 
            }

            #setup-wrapper{
                width:500px;
                margin:0px auto;
                border-radius:5px; 
                -moz-border-radius:10px; 
                -webkit-border-radius:10px; 
                border:1px solid #c0c0c0;
                padding:20px; 
                -moz-box-shadow: 5px 5px 5px #ccc;
                -webkit-box-shadow: 5px 5px 5px #ccc;
                box-shadow: 5px 5px 5px #ccc;
            }

            #setup-wrapper div{
                font-size:22px;
            }

            #bienvenido{
                text-align:center;
            }

            #msg div{
                font-size: 12px !important;
            }
        </style>

    </head>
    <body id="body">
        <div id="valign">
            <div id="setup-wrapper" >
                <div id="bienvenido">
                    %BIENVENIDO%
                </div>
                <div id="setup">
                    %SETUP%
                </div>
                <div id="setup_nav">
                    <a class="button" onclick="mvcPost('system::setup_step_1','','setup-wrapper');" href="javascript:void(0);"><span>%SIGUIENTE%</span></a>
                </div>
            </div>
        </div>
    </body>
</html>
