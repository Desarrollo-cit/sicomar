import { Dropdown } from 'bootstrap';
import L from "leaflet"
import 'leaflet-easyprint'
import 'leaflet-heatmap'
import { Heatmap, h337 } from 'heatmap.js';
import HeatmapOverlay from 'leaflet-heatmap';
import { Toast } from "../funciones";
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables)



const formEstadisticas = document.getElementById('formEstadisticas');
const chartColors = [
	'rgba(14, 128, 255, 0.8)', //azul
	'rgba(7, 216, 0, 0.8)', //verde
	'rgba(255, 0, 0, 0.8)', //rojo
	'rgba(255, 0, 231, 0.8)', //rosa
	'rgba(0, 255, 247, 0.8)', //celeste
	'rgba(236, 255, 0, 0.8)', //amarillo
	'rgba(162, 255, 0, 0.8)' //verde mas claro
];
const grayScale = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    minZoom: 5,
    maxNativeZoom: 19,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoiZGFuaWVsZmo5NzUiLCJhIjoiY2wzZXkyMHliMDJpeDNwbzdyM3VjNXF0NSJ9.qN1T0BLAdQ5T4_K9XdJGnQ'
});
const map = L.map('map', {
    center: [15.525158, -90.32959],
    zoom: 7,
    layers: [grayScale]
})
const cfgHeatMap = {
    "radius": .05,
    "maxOpacity": .5,
    "scaleRadius": true,
    "useLocalExtrema": true,
    latField: 'lat',
    lngField: 'lng',
    valueField: 'count'
};
const heatmapLayer = new HeatmapOverlay(cfgHeatMap);
map.addLayer(heatmapLayer)

const dataMapa = async (inicio = '', fin = '') => {
    map.removeLayer(heatmapLayer)

    try {

        const url = `/sicomar/API/estadisticas/mapa?inicio=${inicio}&fin=${fin}`
        const config = { method: "GET" }
        const response = await fetch(url, config);
        const data = await response.json()
        console.log(data);
        var testData = {
            max: 15,
            data
        };
        // console.log(testData);
        heatmapLayer.setData(testData);


        heatmapLayer.addTo(map);
    } catch (error) {
        console.log(error)
    }
}

const getConsumos = async (inicio = '', fin = '') => {
    try {

        const url = `/sicomar/API/estadisticas/consumos?inicio=${inicio}&fin=${fin}`
        const config = { method: "GET" }
        const response = await fetch(url, config);
        const dataBD = await response.json()
        // console.log(dataBD);

        if (window.grafico) {
            window.grafico.destroy()
        }
        if (dataBD) {
            const labels = dataBD.map(r => r.nombre)
            const cantidades = dataBD.map(r => r.cantidad)
            const colores = dataBD.map(r => r.color)


            const canvas = document.getElementById('chartConsumos');
            const ctx = canvas.getContext('2d');

            const data = {
                labels,
                datasets: [{
                    label: 'Grafico de consumos',
                    data: cantidades,
                    backgroundColor: colores
                }]
            };

            const configChart = {
                type: 'doughnut',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Consumos'
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            };

            window.grafico = new Chart(
                ctx,
                configChart
            );
        } else {
            Toast.fire({
                title: 'No se reportan registros',
                icon: 'info'
            })
        }



    } catch (error) {
        console.log(error)
    }

    // console.log(info);
}

