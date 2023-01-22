/* 
Titulo:Gráfico de barras en Charts utilizando JSON para extraer datos 

Objetivo: Representar un gráfico de barras extrayendo datos de MySQL y convirtiendolos a codigo JSON

Autores: Alan Mitchell Velasco Gonzalez y Gerardo Iturribarria Moya 

*/

function CargarDatosGraficoBar(){
    $.ajax({
        url:'../backEnd/controlador/controlador_grafico.php',
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
  label: 'Datos',
  data: cantidad,
  backgroundColor: generadorColor(data.length),
  borderColor: generadorColor(data.length),
  borderWidth: 1,
  borderRadius:30, //ovalar la gráfica solo de la parte superior
  borderSkipped:false // mostrar el ovalado completo
}]
};


//config
const config =new Chart (ctx, {
type: 'line',
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

 function aleatorio(inferior,superior){
  numPosibilidades = superior - inferior
  aleat = Math.random() * numPosibilidades
  aleat = Math.floor(aleat)
  return parseInt(inferior) + aleat
}

function generadorColor(limite){
  hexadecimal = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F")
  color_aleatorio = "#";
  var colores = [];
  for(j=0;j<limite; j++){
  for (i=0;i<6;i++){
     posarray = aleatorio(0,hexadecimal.length);
     color_aleatorio += hexadecimal[posarray];
     
  }
colores.push(color_aleatorio);
color_aleatorio = "#";
}
console.log(colores);
  return colores;
}