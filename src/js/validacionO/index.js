import { Dropdown, Tooltip, Modal, Alert } from "bootstrap";
import L from "leaflet"
import 'leaflet-easyprint'
import { Toast, validarFormulario } from '../funciones';
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";


const divTabla = document.getElementById('tabla');
const modalElement = document.getElementById('modalReporte')
// let punto = [];
// let idDerrota = [];
 let distancia = 0;
let puntos = []
let punto = []

window.limpiar = () => {
    tablaReporte.clear();
}

let contador = 1
let tablaReporte = new Datatable('#dataTable', {
    language: lenguaje,
    data: null,
    columns: [
        {
            data: 'id',
            width: '5%',
            render: () => {
                return contador++;
            }
        },
        { data: "ope_identificador", "width": "35%" },
        {
            data: "ope_id",
            "render": (data, type, row, meta) => `<button class='btn btn-info' data-operacion="${data}" data-bs-toggle='modal' data-bs-target='#modalReporte'><i class='bi bi-file-earmark-post'></i></button>`,
            "width": "10%"
        },
        {
            data: 'rechazo',
            width: '9.37%',
            'render': (data, type, row, meta) => {
                return `<button class='btn btn-danger' onclick='ApiRechazo("${row['ope_id']}")'><i class='bi bi-x-circle'></i></button>`
            }
        },
        {
            data: 'cambiar',
            width: '9.37%',
            'render': (data, type, row, meta) => {
                return `<button class='btn btn-success' onclick='ApiCambio("${row['ope_id']}")'><i class='bi bi-check-circle'></i></button>`
            }
        },

    ],
})





const BuscarDatos = async (evento) => {
    evento && evento.preventDefault();


    try {
        const url = '/sicomar/API/validacionO/BusDatos'
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

       // console.log(data);

        tablaReporte.clear().draw();
        if (data) {
            // console.log(data)
            tablaReporte.rows.add(data).draw();


        }



    } catch (error) {
        console.log(error);
    }
}





