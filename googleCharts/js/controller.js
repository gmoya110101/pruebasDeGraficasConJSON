/* 
Titulo:Gráfico de barras en GoogleCharts utilizando JSON para extraer datos 

Objetivo: Representar un gráfico de barras extrayendo datos de MySQL y convirtiendolos a codigo JSON

Autores: Alan Mitchell Velasco Gonzalez y Gerardo Iturribarria Moya 

*/
google.charts.load('current', { packages: ['corechart', 'bar'] });

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);


    var tempTitulo ='Gráfica de cantidad disponible de productos ';
    $.ajax({
        url: "../backEnd/controlador/controlador_grafico.php",
        method: "POST",
        
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            drawChart(data, tempTitulo);
        }
    });


function drawChart(chart_data, chart_titulo) {

    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Nombre');
    data.addColumn('number', 'cantidad');

   
    $.each(jsonData, function (i, jsonData) {
        var nombre = jsonData.nombre_producto;
        var cantidad = parseInt($.trim(jsonData.stock_producto));
        data.addRows([[nombre,cantidad]]);
        
    });

    var options = {
        title: chart_titulo,
        colors: ['#AB0D06', '#007329', '#F5E605'],
        hAxis: { title: "Nombre" },
        vAxis: { title: "Cantidad" },
       
    };

    var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
    
    chart.draw(data, options);
}