$(document).ready(function () {
    console.log('js_ready');

    //инициализируем календари
    $("#datepicker_1").datepicker();
    $("#datepicker_2").datepicker();
    //скрыть календарь при загрузке страницы
    $('#datepicker_1').hide();
    $('#datepicker_2').hide();
 
//******************************************************************************    
    /*дополнительный счетчик showPicker для выполнения запроса ajax после выбора дат сортировки 
      открыть календарь  showPicker=1
      закрыть календарь  showPicker=2
      ajax запрос срабатывает при закрытии календаря
     */
    var showPicker = 0;

    //открываем календарь при нажатии кнопки "Выбрать период" 
    $('#showPicker').click(function () {
        
        //открываем календарь
        $('#datepicker_1').toggle();
        $('#datepicker_2').toggle();
        
        //дополнительный счетчик +1
        showPicker++;
        //выполнение функций после закрытия календаря
        if (showPicker === 2) {
            
            $('#ajax_data').text('Загружаю данные!!!');

            var data = {
                date_min: {},
                date_max: {}
            };

            //получаем данные календаря
            data.date_min.day   = $("#datepicker_1").datepicker('getDate').getDate();
            data.date_min.month = $("#datepicker_1").datepicker('getDate').getMonth() + 1;
            data.date_min.year  = $("#datepicker_1").datepicker('getDate').getFullYear();

            data.date_max.day   = $("#datepicker_2").datepicker('getDate').getDate();
            data.date_max.month = $("#datepicker_2").datepicker('getDate').getMonth() + 1;
            data.date_max.year  = $("#datepicker_2").datepicker('getDate').getFullYear();

            //формируем JSON
            var json_data = JSON.stringify(data);
            console.log('json_data_to_server=' + json_data);
            
            //возвращаем отформатированную строку с датами
            var form_date = makeDataFormat(data);
            //вставляем данные в лицевую панель 
            $('span#sort_period').text('');
            $('span#sort_period').append('<span>&nbsp;&nbsp;'+form_date+'</span>');

            //передаем данные
            console.log('start ajax');
            $.ajax({
                type: 'post',
                data: {
                    params: json_data
                },
                success: function (answer) {

                    console.log('answer ajax');
                    console.log(answer);
                    answer = $.parseJSON(answer); //приводим данные в рабочий вид
                   
                    //выводим сообщение если данных за выбраный период нету
                    if (answer === null){
                      $('#ajax_data').text('Внимание! Данные за выбраный период не обнаружены!!!');  
                    //обрабатываем полученные данные   
                    } else {
                      
                      //убираем предыдущие данные     
                      $('#ajax_data').text('');
                      
                      //переменная для хранения данных баланса
                      var balance = {
                          cash_in: {ua: 0, usd: 0},
                          cash_out: {ua: 0, usd: 0}
                         };
                      
                      //перебираем полученные данные
                      for (i=0; i<answer.length; i++){
                       
                        //подсчет данных прихода/расхода
                        if(answer[i].direction === 'in'){      
                            balance.cash_in.ua += parseFloat(answer[i].cash_ua);
                            balance.cash_in.usd += parseFloat(answer[i].cash_usd);
                        } else {
                          if(answer[i].direction === 'out'){
                            balance.cash_out.ua += parseFloat(answer[i].cash_ua);
                            balance.cash_out.usd += parseFloat(answer[i].cash_usd);  
                          }
                        }
                      
                        //формируем цвет маркера в зависимости от типа операции     
                        if(answer[i].direction === 'in') {
                            style =  '#8FBC8F';
                        } else {
                            style =  '#E9967A'; 
                        }
                        
                        //выводим данные за указанный период
                        $('#ajax_data').append('\
                            <tr id='+answer[i].id+' class="gradeA row_set">\n\
                                <td><center><i style="color:'+style+'" class="shop support-hover fa fa-shopping-cart"></i></center></td>\n\
                                <td>'+answer[i].date+'</td>\n\
                                <td>'+answer[i].operation+'</td>\n\
                                <td><center>'+parseFloat(answer[i].cash_ua).toFixed(2)+'</center></td>\n\
                                <td><center>'+parseFloat(answer[i].cash_usd).toFixed(2)+'</center></td>\n\
                                <td><center>\n\
                                    <a data-toggle="tooltip" class="btn btn-primary btn-sm" data-original-title="редактировать аппарат" href="/diary/edit/direct/'+answer[i].direction+'/id/'+answer[i].id+'"><i class="fa fa-pencil fa-fw" ></i></a>\n\
                                    <a data-toggle="tooltip" class="delete btn btn-danger btn-sm"  data-original-title="удалить аппарат" href="/diary/delete/id/'+answer[i].id+'" rel="'+answer[i].id+'"><i class="fa fa-trash-o fa-fw"></i></a>\n\
                                    </center>\n\
                                </td>\n\
                            </tr>'); 
                    }
                    
                    //выводим данные прихода/расхода и баланса                       
                    $('span#balance').text('');
                    $('span#balance').append('<i class="fa fa-dashboard fa-long-arrow-down " style="color: red;">\n\
                                                &nbsp;Расход:'+balance.cash_out.ua.toFixed(2)+'/'+balance.cash_out.usd.toFixed(2)+
                                             '</i>\n\
                                              <i class="fa fa-dashboard fa-long-arrow-up" style="color: green;">\n\
                                                &nbsp;Приход: '+balance.cash_in.ua.toFixed(2)+'/'+ balance.cash_in.usd.toFixed(2)+
                                              '</i>\n\
                                                Баланс: '+(balance.cash_in.ua-balance.cash_out.ua).toFixed(2)+'/'+(balance.cash_in.usd-balance.cash_out.usd).toFixed(2)                                           
                                );

                }
                }
            });
            //обнуляем дополнительный счетчик
            showPicker = 0;
        }
    });
//******************************************************************************   
    //удаление записи live method
    $( document ).on( "click", "a.delete", function(e) {
            
        if(confirm('Удалить запись?')){
            
            id = $(this).attr("rel");    //записываем id
            console.log(id); 
            
             $.ajax({
                url : '/diary/delete/',
                type: 'post',
                data: {
                    id: id
                },
                success: function (a) {
                    console.log(a);
                    alert('Удалено!');
                    location.reload();
                }
            });
        };
        //отменить стандартное действие
        e.preventDefault();  
    });
//******************************************************************************    
    //удаление операции
     $('.delete_operation').click(function (e) {
            
        if(confirm('Удалить запись?\n\Внимание! Вы удалите все записи ДНЕВНИКА связанные с данной операцией!')){
            
            id = $(this).attr("rel");    //записываем id
            console.log(id); 
            
             $.ajax({
                url : '/setup/delete/',
                type: 'post',
                data: {
                    id: id
                },
                success: function (a) {
                    console.log(a);
                    alert('Удалено!');
                    location.reload();
                }
            });
        };
        //отменить стандартное действие
        e.preventDefault();  
    });
//******************************************************************************
    //возвращаем отформатированную строку с датами типа хх-хх-хххх - хх-хх-хххх 
    function makeDataFormat(data) {

        if(data.date_min.day < 10) {
          data.date_min.day = '0'+data.date_min.day;  
        } 
        if(data.date_min.month < 10) {
          data.date_min.month = '0'+data.date_min.month;  
        } 
        if(data.date_max.day < 10) {
          data.date_max.day = '0'+data.date_max.day;  
        } 
        if(data.date_max.month < 10) {
          data.date_max.month = '0'+data.date_max.month;  
        } 
        return data.date_min.day+'.'+data.date_min.month+'.'+data.date_min.year+' - '
                +data.date_max.day+'.'+data.date_max.month+'.'+data.date_max.year;
    }
//******************************************************************************
});


