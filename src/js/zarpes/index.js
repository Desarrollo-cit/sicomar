import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";
import { Modal } from "bootstrap";
import { formatDate, formatRange } from "fullcalendar";
import tinymce, { TinyMCE } from "tinymce";
import 'tinymce/themes/silver'
import 'tinymce/icons/default'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/lists'
import 'tinymce/models/dom/model'


const formZarpe = document.getElementById('formZarpe');
const btnModificar = document.getElementById('btnModificar');
const divAsignados = document.querySelector('#divAsignados');
const modal = new Modal(document.getElementById('myModal'));
const elementModalModificar = document.getElementById('modalModifica');
const modalModifica = new Modal(elementModalModificar);
const modalVer = new Modal(document.getElementById('modalVer'));
const modalImprimir = new Modal(document.getElementById('modalImprimir'));
const situacion = document.getElementById('ope_situacion');
const divTabla2 = document.getElementById('tabla1');
let tabla_resultados = new Datatable('#tabla_resultados');
const agregarInputsorden = document.getElementById('agregarInputsorden');
// const divPuntosOrden = document.getElementById('divpuntosdeorden');
const quitarInputsorden = document.getElementById('quitarInputsorden');

const btnCancelar = document.getElementById('btnCancelar')
const divTabla = document.getElementById('divTabla');
let tablaZarpes = new Datatable('#zarpesTabla');
let contadorInputs = 1;

//console.log(formZarpe) 
//btnModificar.parentElement.style.display = 'none';
// btnCancelar.parentElement.style.display = 'none';
// btnCancelar.disabled = true;
// btnGuardar.disabled = false;
// btnModificar.disabled = false;
document.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: 'textarea',
        promotion: false,
        height: 200,
        menubar: true,
        plugins: [
            'lists ',
            'advlist',

        ],
        advlist_number_styles: 'default,lower-alpha,lower-greek,lower-roman,upper-alpha,upper-roman',
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist advlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
})

let cantidad = 1

const crearInputs = (e, catalogo = '', nombre = '') => {
    console.log(catalogo)
    const fragment = document.createDocumentFragment();
    const divRow= document.createElement('div');
    divRow.classList.add('row', 'justify-content-center', 'mb-3')

    const div1 = document.createElement('div');
    const input1 = document.createElement('input');
    div1.classList.add('col-lg-4', 'text-center')
    input1.type = 'tel'
    input1.classList.add('form-control')
    input1.id = `catalogo${cantidad}`
    input1.name = `catalogo${cantidad}`
    input1.placeholder = 'Ingrese el catálogo'
    input1.dataset.cantidad = cantidad;
    input1.addEventListener('change', buscardatos );
    input1.value = catalogo

    div1.appendChild(input1)
    divRow.appendChild(div1)
    
    const div2 = document.createElement('div');
    const input2 = document.createElement('input');
    div2.classList.add('col-lg-8', 'text-center')
    input2.type = 'text'
    input2.classList.add('form-control')
    input2.id = `nombre${cantidad}`
    input2.catalogo = `nombre${cantidad}`
    input2.disabled = true
    input2.placeholder = 'El nombre del asignado aparecera automáticamente'
    input2.value = nombre
    div2.appendChild(input2)

    divRow.appendChild(div2)

    fragment.appendChild(divRow)

    divAsignados.appendChild(fragment);
    cantidad ++;

}



