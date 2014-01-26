<?php //
//echo 'hola';
$matriz_Resultado = maximizar($_POST);
//echo imprimir_resultados($matriz_Resultado);
//echo valores_respuesta($matriz_Resultado);
$r = valores_respuesta($matriz_Resultado);
echo mostrar_resultado($r);
//echo imprimir($matriz_Resultado);
//imprimir($matriz_Resultado);
//var_dump($matriz_Resultado);
//var_dump($matriz_Resultado);

function maximizar($POST) {
    $matriz = preparar($POST);
//    imprimir
    echo '<br><br>';
    $variable_desicion = $POST['num_variable_desicion'];
    array_push($matriz, array('num_variable_desicion' => $variable_desicion));
//        echo '-------------------';
    echo imprimir_resultados($matriz);
    array_pop($matriz);
//    fin Imprimir
    
//    var_dump($matriz);
//    die();
    $numero_filas = count($matriz[0]) - 1;
    $variable_desicion = $POST['num_variable_desicion'];
    do {
//        $filaFO = fila_funcion_objetivo($matriz, $variable_desicion);
        $filaFO = fila_funcion_objetivo($matriz);
        $minimo_funcion_objetivo = minimo_funcion_objetivo($filaFO);
        if ($minimo_funcion_objetivo == null) {
            continue;
        }
        $numero_columna_pivote = buscar($filaFO, $minimo_funcion_objetivo);
        $columna_pivote = array_column($matriz, $numero_columna_pivote);
        $columa_r = array_column($matriz, $numero_filas);
        $columa_resultado = columna_resultado($columna_pivote, $columa_r);
        $minR = minimo_columnaR($columa_resultado);
        $numero_fila_pivote = buscar($columa_resultado, $minR);
        $matriz = calcular($matriz, $numero_fila_pivote, $numero_columna_pivote);
//        imprimir($matriz);
        
//        imprimir
        array_push($matriz, array('num_variable_desicion' => $variable_desicion));
//        echo '-------------------';
        echo imprimir_resultados($matriz);
        array_pop($matriz);
//        fin Imprimir
        
    } while (!$minimo_funcion_objetivo == null);
    array_push($matriz, array('num_variable_desicion' => $variable_desicion));

    return $matriz;
}

/**
 * Prepara la maatriz para sus calculos; el paramero de entrada vienen 
 * por Post.
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param type $array_compuesto
 * @param type $numero_variables_decision
 * @return {array compuesto - matriz}
 */
function preparar($array_compuesto) {
    $numero_variables_decision = $array_compuesto['num_variable_desicion'];
    $numero_restricciones = $array_compuesto['num_restricciones'];
    $array_compuesto = $array_compuesto['m'];
//    echo "nr= $numero_restricciones--------nvd=$numero_variables_decision";
//    var_dump($array_compuesto);
    $condicion = count($array_compuesto[0]) + $numero_restricciones - 1;
    $finalarray = array_pop($array_compuesto[0]);
    for ($index = 0; $index < $condicion; $index++) {
        if ($index < count($array_compuesto[0])) {
            $array_compuesto[0][$index] = $array_compuesto[0][$index] * -1;
        } else {
            array_push($array_compuesto[0], 0);
        }
    }
    array_push($array_compuesto[0], $finalarray);
//    ingresa 1 para representar Z
    array_unshift($array_compuesto[0], 1);
    $condicionR = count($array_compuesto);
    for ($index1 = 1; $index1 < $condicionR; $index1++) {
        array_unshift($array_compuesto[$index1], 0);
//        Extrae el ultimo elemento el cual es R
        $finalarra = array_pop($array_compuesto[$index1]);
        for ($index2 = 1; $index2 <= $numero_restricciones; $index2++) {
            if ($index1 == $index2) {
                array_push($array_compuesto[$index1], 1);
            } else {
                array_push($array_compuesto[$index1], 0);
            }
        }
//        Inserto el de R
        array_push($array_compuesto[$index1], $finalarra);
    }
//    imprimir($array_compuesto);
    return $array_compuesto;
}

/**
 * Retorna el valor mas negativo del array(Función obejetivo)
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param {array} $array
 * @return {int}
 */
function minimo_funcion_objetivo(Array $array) {
//    Si hay valores negativos retorneme el minimo caso contrario retorneme null
    return min($array) < 0 ? min($array) : null;
}

/**
 * Retorna el menor valor de la columna de r
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param {array} $array
 * @return {int}
 */
