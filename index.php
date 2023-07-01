<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();
?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/d1cdf3c945.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/style_form.min.css">
    </head>
    <body>

        <div class="container card mt-4">
            <div class="row row-header">
                <div class="col-12">
                    <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                    <h1>Прокат</h1>
                </div>
            </div>

            <!-- TODO: реализовать форму расчета -->
            <div class="row row-form">
                <div class="col-12 ">
                    <h4>Форма расчета:</h4>
                    <div class="container">
                        <div class="row row-body">
                            <div class="col-9">
                                <form action="" id="form">
                                        <label class="form-label" for="product">Выберите продукт:</label>
                                        <select class="form-select" name="product" id="product">
                                            <?php 
                                                $product = $dbh->make_query("SELECT * FROM `a25_products`");
                                                foreach ($product as $key => $itemProduct) : ?>
                                                    <option value="<?= $itemProduct['ID']?>"><?= $itemProduct['NAME'] . ' за '. $itemProduct['PRICE'] ?> </option>
                                                <? endforeach; ?>
                                             ?>
                                        </select>
                                        
                                        <label for="customRange1" class="form-label">Количество дней:</label>
                                        <span class="errorsInput"></span>
                                        <input type="number" name="days" class="form-control" id="customRange2" min="1" max="30">

                                        <label for="customRange1" class="form-label">Дополнительные услуги:</label>
                                        <?
                                            $i = 0;
                                            $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                                            foreach ($services as $k => $s) : ?>
                                                <?php
                                                  $i++
                                                ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="dop-<?= $i ?>" type="checkbox" value="<?= $s ?>" id="flexCheckChecked<?=$k?>">
                                                    <label class="form-check-label" for="flexCheckChecked<?= $i ?>">
                                                        <?= $k . ': ' . $s?>
                                                    </label>
                                                </div>
                                            <? endforeach; ?>
                                        <button type="submit" name="do_send" class="btn btn-primary btn_send">Рассчитать</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loader p-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="loader-body">
                                <button class="btn btn-primary" type="button" disabled>
                                  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                  Расчитываю...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="alert-container">
            <!-- Алерт с предупреждением -->
            <div id="alert" class="alert alert-danger alert-dismissible fade" role="alert">
                <span class="titleAlert"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
            <div class="card mb-4" id="result" style="display: none;">
                <div class="card-header">
                    <h3>Результат</h3>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row" id="resultCalc">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>