const buscardatos = async (e) => {

    let catalogo = e.target.value
    let cantidad = e.target.dataset.cantidad
    const valor = noIngresarRepetidos(catalogo, cantidad)
    

    if (valor == false) {
        if (!isNaN(catalogo)) {

            const url = `/sicomar/API/operaciones/catalogo?catalogo=${catalogo} `
            const headers = new Headers();
            headers.append("X-Requested-With", "fetch");

            const config = {
                method: 'GET',
                headers,
            }

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();

            // console.log(e.target.parentElement.parentElement.nextElementSibling.lastElementChild);
            if (data) {
                const { per_nom1, per_nom2, per_ape1, per_ape2, per_ape3, grado, per_situacion } = data[0]
                const nombre = e.target.parentElement.nextElementSibling.lastElementChild

                nombre.value = grado.trim() + " " + per_nom1.trim() + " " + per_nom2.trim() + " " + per_ape1.trim() + " " + per_ape2.trim() + " " + per_ape3.trim()
                // catalogo1.dataset.catalogo = catalogo;

            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Catalogo incorrecto',
                    timer: 10000
                })
                document.getElementById(`catalogo${numero}`).focus();
            }
        } else {
            // formaltas.dependencia.value = ""

            Toast.fire({
                icon: 'error',
                title: 'Ingrese  un catalogo correcto'
            })
        }


    } else {
        e.target.value = "";
        // console.log(valor);
        e.target.focus();
    }
}
const quitarInputsOrden = () => {
   
    if (cantidad > 0) {

        divAsignados.removeChild(divAsignados.lastElementChild);
        cantidad--;
    } else {
        Toast.fire({
            icon: 'error',
            title: 'Debe de ingresar al menos una persona',
            timer: 5000
        })
    }

}

const noIngresarRepetidos = (catalogo, cantidad) => {

    let valor = false;
    console.log(cantidad)
    const catalogos = document.querySelectorAll('input[type="tel"]')
    catalogos.forEach(element => {
        // console.log(element.value);

        if (element.value == catalogo && element.dataset.cantidad != cantidad) {
            Toast.fire({
                icon: 'error',
                title: 'Catalogo ya ingresado',
                timer: 1000
            })

            valor = true;
        }
    });
    return valor
}

