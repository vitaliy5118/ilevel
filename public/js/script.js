$(document).ready(function () {
    console.log('js_ready');

    //�������������� ���������
    $("#datepicker_1").datepicker();
    $("#datepicker_2").datepicker();
    //������ ��������� ��� �������� ��������
    $('#datepicker_1').hide();
    $('#datepicker_2').hide();
 
//******************************************************************************    
    /*�������������� ������� showPicker ��� ���������� ������� ajax ����� ������ ��� ���������� 
      ������� ���������  showPicker=1
      ������� ���������  showPicker=2
      ajax ������ ����������� ��� �������� ���������
     */
    var showPicker = 0;

    //��������� ��������� ��� ������� ������ "������� ������" 
    $('#showPicker').click(function () {
        
        //��������� ���������
        $('#datepicker_1').toggle();
        $('#datepicker_2').toggle();
        
        //�������������� ������� +1
        showPicker++;
        //���������� ������� ����� �������� ���������
        if (showPicker === 2) {
            
            $('#ajax_data').text('�������� ������!!!');

            var data = {
                date_min: {},
                date_max: {}
            };

            //�������� ������ ���������
            data.date_min.day   = $("#datepicker_1").datepicker('getDate').getDate();
            data.date_min.month = $("#datepicker_1").datepicker('getDate').getMonth() + 1;
            data.date_min.year  = $("#datepicker_1").datepicker('getDate').getFullYear();

            data.date_max.day   = $("#datepicker_2").datepicker('getDate').getDate();
            data.date_max.month = $("#datepicker_2").datepicker('getDate').getMonth() + 1;
            data.date_max.year  = $("#datepicker_2").datepicker('getDate').getFullYear();

            //��������� JSON
            var json_data = JSON.stringify(data);
            console.log('json_data_to_server=' + json_data);
            
            //���������� ����������������� ������ � ������
            var form_date = makeDataFormat(data);
            //��������� ������ � ������� ������ 
            $('span#sort_period').text('');
            $('span#sort_period').append('<span>&nbsp;&nbsp;'+form_date+'</span>');

            //�������� ������
            console.log('start ajax');
            $.ajax({
                type: 'post',
                data: {
                    params: json_data
                },
                success: function (answer) {

                    console.log('answer ajax');
                    console.log(answer);
                    answer = $.parseJSON(answer); //�������� ������ � ������� ���
                   
                    //������� ��������� ���� ������ �� �������� ������ ����
                    if (answer === null){
                      $('#ajax_data').text('��������! ������ �� �������� ������ �� ����������!!!');  
                    //������������ ���������� ������   
                    } else {
                      
                      //������� ���������� ������     
                      $('#ajax_data').text('');
                      
                      //���������� ��� �������� ������ �������
                      var balance = {
                          cash_in: {ua: 0, usd: 0},
                          cash_out: {ua: 0, usd: 0}
                         };
                      
                      //���������� ���������� ������
                      for (i=0; i<answer.length; i++){
                       
                        //������� ������ �������/�������
                        if(answer[i].direction === 'in'){      
                            balance.cash_in.ua += parseFloat(answer[i].cash_ua);
                            balance.cash_in.usd += parseFloat(answer[i].cash_usd);
                        } else {
                          if(answer[i].direction === 'out'){
                            balance.cash_out.ua += parseFloat(answer[i].cash_ua);
                            balance.cash_out.usd += parseFloat(answer[i].cash_usd);  
                          }
                        }
                      
                        //��������� ���� ������� � ����������� �� ���� ��������     
                        if(answer[i].direction === 'in') {
                            style =  '#8FBC8F';
                        } else {
                            style =  '#E9967A'; 
                        }
                        
                        //������� ������ �� ��������� ������
                        $('#ajax_data').append('\
                            <tr id='+answer[i].id+' class="gradeA row_set">\n\
                                <td><center><i style="color:'+style+'" class="shop support-hover fa fa-shopping-cart"></i></center></td>\n\
                                <td>'+answer[i].date+'</td>\n\
                                <td>'+answer[i].operation+'</td>\n\
                                <td><center>'+parseFloat(answer[i].cash_ua).toFixed(2)+'</center></td>\n\
                                <td><center>'+parseFloat(answer[i].cash_usd).toFixed(2)+'</center></td>\n\
                                <td><center>\n\
                                    <a data-toggle="tooltip" class="btn btn-primary btn-sm" data-original-title="������������� �������" href="/diary/edit/direct/'+answer[i].direction+'/id/'+answer[i].id+'"><i class="fa fa-pencil fa-fw" ></i></a>\n\
                                    <a data-toggle="tooltip" class="delete btn btn-danger btn-sm"  data-original-title="������� �������" href="/diary/delete/id/'+answer[i].id+'" rel="'+answer[i].id+'"><i class="fa fa-trash-o fa-fw"></i></a>\n\
                                    </center>\n\
                                </td>\n\
                            </tr>'); 
                    }
                    
                    //������� ������ �������/������� � �������                       
                    $('span#balance').text('');
                    $('span#balance').append('<i class="fa fa-dashboard fa-long-arrow-down " style="color: red;">\n\
                                                &nbsp;������:'+balance.cash_out.ua.toFixed(2)+'/'+balance.cash_out.usd.toFixed(2)+
                                             '</i>\n\
                                              <i class="fa fa-dashboard fa-long-arrow-up" style="color: green;">\n\
                                                &nbsp;������: '+balance.cash_in.ua.toFixed(2)+'/'+ balance.cash_in.usd.toFixed(2)+
                                              '</i>\n\
                                                ������: '+(balance.cash_in.ua-balance.cash_out.ua).toFixed(2)+'/'+(balance.cash_in.usd-balance.cash_out.usd).toFixed(2)                                           
                                );

                }
                }
            });
            //�������� �������������� �������
            showPicker = 0;
        }
    });
//******************************************************************************   
    //�������� ������ live method
    $( document ).on( "click", "a.delete", function(e) {
            
        if(confirm('������� ������?')){
            
            id = $(this).attr("rel");    //���������� id
            console.log(id); 
            
             $.ajax({
                url : '/diary/delete/',
                type: 'post',
                data: {
                    id: id
                },
                success: function (a) {
                    console.log(a);
                    alert('�������!');
                    location.reload();
                }
            });
        };
        //�������� ����������� ��������
        e.preventDefault();  
    });
//******************************************************************************    
    //�������� ��������
     $('.delete_operation').click(function (e) {
            
        if(confirm('������� ������?\n\��������! �� ������� ��� ������ �������� ��������� � ������ ���������!')){
            
            id = $(this).attr("rel");    //���������� id
            console.log(id); 
            
             $.ajax({
                url : '/setup/delete/',
                type: 'post',
                data: {
                    id: id
                },
                success: function (a) {
                    console.log(a);
                    alert('�������!');
                    location.reload();
                }
            });
        };
        //�������� ����������� ��������
        e.preventDefault();  
    });
//******************************************************************************
    //���������� ����������������� ������ � ������ ���� ��-��-���� - ��-��-���� 
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


