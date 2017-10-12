<?php
$acsess='true';
?>
<html>
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript">
        $(document).ready(function()
        {
            var navItems = $('.admin-menu li > a');
            var navListItems = $('.admin-menu li');
            var allWells = $('.admin-content');
            var allWellsExceptFirst = $('.admin-content:not(:first)');

            allWellsExceptFirst.hide();
            navItems.click(function(e)
            {
                e.preventDefault();
                navListItems.removeClass('active');
                $(this).closest('li').addClass('active');

                allWells.hide();
                var target = $(this).attr('data-target-id');
                $('#' + target).show();
            });
        });
    </script>
    <script type="text/javascript" src=""></script>

    <script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
    <link type="text/css" rel="stylesheet" href="style/adminstyle.css">
    <link type="text/css" rel="stylesheet" href="style/loading.css">


</head>
<body>
<div id="loader"><div class="containr">
        <div class="gearbox">
            <div class="overlay"></div>
            <div class="gear one">
                <div class="gear-inner">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
            <div class="gear two">
                <div class="gear-inner">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
            <div class="gear three">
                <div class="gear-inner">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
            <div class="gear four large">
                <div class="gear-inner">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
        </div>
        <h1>Loading...</h1>
    </div>
</div>
<div id="content" style="opacity:0.5" class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked admin-menu">
                <li class="active"><a href="#" data-target-id="home"><i class="fa fa-home fa-fw"></i>Home</a></li>
                <li><a  data-target-id="parsing_progress_list"><i class="fa fa-list-alt fa-fw"></i>Parsing</a></li>
                <li><a  data-target-id="pages"><i class="fa fa-file-o fa-fw"></i>Pages</a></li>
                <li><a  data-target-id="charts"><i class="fa fa-bar-chart-o fa-fw"></i>Charts</a></li>
                <li><a  data-target-id="table"><i class="fa fa-table fa-fw"></i>Table</a></li>
                <li><a  data-target-id="forms"><i class="fa fa-tasks fa-fw"></i>Forms</a></li>
                <li><a  data-target-id="calender"><i class="fa fa-calendar fa-fw"></i>Calender</a></li>
                <li id="err"><a  data-target-id="errors"><i class="fa fa-pencil fa-fw"></i>errors <div id="count_errors"> 0 </div></a></li>
                <li><a  data-target-id="to_delete"><i class="fa fa-book fa-fw"></i>To delete</a></li>
                <li><a  data-target-id="settings"><i class="fa fa-cogs fa-fw"></i>Settings</a></li>
            </ul>
        </div>
        <div class="col-md-9 well admin-content" id="home">
            <p>
                Hello! This is a forked snippet.<br>
                It is for users, which use one-page layouts.
            </p>
            <p>
                Here's the original one from BhaumikPatel: <a href="http://bootsnipp.com/snippets/featured/vertical-admin-menu" target="_BLANK">Vertical Admin Menu</a>
                <br>
                Thank you Baumik!
            </p>
        </div>
        <div class="col-md-9 well admin-content" id="parsing_progress_list">




            <div class="row">
                <div class="col-md-8">
                    <h2>Информация</h2>
                    <div class="row">
                        <div class="col-md-8">Ссылок найдено: </div>   <div id="quantity_urls" class="col-md-4">0</div>
                        <div class="col-md-8">Количество ссылок, ожидающие обработку:
                        </div>
                        <div class="col-md-4"><div id="quantity_urls_to_parsing">0</div></div>
                        <div class="col-md-8">Количество обработанных ссылок:
                            <div id="progress_quantity_parsed_urls" class="progress"><div class="progress-bar progress-bar-striped"></div></div>
                        </div>   <div  class="col-md-4"><div id="quantity_parsed_urls">0</div><div id="uploaded_products_time">0</div></div>

                        <div class="col-md-8">
                            Продуктов найдено:
                        </div>
                        <div id="quantity_found_products" class="col-md-4">
                            0
                        </div>

                        <div class="col-md-8">
                            Товаров загружено из магазина:
                            <div id="progress_quantity_downloaded_from_our_PL" class="progress"><div class="progress-bar progress-bar-striped"></div></div>
                        </div>
                        <div id="quantity_downloaded_from_our_PL" class="col-md-4">
                            0
                        </div>
                        <div class="col-md-8">Загружено товаров в магазин:
                            <div id="progress_goods_uploaded" class="progress"><div class="progress-bar progress-bar-striped"></div></div>
                        </div>
                        <div class="col-md-4"><div id="goods_uploaded">
                                0
                            </div>
                            <div id="updated_products_time">
                                0
                            </div>
                        </div>
                        <div class="col-md-8">Шаг выполнения: </div>   <div  id="status_updating" class="col-md-4"></div>
                        <div class="col-md-8">Времени прошло: </div>   <div  id="time_from_start" class="col-md-4">0</div>
                        <div class="col-md-8">Времени прошло  последнего действия: </div>   <div  id="time_last_updated" class="col-md-4">0</div>
                        <div class="col-md-8">Памяти используется: </div>   <div  id="memory_usage" class="col-md-4">0</div>
                        <div class="col-md-8">Последний продукт: </div>   <div  id="next_url_to_updating" class="col-md-4"><a target="_blank" href="">Link</a></div>
                        <div class="col-md-8">Статус обновления: </div>   <div id="continue_creating" class="col-md-4">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="start_buttons">
                        <div class="col-md-12">
                        <p><button id='startupdating' class="btn btn-success" >START UPDATING</button><button id='clear_all' class="btn btn-danger">CLEAR ALL</button></p>
                        </div>
                        <div class="col-md-12">
                        <p><button id='startspider' class="btn btn-success" >START SPIDER</button></p>
                        </div>
                        <div class="col-md-12">
                        <p>URL LIST <br><button id='start_spider' class="btn btn-success">UPDATE</button><button id='' class="btn btn-danger">CLEAR</button></p>
                        </div>
                        <div class="col-md-12">
                        <p>LIST OF PRODUCTS IN SHOP<br><button id='upload_our_PL' class="btn btn-success">UPLOAD</button><button id='upload_PL' class="btn btn-danger">CLEAR</button></p>
                        </div>
                        <div class="col-md-12">
                        <p>CREATE NEW PRODUCTS <br><button id='create_new_products' class="btn btn-success">UPLOAD</button><button id='startparsing' class="btn btn-danger">CLEAR</button></p>
                        </div>
                        <div class="col-md-12">
                            Обновлять :<br>
                        <input  type="checkbox" class="btn-primary" id='update_magia-sveta' checked>magia-sveta</p>
                        <input  type="checkbox" class="btn-success" id='update_antares' checked>antares</p>
                        <input  type="checkbox" class="btn-info" id='update_electra' checked>electra</p>
                        </div>
                    </div>
                    <div id="continue_buttons" >
                        <p><button id='continue_updating' >CONTINUE UPDATING</button></p>
                        <p><button id='pause_updating' >PAUSE UPDATING</button></p>
                        <p><button id='stop_updating' >STOP UPDATING</button></p>
                    </div>
                </div>
            </div>



        </div>
        <div class="col-md-9 admin-content" id="pages">
        </div>
        <div class="col-md-9 well admin-content" id="charts">
            Charts
        </div>
        <div class="col-md-9 well admin-content" id="table">
            Table
        </div>
        <div class="col-md-9 well admin-content" id="forms">
            Forms
        </div>
        <div class="col-md-9 well admin-content" id="calender">
            Calender
        </div>
        <div class="col-md-9 admin-content" id="errors">
        </div>
        <div class="col-md-9 well admin-content" id="to_delete">
            Library
        </div>
        <div class="col-md-9 well admin-content" id="settings">
            Settings
        </div>
    </div>
</div>
<script type="text/javascript" src="index.js"></script>
</body>
</html>