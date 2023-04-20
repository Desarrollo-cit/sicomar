import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";

const divTabla = document.getElementById('divTabla');
let tablaReporte = new Datatable('#TablaReporte');

const BuscarDatos = async (evento) => {
    evento && evento.preventDefault();


    try {
        const url = '/sicomar/API/reporte/BusDatos'
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method : 'GET',
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

     console.log(data);


        
     tablaReporte.destroy();
        let contador = 1;
        tablaReporte = new Datatable('#TablaReporte', {
            language : lenguaje,
            data : data,
            columns : [
                { 
                    data : 'id',
                    width: '5%',
                    render : () => {      
                        return contador++;
                    }
                },
             
                {
                     data : 'ope_identificador',
                     width: '20%'
                    
                },
                { data : 'id',
                width: '9.37%',
                'render': (data, type, row, meta) => {
                    return`<button class='btn btn-success'  onclick='ApiDerrota("${row['ope_id']}", "${row['ope_identificador']}", "${row['ope_fecha_zarpe']}" )' > <i class='bi bi-cursor'></i></button>` 
                } },
                
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' data-unidad=" data-operacion="${row.id}" data-bs-toggle='modal' data-bs-target='#modalDetalle2'><i class='bi bi-gear-wide-connected'></i></button>`
                    } 
                },
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return  `<button class='btn btn-outline-info' data-operacion="${data}" data-bs-toggle='modal' data-bs-target='#modalDetalle3'><i class='bi bi-boxes'></i></button>`
                    } 
                },
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' data-operacion="${data}" data-bs-toggle='modal' data-bs-target='#modalDetalle4'><i class='bi bi-broadcast-pin'></i></button>`
                    } 
                },
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' data-operacion="${data}" data-bs-toggle='modal' data-bs-target='#modalDetalle5'><i class='bi bi-file-earmark-ruled'></i></button>`
                    } 
                },
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return  `<button class='btn btn-outline-info' data-operacion="${data}" data-bs-toggle='modal' data-bs-target='#modalDetalle6'><i class='bi bi-file-earmark-text'></i></button>`
                    } 
                },
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-outline-info' data-operacion="${data}" data-bs-toggle='modal' data-bs-target='#modalDetalle7'><i class='bi bi-binoculars'></i></button>`
                    } 
                },
                { 
                    data : 'id',
                    width: '9.37%',
                    'render': (data, type, row, meta) => {
                        return `<button class='btn btn-success' data-operacion="${data}" onclick='entregarReporte(${data})' ><i class='bi bi-arrow-right-circle'></i></button>`
                    } 
                },
            ]
        })

    } catch (error) {
        console.log(error);
    }
}


// window.ApiDerrota = (ope_id, ope_identificador) => {
//     // construir la URL con el parámetro de consulta "id" codificado en base64
//     var url = `./reporte/derrota?id=${btoa(ope_id)}`;
    
//     // redirigir el navegador a la URL
//     window.location.href = url;
//   }


  window.ApiDerrota = (ope_id, ope_identificador, ope_fecha_zarpe) => {
    // construir la URL con los parámetros de consulta "id" y "identificador" codificados en base64
    var url = `./reporte/derrota?id=${btoa(ope_id)}&identificador=${btoa(ope_identificador)}&fecha_zarpe=${btoa(ope_fecha_zarpe)}`;
    
    // redirigir el navegador a la URL
    window.location.href = url;
  }
  



BuscarDatos()