function minimo_columnaR(Array $array) {
//    Primero filtra los  ceros para que no intervengan en la busqueca de menor
//    Retorna el menor caso contrario retorna null.
    return !count(array_diff(array_map('floatval', $array), array(0))) == 0 ? min(array_diff(array_map('floatval', $array), array(0))) : null;
}

/**
 * Lo que realiza esta función es buscar el valor de busqueda 
 * y nos retorna en key del array que contiene dicho valor
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param {array} $array
 * @param {string o int} $variable(busqueda)
 * @return type
 */
function buscar(Array $array, $variable) {
    return array_search($variable, $array);
}

/**
 * Lo que hace esta funcion es dividir los valores de la columna pivote para 
 * la columna de R
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param {array} $columna_pivote
 * @param {array} $columa_r
 * @return array
 */
function columna_resultado(Array $columna_pivote, Array $columa_r) {
    $columa_resultado = array();
    for ($index = 0; $index < count($columna_pivote); $index++) {
        if ($index == 0) {
            //para la primera fila(Funcion Obtejivo);para que este valor no se tome en cuenta
            array_push($columa_resultado, 0);
        } else {
            if ($columna_pivote[$index] == 0) {
                //para evitar division pra cero y tambien para que este valor no se tome en cuenta en la busqueda del menor de la columna resultado
                array_push($columa_resultado, 0);
            } else {
                array_push($columa_resultado, ($columa_r[$index] / $columna_pivote[$index]) < 0 ? 0 : ($columa_r[$index] / $columna_pivote[$index]));
            }
        }
    }
    return $columa_resultado;
}

/**
 * Nos retorna solo los valores( del array z), que van a intervenir en la busqueda del minimo
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param {array bidimensional} $array_compuesto
 * @param {int} $numero_variable
 * @return {array}
 */
function fila_funcion_objetivo($array_compuesto) {
    //La razón po la cual sumo +1 es por que se aumenta la fila Z al principio de la matriz
//    return array_slice($array_compuesto[0], 0, $numero_variable + 1);
    return $array_compuesto[0];
}

/**
 * Debe ser declarada en php <= 5.4+
 * Esta función lo que hace es sacar la columna deseada de un a array asociativo
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param {array compuesto de preferencia} $array 
 * @param {int o string} $nombre_columna
 * @return array
 */
function array_column(Array $array, $nombre_columna) {
    $resultado = array();
    if (!is_array($array))
        return $resultado;
    foreach ($array as $hijos) {
        if (!is_array($hijos))
            continue;
        if (array_key_exists($nombre_columna, $hijos)) {
            $resultado[] = $hijos[$nombre_columna];
        }
    }
    return $resultado;
}

/**
 * Hace ceros los valores de la columna pivote y calcular los demas valores respectivamente 
 * @author Armando Maldonado Conejo<armand1live@gmail.com>
 * @param {Array compuesto} $matriz
 * @param {int}$numero_fila_pivote
 * @param {int} $numero_columna_pivote
 * @return {Array compuesto} $matriz
 */
function calcular($matriz, $numero_fila_pivote, $numero_columna_pivote) {
    //Martiz[fila][columna]
    $numero_pivote = $matriz[$numero_fila_pivote][$numero_columna_pivote];
    $para_hacer_uno = $numero_pivote < 0 ? ( 1 / ($numero_pivote)) * -1 : 1 / $numero_pivote;
//    Hacer uno numero pivote y todos los valores se afectan de esta fila
    for ($index = 0; $index < count($matriz[0]); $index++) {
        $matriz[$numero_fila_pivote][$index] = $matriz[$numero_fila_pivote][$index] * $para_hacer_uno;
    }
    $numero_pivote = $matriz[$numero_fila_pivote][$numero_columna_pivote];
//    Almacena los valores por la cual van a hacer ceros la columna_pivote
    $columna_pivote = array_column($matriz, $numero_columna_pivote);
    for ($index1 = 0; $index1 < count($columna_pivote); $index1++) {
        $columna_pivote[$index1] = $columna_pivote[$index1] * -1;
    }
//    Calcula los valores y hace cero la columna_pivote de la matriz
    for ($fila = 0; $fila < count($matriz); $fila++) {
        for ($columna = 0; $columna < count($matriz[0]); $columna++) {
            //Cuando la fila sea la fila pivote no haga nada y continue al siguiente ciclo
//            Caso contrario calcular valores
            if ($fila == $numero_fila_pivote) {
                continue;
            } else {
                $matriz[$fila][$columna] = $matriz[$numero_fila_pivote][$columna] * $columna_pivote[$fila] + $matriz[$fila][$columna];
            }
        }
    }
    return $matriz;
}

