$(document).ready(function () {

    google.charts.load('current', {
        packages: ['corechart', 'line']
    });
    google.charts.setOnLoadCallback(drawChartLine);
});

function drawChartLine() {

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
            url: "control/ctrlBuscaProducto.php",
            dataType: "json",
            success: {
                function(data) {
                    jsonData = data;

                    console.log(jsonData);
                    c = jsonData.data.arrProds;

                    var data = new google.visualization.DataTable();
                    //Columnas con datos a evaluar
                    data.addColumn('string', 'Producto');
                    data.addColumn('number', 'Precio');

                    //Se agregan filas de datos
                    data.addRows(c.length);
                    for (var i = 0; i < c.length; i++) {
                        data.setCell(i, 0, c[i].nombre);
                        data.setCell(i, 1, c[i].precio);
                    }

                    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            }
        }).responseText;
}










