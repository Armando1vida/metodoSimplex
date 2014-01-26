<DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Método Simplex</title>
            <link rel="stylesheet" href="../css/bootstrap.min.css">
            <script type="text/javascript" src="../js/bootstrap.min.js" ></script>
            <script type="text/javascript" src="../js/jquery-1.10.2.js" >
            </script>
            <script type="text/javascript">
                $(function() {
                    $('input[type="number"]').val(0);
//                    $('input').limitkeypress({rexp: /^(?:\+|-)?[0-9]+(.([0-9]+)|\/([0-9]+))?$/});
//                    $("#h").limitkeypress({rexp: /^[+]?\d*$/});

//                    $("#h").limitkeypress({rexp: /^[A-Za-z]*$/});

//                    var regex = /^(?:\+|-)?[0-9]+(.([0-9]+)|\/([0-9]+))?$/;
// OR this one if you want uppercase letters:
//                    var regex = /^[A-Z\x08\?]$/;
//                    $('input[type="number"]').keypress(function(event) {
//                        var _event = event || window.event;
//                        var key = _event.keyCode || _event.which;
////                        alert(keyinput[type="number"]
//                        key = String.fromCharCode(key);
////                        alert(key);
//                        if (regex.test(key)) {
//                            _event.returnValue = false;
//                            if (_event.preventDefault)
//                                _event.preventDefault();
//                        }
//                    });
//                    $('input[type="number"]').attr('onkeypress', 'return permite(event, "num")');
//                    $('input[type="number"]').numeric();
//                    $('input[type="number"]').numeric(",");
//                    $('input[type="number"]').numeric("/");

//                    $('input[type="number"]').on('blur', function() {
//                        alert($(this).val());
//                        if (!parseInt($(this).val())) {
////                            $(this).val();
//                            alert($(this).val());
////                         var array=($(this).val()).split("/");
////                         alert(array[0]);
////                         $(this).val(array[0]/array[1]);
//                        } else {
//
//                        }
////                        
//                    })

                });
//                function permite(even, permitidos) {
////                    var numeros = "0123456789/";
//                    var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
//                    var numeros_caracteres = numeros + caracteres;
//                    var teclas_especiales = [8, 37, 39, 46];
//// 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
//// Seleccionar los caracteres a partir del parámetro de la función
//                    switch (permitidos) {
//                        case 'num':
//                            permitidos = numeros;
//                            break;
//                        case 'car':
//                            permitidos = caracteres;
//                            break;
//                        case 'num_car':
//                            permitidos = numeros_caracteres;
//                            break;
//                    }
//// Obtener la tecla pulsada
//                    var evento = even || window.event;
//                    var codigoCaracter = evento.charCode || evento.keyCode;
//                    var caracter = String.fromCharCode(codigoCaracter);
//// Comprobar si la tecla pulsada es alguna de las teclas especiales
//// (teclas de borrado y flechas horizontales)
//                    var tecla_especial = false;
//                    for (var i in teclas_especiales) {
//                        if (codigoCaracter == teclas_especiales[i]) {
//                            tecla_especial = true;
//                            break;
//                        }
//                    }
//// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
//// o si es una tecla especial
//                    return permitidos.indexOf(caracter) != -1 || tecla_especial;
//
//                }
                function enviar() {
                    datos = $('form').serialize();
                    $.ajax({
                        data: datos,
                        url: 'calcular.php',
                        type: 'post',
                        beforeSend: function() {                            
                              $("#contenedor").html("Calculando, espere por favor...");
                       },
                        success: function(respuesta) {
                            $("#contenedor").html(respuesta);
                        }
                    });
                }
            </script>
        </head>
        <body>   
            <div class="container">
                <?php
                if (!empty($_POST)) {
                    $num_variables_desicion = $_POST{'num_variable_desicion'};
                    $num_restricciones = $_POST{'num_restricciones'};
                    $i = 0;
                    $indexMatriz = 0;
                    ?>


                    <br>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h1>Ingresar Datos:</h1>
                            <div class="row">  
                                <form  action="calcular.php" method="post" >
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <?php for ($index = 0; $index < (int) $num_variables_desicion; $index++) { ?>
                                                    <th>
                                                        X<?php echo $index + 1 ?>
                                                    </th>
                                                <?php } ?>
                                                <th></th>
                                                <th>R</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><abbr title="Función Objetivo (Z)">FO</abbr></td>
                                                <?php for ($index = 0; $index < (int) $num_variables_desicion; $index++) { ?>
                                                    <td>
                                                        <input type="number"   step="any" required="true" class="form-control" name=<?php echo "m[$indexMatriz][$index]" ?>  placeholder="">
                                                    </td>
                                                <?php } $indexMatriz++; ?>
                                                <td></td>
                                                <td>                                                   
                                                    <input class="hidden" type="number"   step="any" required="true" class="form-control" name=<?php echo "m[0][$num_variables_desicion]" ?>  placeholder="">
                                                </td>
                                            </tr>
                                            <?php for ($index1 = 0; $index1 < (int) $num_restricciones; $index1++) { ?>
                                                <tr>
                                                    <td>
                                                        <abbr title="Resticción <?php echo $index1 + 1; ?>">R<?php echo $index1 + 1; ?></abbr>
                                                    </td>
                                                    <?php for ($index = 0; $index < (int) $num_variables_desicion + 2; $index++) { ?>
                                                        <?php if ($index == (int) $num_variables_desicion) { ?>
                                                            <th>
                                                                <label for="">&leqq;</label>
                                                            </th>                                              
                                                        <?php } else { ?>
                                                            <td>
                                                                <input type="number" step="any" required="true" class="form-control" name=<?php echo "m[$indexMatriz][$i]"; ?>  placeholder="">
                                                            </td>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>   
                                                        <?php $i = $i == (int) $num_variables_desicion + 1 ? 0 : $i; ?>
                                                    <?php } ?>              
                                                </tr>
                                                <?php
                                                $indexMatriz++;
                                            }
                                            ?> 
                                        </tbody>
                                    </table>
                            </div>

                            <div class="row" >
                                <div class="col-md-4 col-md-offset-1">
                                    <br>
                                    <br>
                                    <br>
                                    <button type="button" onclick="enviar()"  class="btn btn-info btn-default btn-block">Calcular</button>
                                    <a href="../index.php" class="btn btn-success btn-default btn-block" role="button" >Atras</a>

                                </div>

                                <div class="col-md-4 col-md-offset-2" id="contenedor">


                                </div>
                            </div>


                            <br>
                            <br>
                            <input type="hidden" value="<?php echo $num_restricciones ?>" name="num_restricciones">
                            <input type="hidden" value="<?php echo $num_variables_desicion ?>" name="num_variable_desicion">


                            </form>

                        </div>

                    </div>


                    <div class="row">



                    </div>




                <?php } else {
                    ?>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <br>
                            <br>
                            <br>
                            <br>
                            <br><br>
                            <div class="jumbotron">
                                <h1>Ocurrió un problema</h1>
                                <p>Para empezar de nuevo dale click en el botón porfavor.</p>
                                <p><a class="btn btn-primary btn-lg" href="../index.php" role="button">Atrás</a></p>
                            </div>
                        </div>

                    </div>


                    <!--                header("Location:../index.php");-->
                <?php } ?>





                <div class="row">
                    <div class="col-sm-3 col-sm-offset-2">

                        <div class="navbar navbar-default navbar-fixed-bottom">
                            <div class="container">
                                <p class="navbar-text">Autor: <strong>Armando Maldonado Conejo</strong>  </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>



