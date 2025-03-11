
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="Shortcut Icon" type="image/x-icon" href="assets/icons/icono.png" />
    <script src="js/sweet-alert.min.js"></script>
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>
</head>



<body class="full-cover-background" style="background-image:url(assets/img/loguinauditoria.jpg);">

    <div class="form-container">

        <p class="text-center" style="margin-top: 17px;">

           <img src="assets/img/puyo.png" width="200" height="200">
       </p>

       <h2 class="text-center all-tittles" style="margin-bottom: 30px;"><font   color="RED"> INICIA SESIÓN</font></h4>

       <form action="validar.php" method="POST">




       <div class="form-group">
						<label for="username" ><font   color="RED"> CORREO</font></label>
						<input type="email" class="form-control"  name="mail" required>
					</div>

          <div class="form-group">
						<label for="username"><font   color="RED">CONTRASEÑA </font></label>
						<input type="password" class="form-control"  name="pass" required>
					</div>
          

            


        
<button class="btn-login" type="submit"><font   color="white">Ingresar</font> &nbsp; <i class="zmdi zmdi-arrow-right"></i></button>
           
        </form>
          
         
          </form>
      
         <!-- <a href="registro.php">
     <button class="btn-login3" type="submit"><i class="zmdi zmdi-arrow-left"></i> &nbsp; Registrarse </button>
   </a> -->
    </div>  
    </div>

</body>

</html>