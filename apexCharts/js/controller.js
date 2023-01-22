/* 
Titulo:Gráfico de barras en ApexCharts utilizando JSON para extraer datos 

Objetivo: Representar un gráfico de barras extrayendo datos de MySQL y convirtiendolos a codigo JSON

Autores: Alan Mitchell Velasco Gonzalez y Gerardo Iturribarria Moya 

*/

$.ajax({
    url: '../backEnd/controlador/controlador_grafico.php',
    type: 'POST'
})
    .done(function (resp) {
        var titulo = [];
        var cantidad = [];
        var data = JSON.parse(resp);
        for (var i = 0; i < data.length; i++) {
            titulo.push(data[i][1]);
            cantidad.push(data[i][2]);
        }


        var options = {
            chart: { //inica el gráfico 
                type: 'area', //se ingresa el tipo de gráfica 
                width: '75%' //se ingresa el tamaño que tendrá la gráfica 
            },
            series: [{
                name: 'Cantidad de producto', //se ingresa el nombre según el de lo que trate la gráfica 
                data: cantidad //datos que contendra la gráfica
            }],
            xaxis: {
                categories: titulo //eje horizontal que se extiende en la parte inferior de la gráfica
            },
            fill: {
              type: "pattern", //type: 'gradient' / 'solid' / 'pattern' / 'image'
              //se puden modificar el tipo
              gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
              }
            },
        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();
    })