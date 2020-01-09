@extends('admin.layout.layout')

@section('title', '登录')
@section('sidebar')
@section('content')

        <meta charset="utf-8"><link rel="icon" href="https://jscdn.com.cn/highcharts/images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            /* css 代码  */
            
        </style>
        <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
        <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
      

        <form>
            <table>
                <h1>一周天气</h1>
                <input type="text" name="city" placeholder="请输入城市">
                <button type='button' id="dianji">搜索</button> 
            </table>
        </form>
        <br><br><br>
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <script>
            
          
        </script>
{{-- ajax --}}
        <script>
            $(function(){
                $(document).on('click','#dianji',function(){
                    var city =$('input[name="city"]').val();
                    if(city==""){
                        city="北京";
                    }
                    
                    $.ajax({
                        method:"get",
                        data:{city:city},
                        url:"{{url('admin/getWeater')}}",
                        dataType:"json",
                        success:function(res){
                            console.log(res);
                            suibianqide(res.result);
                        }
                    })
            })
             function suibianqide(res){
                    // console.log(res);
                    var week=[];
                    var temperature=[];
                    $.each(res,function(i,v){
                        week.push(v.days);
                        var arr=[parseInt(v.temp_low),parseInt(v.temp_high)];
                        temperature.push(arr);
                    })
                    var chart = Highcharts.chart('container', {
                chart: {
                    type: 'columnrange', // columnrange 依赖 highcharts-more.js
                    inverted: true
                },
                title: {
                    text: '每周温度变化范围'
                },
                subtitle: {
                    text: res[0]['citynm']
                },
                xAxis: {
                    categories:  week 
                },
                yAxis: {
                    title: {
                        text: '温度 ( °C )'
                    }
                },
                tooltip: {
                    valueSuffix: '°C'
                },
                plotOptions: {
                    columnrange: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return this.y + '°C';
                            }
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: '温度',
                    data: temperature
                }]
            });
        }
               
    })
           
        </script>

<script src="/jq.js"></script>
<script>
            $.ajax({
                        // method:"get",
                        data:{city:'北京'},
                        url:"{{url('admin/getWeater')}}",
                        dataType:"json",
                        success:function(res){
                            console.log(res);
                            suibianqide(res.result);
                        
                        }
                    })

                function suibianqide(res){
                    // console.log(res);
                    var week=[];
                    var temperature=[];
                    $.each(res,function(i,v){
                        week.push(v.days);
                        var arr=[parseInt(v.temp_low),parseInt(v.temp_high)];
                        temperature.push(arr);
                    })
                    var chart = Highcharts.chart('container', {
                chart: {
                    type: 'columnrange', // columnrange 依赖 highcharts-more.js
                    inverted: true
                },
                title: {
                    text: '每周温度变化范围'
                },
                subtitle: {
                    text: res[0]['citynm']
                },
                xAxis: {
                    categories:  week 
                },
                yAxis: {
                    title: {
                        text: '温度 ( °C )'
                    }
                },
                tooltip: {
                    valueSuffix: '°C'
                },
                plotOptions: {
                    columnrange: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return this.y + '°C';
                            }
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: '温度',
                    data: temperature
                }]
            });
        }
</script>


@endsection