//mostrar modal
modalElement.addEventListener('show.bs.modal', async (event) => {  

   
    const boton = event.relatedTarget;
    const id = boton.dataset.operacion;
    console.log(id)
 
    const tablaInformacion = document.getElementById('tablaInformacion')
    const tablaDerrota = document.getElementById('tablaDerrota')
    const tablaPersonal = document.getElementById('tablaPersonal')
    const tablaUnidades = document.getElementById('tablaUnidades')
    const tablaMotores = document.getElementById('tablaMotores')
    const tablaNovedades = document.getElementById('tablaNovedades')
    const tablaComunicaciones = document.getElementById('tablaComunicaciones')
    const tablaConsumos = document.getElementById('tablaConsumos')
    const tablaRecomendaciones = document.getElementById('tablaRecomendaciones')
    const tablaInteligencia = document.getElementById('tablaInteligencia')

    const url = `/sicomar/API/validacionO/BusInformacion?id=${id}`
    const headers = new Headers();
    headers.append("X-requested-With", "fetch");

    const config = {
        method: 'GET',
    }

    const respuesta = await fetch(url, config);
    const informacion = await respuesta.json();
    console.log(informacion)
 


    LimpiarMapa();
    
    const {identificador, atraque, zarpe, tipo, puntos, personal, unidades, motores, consumos, comunicaciones, novedades, recomendaciones, inteligencia} = informacion
    console.log(puntos);
    //dibujar puntos


    let index = 0;
    let puntosArray = []
    puntos.forEach(punto => {
        let dataPunto = [punto.latitud, punto.longitud, punto.fecha]
        puntosArray = [...puntosArray, dataPunto]
        let marker = L.marker(dataPunto, {icon} ).addTo(markers);
        marker.bindPopup(`<b>Punto ${index + 1}</b><br>Latitud: ${punto.latitud}<br>Longitud: ${punto.longitud}<br>Fecha: ${punto.fecha}`);
        index++;
    });
    L.polyline(puntosArray, {color: 'teal'}).addTo(markers);
    markers.addTo(map)
    let distancia = 0;
    while (tablaDerrota.rows.length > 1) {
        tablaDerrota.deleteRow(-1)
    }
    let contador = 1;
   // console.log(puntosArray);


    for (let i = 0; i < puntosArray.length - 1; i++) {

        const row = tablaDerrota.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${puntosArray[i][0]}</td><td>${puntosArray[i][1]}</td><td>${puntosArray[i][2]}</td><td>${ parseFloat(distancia).toFixed(2)}</td>`
        distancia += getDistancia(puntosArray[i][0],puntosArray[i][1],puntosArray[i+1][0],puntosArray[i+1][1])   
        contador++
    }
    const rowFinal = tablaDerrota.insertRow();
    rowFinal.innerHTML = `<td>${contador}</td><td>${puntosArray[puntosArray.length - 1][0]}</td><td>${puntosArray[puntosArray.length - 1][1]}</td><td>${puntosArray[puntosArray.length - 1][2]}</td><td>${ parseFloat(distancia).toFixed(2)}</td>`
    const rowTotal = tablaDerrota.insertRow();
    rowTotal.innerHTML = `<td class='fw-bold' colspan='4'>DISTANCIA TOTAL</td><td class='fw-bold'>${parseFloat(distancia).toFixed(2)}</td>`


    //INFORMACION PRINCIPAL
    while(tablaInformacion.rows.length > 1){
        tablaInformacion.deleteRow(-1);
    }
    const row = tablaInformacion.insertRow();
    const zarpeFecha = new Date(zarpe)
    const atraqueFecha = new Date(atraque)

    row.innerHTML = `<td>${identificador}</td><td>${tipo}</td><td>${zarpeFecha.toLocaleString()}</td><td>${atraqueFecha.toLocaleString()}</td>`


    //PERSONAL

    while(tablaPersonal.rows.length > 1){
        tablaPersonal.deleteRow(-1);
    }
    contador = 1;
    personal.forEach( persona => {
        const row = tablaPersonal.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${persona.catalogo}</td><td>${persona.nombre}</td>`
        contador ++
    })

    // console.log(personal);

    //UNIDADES
    while(tablaUnidades.rows.length > 1){
        tablaUnidades.deleteRow(-1);
    }
    contador = 1;
    unidades.forEach( unidad => {
        const row = tablaUnidades.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${unidad.tipo}</td><td>${unidad.nombre}</td>`
        contador ++
    })

    //MOTORES
    while(tablaMotores.rows.length > 1){
        tablaMotores.deleteRow(-1);
    }
    contador = 1;
    motores.forEach( motor => {
        const row = tablaMotores.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${motor.serie}</td><td>${motor.horas}</td><td>${motor.rpm}</td><td>${motor.fallas}</td><td>${motor.observaciones}</td>`
        contador ++
    })

    //COMUNICACIONES
    while(tablaComunicaciones.rows.length > 1){
        tablaComunicaciones.deleteRow(-1);
    }
    contador = 1;
    comunicaciones.forEach( comunicacion => {
        const row = tablaComunicaciones.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${comunicacion.medio}</td><td>${comunicacion.receptor}</td><td>QRK${comunicacion.calidad}</td>`
        contador ++
    })

    //CONSUMOS
    while(tablaConsumos.rows.length > 1){
        tablaConsumos.deleteRow(-1);
    }
    contador = 1;
    consumos.forEach( consumo  => {
        const row = tablaConsumos.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${consumo.insumo}</td><td>${consumo.cantidad } ${consumo.unidad}</td>`
        contador ++

        // console.log(consumo);
    })

  
    while (tablaNovedades.rows.length > 1) {
        tablaNovedades.deleteRow(-1);
      }
      
       contador = 1;
      let fecha = '';
      console.log(novedades)
      if (novedades.length === 0) {
        const rowSinNovedad = tablaNovedades.insertRow();
        rowSinNovedad.innerHTML = `<td colspan='2'>Sin novedad</td>`;
      } else {
        novedades.forEach((novedad) => {
          if (fecha !== novedad.fecha) {
            fecha = novedad.fecha;
            const rowTitulo = tablaNovedades.insertRow();
            rowTitulo.innerHTML = `<td colspan='2'>${fecha}</td>`;
          }
          
          const row = tablaNovedades.insertRow();
          row.innerHTML = `<td>${novedad.hora}</td><td style='text-align: justify;'>${novedad.novedad}</td>`;
          contador++;
        });
      }

    //RECOMENDACIONES
    while(tablaRecomendaciones.rows.length > 1){
        tablaRecomendaciones.deleteRow(-1);
    }
    contador = 1;
    recomendaciones.forEach( recomendacion => {
        const row = tablaRecomendaciones.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${recomendacion.recomendacion}</td>`
        contador ++
    })
    //INTELIGENCIA
    while(tablaInteligencia.rows.length > 1){
        tablaInteligencia.deleteRow(-1);
    }
    contador = 1;
    inteligencia.forEach( fila => {
        const row = tablaInteligencia.insertRow();
        row.innerHTML = `<td>${contador}</td><td>${fila.informacion}</td>`
        contador ++
    })

    // console.log(novedades);

    //Centrando el mapa en el nuevo punto
    setTimeout(function() {
        map.invalidateSize();
    }, 500);
    
    map.setView(new L.LatLng(informacion.puntos[0].latitud,informacion.puntos[0].longitud),8);
    // map.setZoom(8);
    
})
const markers = L.layerGroup()
const map = L.map('map', {
    center: [15.525158, -90.32959],
    zoom: 7
})

// const icon = L.icon({
//     iconUrl: '../assets/img/barquito.png',
//     iconSize:     [35,48],
//     iconAnchor:   [12, 28],
// });



const icon = L.icon({
    iconUrl: './public/images/barquito.png',
    iconSize:     [35,48],
    iconAnchor:   [12, 28],
});

const LimpiarMapa = () => {
    map.eachLayer(layer =>{markers.removeLayer(layer)})
    puntos = []
    punto = []
    distancia = 0;
    // spanDistancia.innerText = `${distancia} MN`
}

const grayScale = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    maxNativeZoom:19,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoiZGFuaWVsZmo5NzUiLCJhIjoiY2wzZXkyMHliMDJpeDNwbzdyM3VjNXF0NSJ9.qN1T0BLAdQ5T4_K9XdJGnQ'
}).addTo(map);


const calcucarDistanciaTotal = async (id) => {
    let total = 0;
    let url = `puntos.php?id=${id}`
    let config = { method : "GET" }
    response = await fetch(url, config);
    let puntos = await response.json()

    for (let i = 0; i < puntos.length - 1; i++) {
       
        total += getDistancia(puntos[i].latitud,puntos[i].longitud,puntos[i+1].latitud,puntos[i+1].longitud)   

    }

    return total.toFixed(2);
}




const getDistancia = (lat1, lon1, lat2, lon2) => {
    const rad = (x) => {
      return x * Math.PI / 180;
    };
  
    let R = 6378.137; // Radio de la tierra en km
    let dLat = rad(lat2 - lat1);
    let dLong = rad(lon2 - lon1);
    let a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(rad(lat1)) *
        Math.cos(rad(lat2)) *
        Math.sin(dLong / 2) *
        Math.sin(dLong / 2);
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    let d = R * c;
    let dmillas = d * 1.852; // Factor de conversión de km a millas
    return dmillas.toFixed(3); // Retorna tres decimales
  };



  window.ApiCambio = (ope_id) => {

    Swal.fire({
        title: 'Confirmación',
        icon: 'warning',
        text: '¿Esta seguro que desea validar esta operacion?',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, aprobar.'
    }).then(async (result) => {
        if (result.isConfirmed) {

            const url = `/sicomar/API/validacionO/CambioSit?id=${ope_id}`  
            const config = {
                method: 'GET',
            }

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data)
   
            const { mensaje, codigo, detalle } = data;
            let icon = "";
            switch (codigo) {
                case 1:
                    icon = "success"
                    BuscarDatos()
                    break;
               
                case 0:
                    icon = "error"
        
                    break;
                case 4:
                    icon = "error"
                    console.log(detalle)
                    
        
                    break;
        
                default:
                    break;
            }
        
            Toast.fire({
                icon: icon,
                title: mensaje,
            })

            
    
        }
    })
}


window.ApiRechazo = (ope_id) => {

    Swal.fire({
        title: 'Confirmación',
        icon: 'error',
        text: '¿Esta seguro que desea rechazar esta operacion?',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, rechazar.'
    }).then(async (result) => {
        if (result.isConfirmed) {

            const url = `/sicomar/API/validacionO/CambioRec?id=${ope_id}`  
            const config = {
                method: 'GET',
            }

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data)
   
            const { mensaje, codigo, detalle } = data;
            let icon = "";
            switch (codigo) {
                case 1:
                    icon = "success"
                    BuscarDatos()
                    break;
               
                case 0:
                    icon = "error"
        
                    break;
                case 4:
                    icon = "error"
                    console.log(detalle)
                    
        
                    break;
        
                default:
                    break;
            }
        
            Toast.fire({
                icon: icon,
                title: mensaje,
            })

            
    
        }
    })
}








BuscarDatos()