const buscarZarpes = async (evento) => {
    evento && evento.preventDefault();

    try {
        const url = '/sicomar/API/zarpes/buscar'
        const headers = new Headers();
        headers.append("X-Requested-With", "fetch");

        const config = {
            method: 'GET', headers
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

       // console.log(data);


        tablaZarpes.destroy();
        let contador = 1;
        
        tablaZarpes = new Datatable('#zarpesTabla', {
            language: lenguaje,
            data: data,
            columns: [
                {
                    data: 'ope_id',
                    render: () => {
                        return contador++;
                    }
                },
                { data: 'tipo' },
                { data: 'ope_identificador' },
                { data: 'unidades' },
                // {
                //     data : "unidades",
                //     "render" : data => {
                //                  console.log(data);

                //         if (data){
                //             let unidad = '';
                //             data.forEach(element => {
                //                 unidad += element.tipo + ' ' + element.asi_unidad + '\n'
                                
                //             });
                //             return unidad;
                //         }else{
                //             return "SIN PERSONAL ASIGNADO"
                //         }
                //     },
                //     "width" : "20%"
                // },
                {
                    data: "situacion",
                    "render": (data, type, row, meta) => {
                        switch (data) {
                            case '1':
                                return 'REPORTE DE PATRULLA'
                                break;
                            case '2':
                                return 'REVISIÓN OFICIAL DE OPERACIONES'
                                break;
                            case '3':
                                return 'REVISIÓN COMANDANTE'
                                break;
                            case '4':
                                return 'VALIDADO'
                                break;

                            default:
                                return 'ELIMINADO'
                                break;
                        }
                    },
                    "width": "15%"
                },

                {
                    data: "ope_id",
                    "render": (data, type, row, meta) => `<button class='btn btn-success' onclick="verPersonal(${row.id})"><i class='bi bi-people-fill'></i></button>`,
                    "width": "5%"
                },
                {
                    data: "ope_id",
                    "render": (data, type, row, meta) => `<button class='btn btn-info' onclick="ImprimirRegistro(${row.id})"><i class='bi bi-printer'></i></button>`,
                    "width": "5%"
                },


                {
                    data: "ope_id",
                    "render": (data, type, row, meta) => `<button class='btn btn-primary' onclick="verRegistro(${row.id})"><i class='bi bi-eye'></i></button>`,
                    "width": "5%"
                },
                {
                    data: "ope_id",
                    "render": (data, type, row, meta) => `<button class='btn btn-warning' onclick="colocarInformacion(${row.id})"><i class='bi bi-pencil-square'></i></button>`,
                    "width": "5%"
                },

                {
                    data: "ope_id",
                    "render": (data, type, row, meta) => `<button class='btn btn-danger' onclick="eliminarRegistro(${row.id})"><i class='bi bi-trash'></i></button>`,
                    "width": "5%"
                },
            ]
        })

    } catch (error) {
        console.log(error);
    }
}

window.verPersonal = async (id) => {
    modal.show();
    // evento.preventDefault();
    const val_id = id
    console.log(val_id)


    const url = `/sicomar/API/zarpes/buscarPer?val_id=${val_id}`
    const headers = new Headers();
    headers.append("X-Requested-With", "fetch");

    const config = {
        method: 'GET', headers
    }

    const respuesta = await fetch(url, config);
    const data = await respuesta.json();

    console.log(data);

    if (data) {
        modal.show();


        tabla_resultados.destroy();
        let contador = 1;
        tabla_resultados = new Datatable('#tabla_resultados', {
            language: lenguaje,
            data: data,
            columns: [
                {
                    data: 'id',
                    render: () => {
                        return contador++;
                    }
                },
                { data: 'catalogo' },
                { data: 'nombre' },


            ]
        })



    }


}




window.ImprimirRegistro = async (id) => {
    modalImprimir.show();
    // evento.preventDefault();
    const val_id = id
    console.log(val_id)


    const url = `/sicomar/API/zarpes/imprimirRegistro?val_id=${val_id}`
    const headers = new Headers();
    headers.append("X-Requested-With", "fetch");

    const config = {
        method: 'GET', headers
    }

    const respuesta = await fetch(url, config);
    const data = await respuesta.json();

    console.log(data);

    if (data) {
        modalImprimir.show();


        tabla_resultados.destroy();
        let contador = 1;
        tabla_resultados = new Datatable('#tabla_resultados', {
            language: lenguaje,
            data: data,
            columns: [
                {
                    data: 'id',
                    render: () => {
                        return contador++;
                    }
                },
                { data: 'catalogo' },
                { data: 'nombre' },


            ]
        })



    }


}

window.verRegistro = async (id) => {
    modalVer.show();
    // evento.preventDefault();
    const val_id = id
    console.log(val_id)


    const url = `/sicomar/API/zarpes/verRegistro?val_id=${val_id}`
    const headers = new Headers();
    headers.append("X-Requested-With", "fetch");

    const config = {
        method: 'GET', headers
    }

    const respuesta = await fetch(url, config);
    const data = await respuesta.json();

    console.log(data);

    if (data) {
        modalVer.show();


        tabla_resultados.destroy();
        let contador = 1;
        tabla_resultados = new Datatable('#tabla_resultados', {
            language: lenguaje,
            data: data,
            columns: [
                {
                    data: 'id',
                    render: () => {
                        return contador++;
                    }
                },
                { data: 'catalogo' },
                { data: 'nombre' },


            ]
        })



    }


}

window.eliminarRegistro = async (id) => {
    const val_id = id
    console.log(val_id)

    //    alert(id)

    Swal.fire({
        title: 'Confirmación',
        text: "¿Esta seguro que desea eliminar este registro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {


                const url = `/sicomar/API/zarpes/eliminarRegistro?val_id=${val_id}`
                const body = new FormData();
                body.append('valor', id)
                const headers = new Headers();
                headers.append("X-Requested-With", "fetch");

                const config = {
                    method: 'POST',
                    headers,
                    body
                }

                const respuesta = await fetch(url, config);
                const data = await respuesta.json();

                console.log(data);

                const { mensaje, codigo, detalle } = data;
                // const resultado = data.resultado;
                let icon = "";
                switch (codigo) {
                    case 1:
                        icon = "success"
                        buscarZarpes()
                        break;
                    case 2:
                        icon = "warning"
                        formZarpe.reset();

                        break;
                    case 3:
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

            } catch (error) {
                console.log(error);
            }
        }
    })
}

