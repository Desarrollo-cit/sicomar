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
        // console.log(data);
        var testData = {
            max: 15,
            data
        };

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
        console.log(dataBD);

        if (window.grafico) {
            window.grafico.destroy()
        }
        if (dataBD){
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
        }else{
            Toast.fire({
               title : 'No se reportar registros',
               icon: 'info'
            })
        }
    
    
        
    } catch (error) {
        console.log(error)
    }

    // console.log(info);
}

dataMapa();
getConsumos();


const filtrarInformacion = async (e) => {
    e.preventDefault();
    let inicio = formEstadisticas.inicio.value
    let fin = formEstadisticas.fin.value
    dataMapa(inicio, fin);
    getConsumos(inicio, fin)
    // getOperaciones(inicio, fin)
    // getOperacionesMensuales(inicio);
    // getOperacionesTop(inicio, fin)
  
  
  }


formEstadisticas.addEventListener('submit', filtrarInformacion )
