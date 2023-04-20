import { Dropdown, Tooltip, Modal, Alert } from "bootstrap";
import L from "leaflet"
import 'leaflet-easyprint'
import { Toast } from '../funciones';


const modalPuntos = new Modal(document.getElementById('modalPuntos'), {})
const formPuntos = document.querySelector('#formPuntos')
const spanDistancia = document.querySelector('#distancia');
const btnLimpiar = document.querySelector('#btnLimpiar');
const btnModificar = document.querySelector('#btnModificar');
const btnBuscar = document.querySelector('#btnBuscar');
const btnGuardar = document.querySelector('#btnGuardar');
let puntos = [];
let distancia = 0;


const iniciarModulo = ()=> {
    // buscarPaises();
    // btnModificar.style.display = 'none'
    btnModificar.parentElement.style.display = 'none'
}



const map = L.map('map', {
    center: [15.825158, -89.72959],
    zoom: 7.5
})
const markers = L.layerGroup();
const LimpiarMapa = () => {
    map.eachLayer(layer => { markers.removeLayer(layer) })

}


const icon = L.icon({
    iconUrl: './public/images/battleship.png',
    iconSize:     [35,48],
    iconAnchor:   [12, 28],
});


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
        tdLatitud.innerText = 'Los puntos ingresados se visualizaran acás'
        tr.appendChild(tdLatitud)
        fragment.appendChild(tr)
    }

    tbodyPuntos.appendChild(fragment)
}

const deletePunto = (e, id) => {
    puntos.splice(id, 1);
    agregarPuntos(puntos)
    agrearPuntosTabla(puntos)
    // console.log(id);
}


const getDistancia = (lat1,lon1,lat2,lon2) => {
    window.rad = function(x) {return x*Math.PI/180;}
    let R = 6378.137; //Radio de la tierra en km
    let dLat = rad( lat2 - lat1 );
    let dLong = rad( lon2 - lon1 );
    let a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLong/2) * Math.sin(dLong/2);
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    let d = R * c;
    let dmillas = d * 1.852
    return dmillas; //Retorna tres decimales
}



iniciarModulo();
map.on("click", onMapClick)
formPuntos.addEventListener('submit', agregarPunto )

