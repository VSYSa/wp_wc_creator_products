
var base = 60;
var clocktimer,clocktimerback,dateObj,dh,dm,ds,ms;
var readout='';
var h=1,m=1,tm=1,s=0,ts=0,ms=0,init=0;
function dop_z(varr){
    if(varr<=9 && varr>=0){
        return ('0'+varr);
    }
    return varr;
}
function time_to_end(){
    var edd=0,edh=0,edm=0,eds=0;
    var time='';
    var progress_products = (settings.step-1)*settings.original_stack+(settings.schet_products_in_stack+1);
    var time_in_second = Math.floor(((((moment()-settings.time_of_start)/progress_products)*(settings.quantiti_products_to_updates-progress_products))/1000));

    eds=time_in_second;
    while((eds/60)>=1){
        edm+=Math.floor(eds/60);
        eds=Math.floor(eds%60);
    }
    while((edm/60)>=1){
        edh+=Math.floor(edm/60);;
        edm=Math.floor(edm%60);
    }
    while((edh/24)>=1){
        edd+=Math.floor(edh/24);
        edh=Math.floor(edh%24);
    }

    time =  dop_z(edd) + ':' +dop_z(edh) + ':' + dop_z(edm) + ':' + dop_z(eds);

    $('#indicators_plase_mini_clockback').html(time);
    clocktimerback = setTimeout("time_to_end()",2000);
}
function ClearСlock() {
    clearTimeout(clocktimer);
    clearTimeout(clocktimerback);
    h=1;m=1;tm=1;s=0;ts=0;ms=0;
    init=0;
    readout='00:00:00.00';
    $('#indicators_plase_mini_clock').html(readout);
}
//функция для старта секундомера
function StartTIME() {
    var cdateObj = new Date();
    var t = (cdateObj.getTime() - dateObj.getTime())-(s*1000);
    if (t>999) { s++; }
    if (s>=(m*base)) {
        ts=0;
        m++;
    } else {
        ts=parseInt((ms/100)+s);
        if(ts>=base) { ts=ts-((m-1)*base); }
    }
    if (m>(h*base)) {
        tm=1;
        h++;
    } else {
        tm=parseInt((ms/100)+m);
        if(tm>=base) { tm=tm-((h-1)*base); }
    }
    ms = Math.round(t/10);
    if (ms>99) {ms=0;}
    if (ms==0) {ms='00';}
    if (ms>0&&ms<=9) { ms = '0'+ms; }
    if (ts>0) { ds = ts; if (ts<10) { ds = '0'+ts; }} else { ds = '00'; }
    dm=tm-1;
    if (dm>0) { if (dm<10) { dm = '0'+dm; }} else { dm = '00'; }
    dh=h-1;
    if (dh>0) { if (dh<10) { dh = '0'+dh; }} else { dh = '00'; }
    readout =  dh + ':' + dm + ':' + ds + '.' + ms;
    $('#indicators_plase_mini_clock').html(readout);
    clocktimer = setTimeout("StartTIME()",1);
}
//Функция запуска и остановки
function StartStop() {
    if (init==0){
        ClearСlock();
        time_to_end();
        dateObj = new Date();
        StartTIME();
        init=1;
    } else {
        clearTimeout(clocktimer);
        clearTimeout(clocktimerback);
        init=0;
    }
}
var indicators={
    count_errors:{
        value:0,
        up: function () {
            this.value+=1;
            $('#count_errors').html(this.value);
        },
        down: function () {
            this.value-=1;
            $('#count_errors').html(this.value);
        }

    }


}



