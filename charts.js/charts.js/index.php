<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Charts.js</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stylechart.css">
    
  </head>
  <body>
    <h1>Gráfico con charts.js</h1>

<div class ="col-lg-12">  
<div class="card">
  <div class="card-header">
    Graficos
  </div>
  <div class="card-body">

  <div class="row">

  <div class="col-lg-2">
<button class="btn btn-primary" onclick="CargarDatosGraficoBar()">Grafico barra</button>
</div>
<canvas id="myChart3" width="400" height="100"></canvas>
 
</div>
</div>

    <script type="text/javascript" src="lib/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="lib/chart.js"></script>
    
    <script >
  function CargarDatosGraficoBar(){
        $.ajax({
            url:'controlador_grafico.php',
            type:'POST'

        }).done(function(resp){
            var titulo = [];
            var cantidad = [];
            var data = JSON.parse(resp);
            for(var i=0;i < data.length; i++){
                titulo.push(data[i][1]);
                cantidad.push(data[i][2]);
            }

var ctx = document.getElementById('myChart3').getContext('2d');



//setup

var data ={
    labels: titulo,
    datasets: [{
      label: 'My First Dataset',
      data: cantidad,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
      ],
      borderWidth: 1,
      borderRadius:30, //ovalar la gráfica solo de la parte superior
      borderSkipped:false // mostrar el ovalado completo
    }]
  };


  //config
  const config =new Chart (ctx, {
    type: 'bar',
     data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  });


        })
     }

    </script>
  </body>
</html>