function imprimir($matriz) {
    foreach ($matriz as $value) {
        foreach ($value as $v) {
            echo $v . '&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        echo '<br>';
    }
}

function valores_respuesta($matriz) {
    $respuesta_array = array();
//    imprimir_resultados($matriz);
    $num_variabled = array_pop($matriz);
    $num_variabled = $num_variabled['num_variable_desicion'];
    $respuesta = "";
    $arrayrespuesta = array();
    for ($index = 0; $index <= $num_variabled; $index++) {
        $columna = array_column($matriz, $index);
        
        $num_fila = count(array_unique($columna)) == 2 ? buscar($columna, 1) : null;
        if ($index == 0) {
            array_push($respuesta_array, array("Z" => $matriz[$num_fila][count($matriz[0]) - 1]));
            $respuesta = $respuesta . "Z =" . $matriz[$num_fila][count($matriz[0]) - 1] . '<br>';
        } else {
            if (!$num_fila == null) {
                array_push($respuesta_array, array("X$index" => $matriz[$num_fila][count($matriz[0]) - 1]));
                $respuesta = $respuesta . "X$index =" . $matriz[$num_fila][count($matriz[0]) - 1] . '<br>';
            } else {
                array_push($respuesta_array, array("X$index" => 0));
                $respuesta = $respuesta . "X$index =0 <br>";
            }
        }
//        var_dump($columna);
//        if ($index == 0) {
//            $respuesta = $respuesta . 'Z =' . $matriz[$num_fila][count($matriz[0])];
//        }
//        if ($index <= $num_variabled) {
//            if ($index == 0) {
//               echo  $matriz[$index][count($matriz[0])];
//                $respuesta = $respuesta . 'Z =' . $matriz[$index][count($matriz[0] - 1)];
//            } else {
//                $respuesta = $respuesta . "X$index =" . $matriz[$index][count($matriz[$index] - 1)];
//            }
//        }
//        if ($index == count($matriz[0]) - 1) {
//            array_push($arrayrespuesta, array_column($matriz, $index));
//        }
    }
//    var_dump($respuesta_array);
    return $respuesta_array;

//    echo $respuesta;
//    array_push($arrayrespuesta, array('vd' => $num_variabled));
//    var_dump($arrayrespuesta);
//    imprimir($arrayrespuesta);
//    imprimir_resultados($arrayrespuesta);
}
?>
<?php

/**
 * Nos retorna los resultados
 * @author Armando Maldonado Conejo <armand1live@gmail.com>
 * @param array $resultado
 */
function mostrar_resultado(Array $resultado = null) {
    ?>

    <h4>Resultados</h4>

    <table class="table table-condensed  table-hover">
        <?php
        foreach ($resultado as $value) {
            foreach ($value as $key => $valor) {
                ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td>=</td>
                    <td><?php echo $valor; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>


<?php } ?>



<?php

function imprimir_resultados($matriz_resultado) {
    $num_variabled = array_pop($matriz_resultado);
    $num_variabled = $num_variabled['num_variable_desicion'];
    $h = 1;
    ?>
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <?php for ($index = 0; $index < count($matriz_resultado[0]); $index++) { ?>
                    <?php if ($index == 0) { ?>
                        <th>Z</th>
                    <?php } else if ($index == count($matriz_resultado[0]) - 1) { ?>                    
                        <th>
                            R
                        </th>
                    <?php } else if ($index > $num_variabled && $index < count($matriz_resultado[0]) - 1) { ?>  
                        <th>
                            H<?php
                            echo $h;
                            $h++;
                            ?>
                        </th>
                    <?php } else {
                        ?>
                        <th>
                            X<?php echo $index ?>
                        </th>
                        <?php
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($index1 = 0; $index1 < count($matriz_resultado); $index1++) { ?>
                <tr>
                    <?php for ($index = 0; $index < count($matriz_resultado[0]); $index++) { ?>
                        <td>
                            <?php echo $matriz_resultado[$index1][$index]; ?>
                        </td>
                    <?php } ?>  
                </tr>
            <?php } ?> 
        </tbody>
    </table>

<?php } ?>