const getOperaciones = async (inicio = '', fin = '') => {
    const url = `/sicomar/API/estadisticas/comando?inicio=${inicio}&fin=${fin}`
    const config = { method: "GET" }
    const response = await fetch(url, config);
    const info = await response.json()
    // console.log(info)
    // return
    window.myChart && window.myChart.destroy()


    const canvas = document.getElementById('chartOperaciones');
    const ctx = canvas.getContext('2d');

    let { labels, cantidades } = info;

    // console.log(cantidades);

    let dataSetsLabels = Object.keys(cantidades);
    let dataSetsValues = Object.values(cantidades)

    // console.log(dataSetsLabels);
    // console.log(dataSetsValues);



    let datasets = []

    for (let index = 0; index < dataSetsLabels.length; index++) {
        datasets = [...datasets, {
            label: dataSetsLabels[index],
            data: dataSetsValues[index],
            backgroundColor: chartColors[index],
            borderColor: chartColors[index],
            borderWidth: 1
        }]

    }

    // console.log(datasets);
    let chartInfo = {
        type: 'bar',
        data: {
            labels,
            datasets
        },
        options: {
            indexAxis: 'y',
            plugins: {
                title: {
                    display: true,
                    text: 'Cantidad de Operaciones'
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    }


    window.myChart = new Chart(ctx, chartInfo);
    window.myChart.update()
 
    


    // console.log(info);
    // return

   

    // console.log(info);
}

const getOperacionesMensuales = async (inicio = '') => {
    const url = `/sicomar/API/estadisticas/mensuales?inicio=${inicio}`
    const config = { method : "GET" }
    const response = await fetch(url, config);
    const info = await response.json()
  
    info.length < 1 && alertToast('info','No se reportan registros') 
  
    let datasets = [];
    const {labels, cantidades} = info;
  
    // return
  
    for (let index = 0; index < labels.length; index++) {
      datasets = [ ... datasets , {
        label: labels[index],
        data: cantidades[index],
        fill: false,
        backgroundColor: chartColors[index],
        borderColor:  chartColors[index],
        tension: 0.2
      }]
      
    }
    
    const canvas = document.getElementById('chartMeses');
    const ctx = canvas.getContext('2d');
    if(window.myChartMeses){
    //   console.log(window.myChartMeses);
      window.myChartMeses.destroy()
    } 
      
    
    const labelsTitulos = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    const data = {
      labels: labelsTitulos,
      datasets
    };
    const configChart = {
      type: 'line',
      data: data,
      options: {
        plugins: {
          title: {
              display: true,
              text: 'Operaciones por Comando'
          }
        }
      }
    };
  
    window.myChartMeses = new Chart(ctx, configChart);
    window.myChartMeses.update()
  
    // console.log(info);
  }

  const getOperacionesTop = async (inicio = '', fin ='') => {
    const url = `/sicomar/API/estadisticas/top?inicio=${inicio}&fin=${fin}`
    const config = { method : "GET" }
    const response = await fetch(url, config);
    const info = await response.json()
  
    // console.log(info);
    // return;
  
    info.length < 1 && alertToast('info','No se reportan registros') 
    
  
    // console.log(info);
    // return
    
    const canvas = document.getElementById('chartTop');
    const ctx = canvas.getContext('2d');
    window.myChartTop && window.myChartTop.destroy()
    
    let {labels, cantidades} = info;
  
    let dataSetsLabels = labels;
    let dataSetsValues = cantidades
  
  
  
  
    let datasets = []
  
    for (let index = 0; index < dataSetsLabels.length; index++) {
      datasets = [...datasets, {
        label: dataSetsLabels[index],
        data: dataSetsValues[index],
        backgroundColor: chartColors[index],
        borderColor:  chartColors[index],
        borderWidth: 1
      }]
      
    }
    let chartInfo = {
      type: 'bar',
      data: {
        labels,
        datasets : [{
          label: 'TOP',
          data: cantidades,
          backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(75, 192, 192)',
            'rgb(255, 205, 86)',
            'rgb(201, 203, 207)',
            'rgb(54, 162, 235)'
          ]
        }]
      },
      options: {
        indexAxis: 'y',
        plugins: {
          title: {
              display: true,
              text: 'TOP PERSONAL OPERATIVO'
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      }
    }
  
  
    window.myChartTop = new Chart(ctx, chartInfo);
    window.myChartTop.update()
  
    // console.log(info);
  }

dataMapa();
getConsumos();
getOperaciones();
getOperacionesMensuales();
getOperacionesTop();

const filtrarInformacion = async (e) => {
    e.preventDefault();
    let inicio = formEstadisticas.inicio.value
    let fin = formEstadisticas.fin.value
    dataMapa(inicio, fin);
    getConsumos(inicio, fin)
    getOperaciones(inicio, fin)
    getOperacionesMensuales(inicio);
    getOperacionesTop(inicio, fin)


}


formEstadisticas.addEventListener('submit', filtrarInformacion)
