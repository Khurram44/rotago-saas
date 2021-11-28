<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lato&amp;display=swap" rel="stylesheet">

    <style type="text/css">
        .text-white { color: #fff; }
        table { width: 97%; }
        table,
        th,
        td {
            border: 1px solid rgba(0, 0, 0, 0);
            border-collapse: collapse;
        }
        th,
        td {
            padding: 15px;
            text-align: left;
        }
        /* #t01 tr:nth-child(even) { background-color: #eee; }
        #t01 tr:nth-child(odd) { background-color: #fff; } */
        #t01 th {
            background-color: #051C4B;
            color: white;
            font-size: 13px;
        }
        .m0 { margin: 0px; }
        .mb5 { margin-bottom: 5px; }
        .mb10 { margin-bottom: 10px; }
        tr.dsads td{
            background-color: #000;            
            padding: 0px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

    </style>
</head>

<body class="overflow-x-hidden">

    <div class="container" id="boxes">
        <div id="app" class="content">
            <div style="width:1000px;margin-left: auto;margin-right: auto; background-color: #dddddd26; height: 98vh;">
                <div style="background: #051C4B; padding: 20px 25px;">
                    <div style="background: #051C4B; padding: 20px 25px;display: inline-block;">
                        <img src="http://localhost/rotago/rotago-saas/main_file/storage/uploads/logo/logo.png" style="width: 100%; display: inline-block; float: left; max-width: 150px;">
                    </div>
                    <div style="display: inline-block; float: right; color: white; width: 250px; text-align: center;">
                        <h2 class="m0 mb5">{{ (!empty($location_data)) ? $location_data->name : '' }}</h2>
                        <div class="mb10"> {{ (!empty($location_data)) ? $location_data->address : '' }} </div>
                        <div> {{ __('week').' '.date("W Y", strtotime($week_date[0])) }} </div>
                    </div>
                    <span style="clear: both; display: block;"></span>
                </div>
                <table id="t01" style="margin: 20px 15px;">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>                            
                            <th>{{ __('Employee') }}</th>                            
                            <th>{{ __('Time In') }}</th>                            
                            <th>{{ __('Time Out') }}</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        @if (!empty($users_name))                        
                            @foreach ($week_date as $date)
                                @foreach ($users_name as $item)
                                    {!! \App\Rotas::getdaterotasreport($date, $item['id'], $location_data->id, $role_id) !!}                                                                
                                @endforeach                          
                                <tr class="dsads"><td></td><td></td><td></td><td></td></tr>                              
                            @endforeach                        
                        @else 
                            <tr> <td colspan="4"> <h2> <center> {{ __('No Data Found') }}  </center> </h2> </td> </tr>
                        @endif
                    </tbody>                                    
                </table>
            </div>                        
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://localhost/posgo/main/saas/main_file/js/html2pdf.bundle.min.js"></script>

    <script>
        function closeScript() {
            setTimeout(function () {
                window.history.back();
                //window.open(window.location, '_self').close();
            }, 1000);
        }

        $(window).on('load', function () {
            var element = document.getElementById('boxes');
            var opt = {
                filename: '#Rotas00010',
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A3'}
            };

            html2pdf().set(opt).from(element).save().then(closeScript);
        });

    </script>
</body>

</html>
