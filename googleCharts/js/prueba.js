function graficar(data) {
    var jsonData = data;
    google.load("visualization", "1", { packages: ["corechart"], callback: drawVisualization });
    function drawVisualization() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Nombre del producto');
        data.addColumn('number', 'Total de piezas');

       /* $.each(jsonData, function (i, jsonData) {
            var value = jsonData[i][2];
            var name = jsonData[i][1];
            data.addRows([[name, value]]);
        });*/
        var titulo = [];
        var cantidad = [];
        var datosJSON = JSON.parse(data);
        for (var i = 0; i < datosJSON.length; i++) {
           /* titulo.push(data[i][1]);
            cantidad.push(data[i][2]);*/
            data.addRows([[data[i][1], data[i][2]]]);
        }
        var options = {
            title: "Autos más vendidos en Latinoamérica",
            subtitle: "Ventas totales durante el periodo 2018-2019",
            legend: "top",
            bar: { groupWidth: "50%" }
        };
        var gf1 = new google.visualization.ColumnChart(document.getElementById('piechart3'));
        gf1.draw(data, options);
    }
}

$(document).ready(function () {
    url = 'controlador/controlador_grafico.php';

    ajax_data('GET', url, function (data) {
        graficar(data);
        console.log(data);
    });
    
});

function ajax_data(type, url, success) {
    $.ajax({
        type: type,
        url: url,
        dataType: "json",
        restful: true,
        cache: false,
        timeout: 20000,
        async: true,
        beforeSend: function (data) { },
        success: function (data) {
            success.call(this, data);
        },
        error: function (data) {
            alert("Error al leer el archivo JSON");
        }
    });
}