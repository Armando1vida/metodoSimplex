<DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Método Simplex</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <body>
            <div class="container">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="page-header text-center">
                            <h1>Maximización (Método Simplex)</h1>
                        </div>
                        <form class="form-horizontal" role="form" action="pages/llenarTabla.php" method="post">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-8 control-label">Número de variables de desición:</label>
                                <div class="col-sm-2">
                                    <input type="number" required="true" class="form-control" name='num_variable_desicion'  placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-8 control-label">Número de restricciones:</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" required="true" name="num_restricciones" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-offset-4 col-sm-4">
                                        <button type="submit" class="btn btn-primary">Continuar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

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
            <script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
            <script type="text/javascript" src="js/bootstrap.min.js" ></script>
        </body>
    </html>
