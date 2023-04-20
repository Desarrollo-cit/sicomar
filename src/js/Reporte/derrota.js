import { Dropdown, Tooltip, Modal, Alert } from "bootstrap";
import L from "leaflet"
import 'leaflet-easyprint'
import { Toast } from '../funciones';


const modalPuntos = new Modal(document.getElementById('modalPuntos'), {})
const formPuntos = document.querySelector('#formPuntos')
let puntos = [];
let distancia = 0;
const tablePuntos = document.querySelector('#tablePuntos');
const spanDistancia = document.querySelector('#distancia');
const formulario = document.querySelector('#formInternacional')



const map = L.map('map', {
    center: [15.825158, -89.72959],
    zoom: 7.5
})
const markers = L.layerGroup();
const LimpiarMapa = () => {
    map.eachLayer(layer => { markers.removeLayer(layer) })

}

const grayScale = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 100,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoiZGFuaWVsZmo5NzUiLCJhIjoiY2tpcWNlbHM0MXZmbDJ6dTZvdDV3NGticiJ9.7ciIi1FKO5-BqgE0zz5UFw'
}).addTo(map);


document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl))

})

L.easyPrint({
    title: 'Imprimir vista actual',
    position: 'topright',
    sizeModes: ['A4Portrait', 'A4Landscape']
}).addTo(map);

const onMapClick = e =>{
    const { lat, lng } = e.latlng;
    formPuntos.reset();
    formPuntos.latitud.value = lat;
    formPuntos.longitud.value = lng;
    modalPuntos.show();
}



map.on("click", onMapClick)

const agregarPunto = (e) => {
    e.preventDefault();
    puntos = [...puntos,[
        formPuntos.latitud.value,
        formPuntos.longitud.value
    ]]

    agregarPuntos(puntos)
    agrearPuntosTabla(puntos)
    modalPuntos.hide();
}



const agregarPuntos = (puntos) => {
    LimpiarMapa();
    distancia = 0;

    for (let index = 0; index < puntos.length; index++) {
        let marker = L.marker(puntos[index], {icon} ).addTo(markers);
        marker.bindPopup(`<b>Punto ${index + 1}</b><br>Latitud: ${puntos[index][0]}<br>Longitud: ${puntos[index][1]}`)
        marker.addEventListener('contextmenu', (e)=>deletePunto(e, index) )

        
    }
   
    var polyline = L.polyline(puntos, {color: 'teal'}).addTo(markers);
    markers.addTo(map)

    
    for (let i = 0; i < puntos.length - 1; i++) {
 

        distancia += getDistancia(puntos[i][0],puntos[i][1],puntos[i+1][0],puntos[i+1][1])
        
    }
    spanDistancia.innerText = `${distancia} MN`
}

// const LimpiarMapa = (puntos) => {
//     map.eachLayer(layer =>{markers.removeLayer(layer)})

// }

const LimpiarFormulario = () => {
    spanText.textContent = ''
    puntos = [];
    catalogoValido = false;
    distancia = 0;
    agregarPuntos(puntos)
    agrearPuntosTabla(puntos)
    btnModificar.parentElement.style.display = 'none'
    btnGuardar.parentElement.style.display = ''
    btnBuscar.parentElement.style.display = ''
    btnGuardar.disabled = false
    map.setView(new L.LatLng(15.525158, -90.32959),7);
}

const deletePunto = (e, id) => {
    puntos.splice(id, 1);
    agregarPuntos(puntos)
    agrearPuntosTabla(puntos)
    // console.log(id);
}
const agrearPuntosTabla = (puntos) => {
    let tbodyPuntos = document.getElementById('tbodyPuntos');
    let fragment = document.createDocumentFragment();
    tbodyPuntos.innerHTML = '';
    let index = 0;
   

    if(puntos.length > 0){
        puntos.forEach(punto => {
            let tr = document.createElement('tr');
            let tdLatitud = document.createElement('td');
            let tdLongitud = document.createElement('td');
    
    
            tdLatitud.innerText = punto[0];
            tdLongitud.innerText = punto[1];
    
    
    
            tr.appendChild(tdLatitud)
            tr.appendChild(tdLongitud)
            fragment.appendChild(tr)
            ++index;
        })

    }else{
        let tr = document.createElement('tr');
        let tdLatitud = document.createElement('td');
        tdLatitud.colSpan = 2;
        tdLatitud.innerText = 'Los puntos ingresados se visualizaran acá'
        tr.appendChild(tdLatitud)
        fragment.appendChild(tr)
    }

    tbodyPuntos.appendChild(fragment)
}


formPuntos.addEventListener('submit', agregarPunto )
formulario.addEventListener('submit', guardarInternacional )