import { Dropdown, Tooltip, Modal, Alert } from "bootstrap";
import L from "leaflet"
import 'leaflet-easyprint'
import { Toast, validarFormulario } from '../funciones';
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";

const divTabla = document.getElementById('divTabla');

window.limpiar = () => {
    tablaReporte.clear();
}

let contador = 1
let tablaReporte = new Datatable('#TablaReporte', {
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

                {
                    data: 'ope_identificador',
                    width: '20%'

                },
                {
                    data: 'derrota',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-success'  onclick='ApiDerrota("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )' > <i class='bi bi-cursor'></i></button>`
                    }
                },

                {
                    data: 'motores',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' onclick='ApiMotores("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )' ><i class='bi bi-gear-wide-connected'></i></button>`
                    }
                },
                {
                    data: 'consumos',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' onclick='ApiConsumos("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )' ><i class='bi bi-boxes'></i></button>`
                    }
                },
                {
                    data: 'comunicaciones',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' onclick='ApiComunicaciones("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )' ><i class='bi bi-broadcast-pin'></i></button>`
                    }
                },
                {
                    data: 'novedades',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' onclick='ApiNovedades("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )'><i class='bi bi-file-earmark-ruled'></i></button>`
                    }
                },
                {
                    data: 'lecciones',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' onclick='ApiLecciones("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )'><i class='bi bi-file-earmark-text'></i></button>`
                    }
                },
                {
                    data: 'inteligencia',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' onclick='ApiInteligencia("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )'><i class='bi bi-binoculars'></i></button>`
                    }
                },
                {
                    data: 'cambiar',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-success' onclick='ApiCambio("${row['ope_id']}", "${row['ope_identificador']}" )'><i class='bi bi-arrow-right-circle'></i></button>`
                    }
                },
            ]
})





const BuscarDatos = async (evento) => {
    evento && evento.preventDefault();


    try {
        const url = '/sicomar/API/reporte/BusDatos'
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

       console.log(data);

       tablaReporte.clear().draw();
       if (data) {
           // console.log(data)
           tablaReporte.rows.add(data).draw();


       }


  
    } catch (error) {
        console.log(error);
    }
}





window.ApiInteligencia = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/inteligencia?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}

window.ApiDerrota = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/derrota?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}

window.ApiLecciones = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/lecciones?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}

window.ApiNovedades = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/novedades?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}
window.ApiMotores = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/motores?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}

window.ApiConsumos = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/consumos?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}

window.ApiComunicaciones = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    var url = `./reporte/comunicaciones?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    window.location.href = url;
}



window.ApiCambio = (ope_id, ope_identificador) => {

    Swal.fire({
        title: 'Confirmación',
        icon: 'warning',
        text: '¿Esta seguro que ha finalizado el ingreso de datos de esta Operecion?',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, he finalizado.'
    }).then(async (result) => {
        if (result.isConfirmed) {

            const url = `/sicomar/API/reporte/CambioSit?id=${ope_id}`  
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