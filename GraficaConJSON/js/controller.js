$(document).ready(function () {

    google.charts.load('current', { packages: ['corechart'] });

    google.charts.setOnLoadCallback(drawChart);

});

function drawChart() {

    var options = {
        hAxis: {
            title: 'Precios'
        },
        vAxis: {
            title: 'Popularidad'
        },
        colors: ['#AB0D06', '#007329', '#F5E605'],
        //Extras para visualizar el crecimiento
        animation: {
            "startup": true,//Tipo de animación
            duration: 3000,//Duración en milisegundos
        }
    };

    var jsonData = $.ajax(
        {
            url: "control/index.php",
            dataType: "json",
            success: {
                function(data) {
                    jsonData = data;

                    console.log(jsonData);
                    c = jsonData.data.arrProds;

                    var data = new google.visualization.DataTable();
                    //Columnas con datos a evaluar
                    data.addColumn('datetime', 'Date_Time');
                    data.addColumn('number', 'Temperature');

                    //Se agregan filas de datos
                    data.addRows(c.length);
                    for (var i = 0; i < c.length; i++) {
                        data.setCell(i, 0, c[i].Date_Time);
                        data.setCell(i, 1, c[i].Temperature);
                    }

                    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            }
        }).responseText;
}










