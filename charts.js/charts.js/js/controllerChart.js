function CargarDatosGraficoBar(){
    $.ajax({
        url:'controlador/controlador_grafico.php',
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