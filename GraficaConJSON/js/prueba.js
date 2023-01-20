$().ready(()=>{

google.charts.load('current', { 'packages': ['line'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var json = $.ajax({
        url: "control/ctrlBuscaProducto.php",
        success: function (jsonData) {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'nombre');
            data.addColumn('number', 'precio');

            for (var i = 0; i < jsonData.length; i++) {
                nombre = jsonData[i].data.arrProds.nombre;
                precio = jsonData[i].data.arrProds.precio;
                data.addRow([nombre, precio]);
            }
            var options = {
                chart: {
                    title: 'Gráfico de Faturamento Mensal',
                    
                },
                width: 600,
                height: 300,
               
               
            };
            var chart = new google.charts.Line(document.getElementById('piechart3'));
            chart.draw(data, options);
        }
    });
}
});