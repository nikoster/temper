<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Stats</title>

        <script type="text/javascript" src="http://code.highcharts.com/highcharts.js" defer></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            $(function () {
                var stats = <?php echo json_encode($stats); ?>;

                Highcharts.chart('container', {
                    chart: {
                        type: 'spline'
                    },

                    title: {
                        text: 'Weekly Retention Curves, Temper'
                    },

                    yAxis: {
                        floor: 0,
                        ceiling: 100,
                        title: {
                            text: 'Percentage of users who have been or are still in this step/percentage'
                        }
                    },

                    xAxis: {
                        title: {
                            text: 'Onboarding Percentage'
                        }
                    },

                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },

                    plotOptions: {
                        series: {
                            pointStart: 0
                        },

                        spline: {
                            lineWidth: 4,
                            states: {
                                hover: {
                                    lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: false
                            }
                        }
                    },

                    series: stats
                });
            });
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div id="container" style="width:100%; height:450px;"></div>
            </div>
        </div>
    </body>
</html>