const modificaRegistro = async (e) => {
    modalModifica.show();

   let  info1 = tinymce.get('ope_situacion').getContent();
   let  info2 = tinymce.get('ope_mision').getContent();
   let  info3 = tinymce.get('ope_ejecucion').getContent();
   let catalogos = [];
//    console.log(catalogos);

        
        let inputCatalogos = document.querySelectorAll("[id^='catalogo']");
        
        inputCatalogos.forEach(input => {
            catalogos.push(input.value)
        })

    e.preventDefault();

    try {
        //Crear el cuerpo de la consulta
        const url = `/sicomar/API/zarpes/modificar`

        const body = new FormData(formZarpe);
  

        body.append('ope_situacion', info1 )
        body.append('ope_mision',info2)
        body.append('ope_ejecucion', info3)
        body.append('catalogos', catalogos)

        
        const headers = new Headers();
        // body.append('id', id)

        headers.append("X-Requested-With", "fetch");

        const config = {
            method: 'POST',
            headers,
            body
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);
        const { mensaje, codigo, detalle } = data;
        // const resultado = data.resultado;
        
        let icon = "";
        switch (codigo) {
            case 1:
                icon = "success"
                modalModifica.hide()
                // formZarpe.reset();

                break;
            case 2:
                icon = "warning"
                formZarpe.reset();

                break;
            case 3:
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
        modalModifica.hide()


        buscarZarpes()
        formZarpe.reset();
        // btnModificar.parentElement.style.display = 'none';
        // btnModificar.disabled = true;
        
        // divTabla.style.display = ''

    } catch (error) {
        console.log(error);
    }
}



buscarZarpes();

window.colocarInformacion = async (id) => {
formZarpe.reset();
while(cantidad > 1){
    quitarInputsOrden();
}
    //    alert(id)
    //     modalModifica.hide()
    //   return

    try {

        const url = `/sicomar/API/zarpes/colocarInformacion?id=${id}`
        const headers = new Headers();
        headers.append("X-Requested-With", "fetch");

        const config = {
            method: 'GET',
            headers
        }


        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

         //console.log(data)
      
         

        const { operacion, unidad, personal } = data;
        // console.log(personal)


        if (operacion) {

            formZarpe.codigo.value = id
            operacion.forEach(o => {

                tinymce.get('ope_situacion').setContent(o.ope_situacion)
                tinymce.get('ope_mision').setContent(o.ope_mision)
                tinymce.get('ope_ejecucion').setContent(o.ope_ejecucion)
                formZarpe.ope_tipo.value = o.ope_tipo
                formZarpe.ope_fecha_zarpe.value = o.ope_fecha_zarpe
                formZarpe.ope_fecha_atraque.value = o.ope_fecha_atraque
                formZarpe.ope_identificador.value= o.ope_identificador
                formZarpe.ope_dependencia.value= o.ope_dependencia

            });
                
                if (unidad) {
                    // console.log(unidad[0].id)

                    formZarpe.asi_unidad.value = unidad[0].id

                }
                if(personal){
                    
                    // divAsignados.removeChild(divAsignados.lastElementChild);


                    let i = 0;
                    personal.forEach(persona => {
                        
                        if(i >= contadorInputs ){
                            crearInputs('',persona.catalogo, persona.nombre)
                        }else{
                            // console.log(formZarpe.catalogo0)
                         
                            document.getElementById(`catalogo${i}`).value = persona.catalogo
                            document.getElementById(`nombre${i}`).value = persona.nombre
                
                        }
              
                        i++;
                    });

                }
            modalModifica.show()

          
        }

    } catch (error) {
        console.log(error);
    }


}


buscarZarpes();

// inputCatalogo1.addEventListener('change', buscarNombre);
agregarInputsorden.addEventListener('click', crearInputs)
quitarInputsorden.addEventListener('click', quitarInputsOrden)
btnModificar.addEventListener('click', modificaRegistro)



