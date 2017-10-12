

    var settings = {
        original_stack:0,
        stack:  0,
        max: 0,
        step:0,
        update:'',
        time_of_start:'',
        schet_products_in_stack:0,
        schet_products_in_stack_up: function () {
            this.schet_products_in_stack+=1;
        },
        is_end:false,
        start_from:0,
        quantiti_products_to_updates:0
    };
    var ajax = {
        getproducts: function (offset,products_limit,fields='id,description') {
            $.ajax({
                type:'post',//тип запроса: get,post либо head
                url:'parser/products.php',//url адрес файла обработчика
                cache: false,
                data:{'what_to_do':'get','products_limit':products_limit,'products_offset':offset,'fields':fields},//параметры запроса
                response:'text',//тип возвращаемого ответа text либо xml
                async:true,
                error: function(){
                    console.log('ajax error on getting')
                    setTimeout(ajax.getproducts,5000,offset,products_limit,fields)
                },
                success:function (data) {//возвращаемый результат от сервера
                    parsing.processor(data)
                    create_record('#pages', 'loaded');
                }
            });
        },
        sendproducts: function (string) {
            $.ajax({
                type:'post',//тип запроса: get,post либо head
                url:'parser/products.php',//url адрес файла обработчика
                cache: false,
                data:{'what_to_do':'send','products':JSON.stringify(string)},
                response:'text',//тип возвращаемого ответа text либо xml
                async:true,
                error: function(){
                    console.log('ajax error on sending')
                    setTimeout(ajax.sendproducts,5000,string)
                },
                success:function (data) {
                    return ajax.processor();
                }
            });
        },
        processor: function(){
            if( settings.quantiti_products_to_updates > settings.step*settings.stack){
                if(((settings.step+1)*settings.stack)>settings.quantiti_products_to_updates){
                    var quantiti = settings.quantiti_products_to_updates-(settings.step*settings.stack);
                    settings.stack=quantiti;
                    settings.is_end=true;
                    this.getproducts((settings.step*settings.stack)+settings.start_from,quantiti);
                    settings.step++;
                }else if(!settings.is_end) {
                    this.getproducts((settings.step*settings.stack)+settings.start_from, settings.stack);
                    settings.step++;
                }else(end_of_updates());
            }else(end_of_updates());

            return
        },
        pars: function(url,products){
            $.ajax({
                type:'post',
                url:'parser/parser.php',
                cache: false,
                data:{'url':url},
                response:'text',
                async:true,
                error: function () {
                    setTimeout(ajax.pars,5000,url,products);
                },
                success:function (data) {
                    parsing.is_error(products,jQuery.parseJSON(data));
                    return
                }
            });
        },
        getproduct: function (id,error,fields='permalink,description') {
            $.ajax({
                type:'post',//тип запроса: get,post либо head
                url:'parser/products.php',//url адрес файла обработчика
                cache: false,
                data:{'what_to_do':'getproduct','product_id':id,'fields':fields},//параметры запроса
                response:'text',//тип возвращаемого ответа text либо xml
                async:true,
                success:function (data) {//возвращаемый результат от сервера
                    var dat = jQuery.parseJSON(data);
                    dat['description']=url_pars(dat['description']);
                    return error_product_contiune(id,error,dat);
                }
            });
        },
        remove_product: function (id) {
            $.ajax({
                type: 'post',//тип запроса: get,post либо head
                url: 'parser/products.php',//url адрес файла обработчика
                cache: false,
                data: {'what_to_do': 'remove_product', 'product_id': id},//параметры запроса
                response: 'text',//тип возвращаемого ответа text либо xml
                async: true,
                success: function (data) {//возвращаемый результат от сервера
                    return;
                }
            });
        },
        getcount: function () {
            $.ajax({
                type: 'post',//тип запроса: get,post либо head
                url: 'parser/products.php',//url адрес файла обработчика
                cache: false,
                data: {'what_to_do': 'getcount'},//параметры запроса
                response: 'text',//тип возвращаемого ответа text либо xml
                async: true,
                success: function (data) {//возвращаемый результат от сервера
                    $('#max').attr('value',data)
                    return;
                }
            });
        }
    };

    var parsing = {
        processor: function (products) {
            this.start(jQuery.parseJSON(products))
        },
        start: function (products) {
            if (settings.schet_products_in_stack < settings.stack) {
                update_indicators_mini()
                var url = products[settings.schet_products_in_stack]['description'];
                ajax.pars(url_pars(url),products);
            } else {
                settings.schet_products_in_stack = 0;
                ajax.sendproducts(products);
            }
        },
        is_error: function(products,data){
            if('error' in data){
                error_product(products[settings.schet_products_in_stack]['id'],data['error']);
                data['stock_quantity']=0;
                data['regular_price']=0;
                data['sale_price']='';
                delete data['error']
            }
            if(data['stock_quantity']==NaN || data['stock_quantity']==null || data['regular_price']==NaN || data['regular_price']==null){
                error_product(products[settings.schet_products_in_stack]['id'],'Полученые данные неверны');
                data['stock_quantity']=0;
                data['regular_price']=0;
                data['sale_price']='';
                delete data['error']
            }
            this.start_cont1(products,data)

        },
        start_cont1: function (products, data) {

            products[settings.schet_products_in_stack]['description'] = this.processor_of_position_updates(data);
            settings.schet_products_in_stack_up();
            this.start(products, settings.schet_products_in_stack);
        },
        processor_of_position_updates: function (data) {
            if(settings.update == 'all'){
                return(data);
            }else if(settings.update == 'quantiti'){
                delete data["regular_price"];
                return data;
            }else if(settings.update == 'prise'){
                delete data["stock_quantity"];
                return data;
            }else return
        }
    };
    $('#startparsing').on("click",function(){
        this.disabled = true;
        start_parsing();
        var stack = $('#stack').val();
        if(stack > 400){
            stack=400;
            $('#stack').val(400)
        }
        settings.time_of_start=moment();
        settings.original_stack=stack;
        settings.stack=stack;
        settings.max = parseInt($('#max').val());
        settings.update = $( "input:checked" ).data('update');
        settings.start_from = parseInt($('#start_from').val());
        settings.quantiti_products_to_updates=settings.max-settings.start_from

        ajax.processor();
    });
    function end_of_updates() {
        StartStop();
        alert('Обновление закончено за '+ $('#indicators_plase_mini_clock').html()+  '\n'+'обновлено '+settings.quantiti_products_to_updates+' товаров');
        $('#indicators_fixed').remove();
        $('#indicators_mini').remove();
        $('.indicators_plase_mini').remove();
        settings.original_stack=0;
        settings.stack=0;
        settings.original_stack=0;
        settings.max=0;
        settings.step=0;
        settings.update='';
        settings.time_of_start='';
        settings.schet_products_in_stack=0;
        settings.is_end=false;
        $('#startparsing')[0].disabled = false;
    }

    function start_parsing(){

        var menu = document.createElement('li');
        menu.className = 'indicators_plase_mini';
        menu.innerHTML='<a class="indicators_plase_mini" >Прошло:  <span id="indicators_plase_mini_clock"></span></a>';
        $('.admin-menu').append(menu);

        var menu = document.createElement('li');
        menu.className = 'indicators_plase_mini';
        menu.innerHTML='<a class="indicators_plase_mini" >Осталось:  <span id="indicators_plase_mini_clockback"></span></a>';
        $('.admin-menu').append(menu);

        var menu = document.createElement('li');
        menu.className = 'indicators_plase_mini';
        menu.innerHTML='<a class="indicators_plase_mini"  id="indicators_plase_mini_upload"></a>';
        $('.admin-menu').append(menu);

        var menu = document.createElement('li');
        menu.className = 'indicators_plase_mini';
        menu.innerHTML='<a class="indicators_plase_mini" id="indicators_plase_mini_updated"></a>';
        $('.admin-menu').append(menu);

        settings.time_of_start=moment();
        StartStop();
        var indicators_fixed = document.createElement('div');
        indicators_fixed.className = 'indicators indicators_fixed';
        indicators_fixed.id = 'indicators_fixed';
        indicators_fixed.innerHTML='<div id="indicators_fixed_progress"></div>';
        $('body').append(indicators_fixed);

    }
    function update_indicators_mini(){
        var progress_products = (settings.step-1)*settings.original_stack+(settings.schet_products_in_stack+1);
        time = new Date();
        $('#indicators_plase_mini_upload').html('Загружено: '+progress_products);
        $('#indicators_plase_mini_updated').html( 'Обновлено: '+(settings.step-1)*settings.original_stack);

        $('#indicators_fixed_progress').animate({width:((100/settings.quantiti_products_to_updates)*progress_products)+'%'},300)

    }
    function create_record(plase, text){
        var record = document.createElement('div');
        record.className = 'well added_record';
        record.innerHTML = text;
        $(plase).prepend(record);
    };
    function error_product(id,error){
        return ajax.getproduct(id,error);
    }
    function error_product_contiune(id,error,data){
        var url_us = data['permalink'];
        var url_shop = data['description'];
        indicators.count_errors.up();
        var error_product='№'+indicators.count_errors.value+' При обновлении <a href="'+
            url_us+'" target="_blank">товара</a> с id = '+
            id+', от <a href="'+url_shop+'" target="_blank">поставщика</a> выдал ошибку: '+
            error+' <button class="btn btn-danger" id="delete_product" data-id="'+id+'">удалить?</button>'

        create_record('#errors',error_product)
    }
    function url_pars(url) {
        myRe = /http:\/\/([a-zA-Z0-9\/\-._])*/ig
        url = myRe.exec(url);
        return url[0]
    }
    $('#errors').on("click", '#delete_product',function(){
        indicators.count_errors.down();
        var id = $(this).data(id);
        ajax.remove_product(id.id);
        $(this).parent().hide(1500);
        $(this).parent().delay(1500);
    });




    $(function () {
        ajax.getcount()

    });

