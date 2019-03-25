<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/jquery.mobile-1.4.5.css">
<script src="js/jquery.js"></script>
<script src="js/jquery.mobile-1.4.5.js"></script>

<!-- Include meta tag to ensure proper rendering and touch zooming -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Include jQuery Mobile stylesheets -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

<!-- Include the jQuery library -->
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Include the jQuery Mobile library -->
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>


</head>
<body>
<div data-role="page" id="pagefirst" data-rel="dialog" data-theme="b">
  <div data-role="header">
    <h1>Inventario Klonal</h1>
  </div>
  <div data-role="main" class="ui-content">
<?php 
// setear zona horaria
date_default_timezone_set(ÚTC);

// creamos la conexion con la DB 
// sqlite en este caso
// vee otrs DSN para mysql
// o usar include.php

// funcion que busca en la BBDD
function existe($numero){
    // Create (connect to) SQLite database in file
    try {
        $dbh = new PDO('sqlite:stock.db');
        // Set errormode to exceptions
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // consulta
        $sql = "SELECT id FROM inventario WHERE stk = :numero";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':numero', $numero);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        //var_dump($fila);
        if ($fila) {
            return true;
        } else {
            return false;    
        }        
    
    } catch (Exception $e) {
        echo 'Error en la base de datos';
        echo $e->getMessage();
        die();
    }
}

// funcion que trae los datos
function get_stk($numero){
    // Create (connect to) SQLite database in file
    try {
        $dbh = new PDO('sqlite:stock.db');
        // Set errormode to exceptions
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // consulta
        $sql = "SELECT stk,serial,ip,fecha  FROM inventario WHERE stk = :numero;";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':numero', $numero, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado;    
    
    } catch (Exception $e) {
        echo 'Error en la base de datos';
        echo $e->getMessage();
        die();
    }


    return $resultado;
}
// verificamos el parametro get
if (!empty($_GET)) {
    // si no llega vacio vemos si esta el key correcto
    if (isset($_GET["stk"])) {
        // lo convertimos a un entero
        $stk = (int)$_GET["stk"];
        //echo "El stock number es: ".$stk;
        // el numero aqui es valido
        // ahora hay que ver si existe en la BBDD
        if (existe($stk)) {
            // si existe mostrarlo
            $ver = get_stk($stk);
            //var_dump($ver); 
?>
        <label for="stk">Numero de stock:</label>
        <input type="text" name="stk" id="stk" value=" <?php echo $ver['stk'] ; ?> " readonly>
        
        <label for="nombre">Numero de Serie:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo $ver['serial'] ; ?> " readonly>

        <label for="ip">IP:</label>
        <input type="text" name="ip" id="ip" value=" <?php echo $ver['ip'] ; ?> " readonly>

        <label for="fecha">Fecha:</label>
        <input type="text" name="fecha" id="fecha" value=" <?php echo $ver['fecha'] ; ?> " readonly>

        <a href="#anylink" class="ui-btn">Editar</a>
<?php  
            
        } else {
            // y si no pedir usuario y contraseña
            // para agregarlo
   
        }
    } else {
        echo "Datos o lectura  incorrectos";
    }
        
        
}else{
    echo "No se han encontrado datos para la busqueda ¿Es uan etiqueta de Klonal? ";
}
?>
  </div>
  <div data-role="footer">
    <h1>Copyright 2019 Sistemas</h1>
  </div>
</div> 
</body>
</html>

