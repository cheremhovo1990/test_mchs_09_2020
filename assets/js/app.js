/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// require jQuery normally
const $ = require('jquery');
require('jquery-ui/ui/widgets/datepicker');
// create global $ and jQuery variables
global.$ = global.jQuery = $;
require("popper.js");
require('bootstrap');
$.datepicker.setDefaults({
    dateFormat: "yy-mm-dd"
});
$( function() {
    $( ".datepicker" ).datepicker();
} );

if ($('#currency-chart').length) {
    const $firstScript = $('script').eq(0);
    const script = document.createElement('script');
    const $script = $(script);

    script.async = true;
    $firstScript.before($script);
    $script.on('load', function () {
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            let currencies = $('#currency-chart').data('currencies');
            console.log(currencies);
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Дата');
            data.addColumn('number', $('#currency-chart').data('char-code'));

            data.addRows(currencies);

            var options = {
                chart: {
                    title: 'Box Office Earnings in First Two Weeks of Opening',
                    subtitle: 'in millions of dollars (USD)'
                },
                width: 900,
                height: 500
            };

            var chart = new google.charts.Line(document.getElementById('currency-chart'));

            chart.draw(data, google.charts.Line.convertOptions(options));
        }
    });
    script.src = 'https://www.gstatic.com/charts/loader.js';
}

