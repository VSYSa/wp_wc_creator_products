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
                        <div class="col-md-8">Товаров в магазине: </div>   <div id="quantiti_products" class="col-md-4">0</div>
                        <div class="col-md-8">Загружено товаров в базу:
                            <div id="progress_uploaded_products" class="progress"><div class="progress-bar progress-bar-striped"></div></div>
                        </div>   <div  class="col-md-4"><div id="uploaded_products">0</div><div id="uploaded_products_time">0</div></div>
                        <div class="col-md-8">Товары с обновленной информацией:
                            <div id="progress_updated_products_information" class="progress"><div class="progress-bar progress-bar-striped"></div></div>
                        </div>   <div class="col-md-4"><div id="updated_products_information">0</div><div id="updated_products_information_time">0</div></div>
                        <div class="col-md-8">Загружено товаров в магазин:
                            <div id="progress_updated_products" class="progress"><div class="progress-bar progress-bar-striped"></div></div>
                        </div>   <div class="col-md-4"><div id="updated_products">0</div><div id="updated_products_time">0</div></div>
                        <div class="col-md-8">Времени прошло: </div>   <div  id="time_from_start" class="col-md-4">0</div>
                        <div class="col-md-8">Времени осталось: </div>   <div id="time_to_end" class="col-md-4">0</div>
                        <!--<div class="col-md-8">Шаг обновления: </div>   <div id="status_step_updating" class="col-md-4">0</div>-->
                        <div class="col-md-8">Статус обновления: </div>   <div id="status_updating" class="col-md-4">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="start_buttons">
                        <p><button id='startparsing' >START UPDATING</button></p>
                        <p><button id='update_PL' >UPDATE PRODUCTS LIST</button></p>
                        <p><button id='update_PI' >UPDATE PRODUCT INFORMATION</button></p>
                        <p><button id='upload_PL' >UPLOAD PRODUCTS LIST</button></p>
                        <input  type="radio" name="answer" data-update='quantiti' >количество товаров</p>
                        <input  type="radio" name="answer" data-update='prise' >цену товаров</p>
                        <input  type="radio" name="answer" data-update='all' checked>все</p>
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