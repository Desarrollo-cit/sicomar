import tinymce, { TinyMCE } from"tinymce";
import 'tinymce/themes/silver'
import 'tinymce/icons/default'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/lists'
import 'tinymce/models/dom/model'
import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";

const inputCatalogo = document.getElementById('catalogo');
const inputNombre = document.getElementById('nombre');
const divPuntosOrden = document.getElementById('divpuntosdeorden');
const agregarInputsorden = document.getElementById('agregarInputsorden');
const quitarInputsorden = document.getElementById('quitarInputsorden');
const btnGuardar = document.getElementById('btnGuardar');
const formZarpe = document.querySelector('#formZarpe');


document.addEventListener('DOMContentLoaded', ()=> {
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


let cantidad = 0
const agregarInputsPersonal = async (e) => {

    cantidad = ++cantidad;


    const fragment = document.createDocumentFragment();
    const divCuadro = document.createElement('div');
    const divRow = document.createElement('div');
    const divRow1 = document.createElement('div');
    const divCol1 = document.createElement('div');
    const divCol2 = document.createElement('div');

    const inputCatalogo = document.createElement('input');
    const inputNombresyApellidos = document.createElement('input')

    const inputFecha = document.createElement('input')
    const label1 = document.createElement('label')
    const label2 = document.createElement('label')

    const buttonEliminar = document.createElement('input')


    divRow.classList.add("row", "justify-content-center");
    divCuadro.classList.add("col-lg-12", "border", "rounded", "mb-2", "bg-light");

    divRow1.classList.add("row", "justify-content-start", "mb-2");
    divCol1.classList.add("col-lg-2");
    divCol2.classList.add("col-lg-10");
    inputCatalogo.name = `catalogo[]`
    inputCatalogo.id = `catalogo${cantidad}`
    inputCatalogo.type = 'number'
    inputCatalogo.classList.add("form-control")
    inputCatalogo.dataset.cantidad = cantidad
    inputCatalogo.addEventListener('change', (e) => buscardatos(e))
    inputNombresyApellidos.name = `nombre[]`
    inputNombresyApellidos.id = `nombre${cantidad}`
    inputNombresyApellidos.type = 'text'
    inputNombresyApellidos.required = true;
    inputNombresyApellidos.readOnly = true;
    inputNombresyApellidos.classList.add("form-control")
    inputFecha.classList.add("form-control")
    inputFecha.dataset.catalogo = cantidad

    label1.innerText = `Catalogo `
    label1.classList.add("text-dark")
    label2.innerText = `Nombres y Apellidos `
    label2.htmlFor = `nombre${cantidad}`
    label2.classList.add("text-dark")
    buttonEliminar.classList.add('form-check-input', 'mt-4')
    buttonEliminar.type = 'checkbox'
    buttonEliminar.value = ''
    buttonEliminar.dataset.cantidad = cantidad
    divCuadro.dataset.cantidad = cantidad



    label1.appendChild(inputCatalogo)
    divCol1.appendChild(label1)
    divCol2.appendChild(label2)
    divCol2.appendChild(inputNombresyApellidos)
    divRow1.appendChild(divCol1)
    divRow1.appendChild(divCol2)
    inputCatalogo.addEventListener('change', (e) => buscardatos(e))
    inputFecha.addEventListener('change', (e) => verificarfecha(e))
    divCuadro.appendChild(divRow1)
    divRow.appendChild(divCuadro)
    fragment.appendChild(divRow)


    divPuntosOrden.appendChild(fragment)
}


const quitarInputsOrden = () => {
    const checks = document.querySelectorAll('input[type="number"]')
    //    console.log(checks);
   let cantidadSegunda = cantidad;
//    console.log(cantidadInputs);

if (cantidad > 0) {
divPuntosOrden.removeChild(divPuntosOrden.lastElementChild);
cantidad--;
}
  
}
const buscardatos = async (e) => {

    let catalogo = e.target.value
    const numero = e.target.dataset.cantidad
    const valor = noIngresarRepetidos(catalogo, numero)
    

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
                const nombre = e.target.parentElement.parentElement.nextElementSibling.lastElementChild
                // const catalogo1 = e.target.parentElement.parentElement.nextElementSibling.nextElementSibling.lastElementChild

                nombre.value = grado.trim() + " " + per_nom1.trim() + " " + per_nom2.trim() + " " + per_ape1.trim() + " " + per_ape2.trim() + " " + per_ape3.trim()
                catalogo1.dataset.catalogo = catalogo;

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
const noIngresarRepetidos = (catalogo, numero) => {

    let valor = false;
    const catalogos = document.querySelectorAll('input[type="number"]')
    catalogos.forEach(element => {
        // console.log(element.value);

        if (element.value == catalogo && element.dataset.cantidad != numero) {
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



const guardarOperaciones = async e => {
    e.preventDefault();

    let ope_situacion = tinymce.get('ope_situacion').getContent()
    let ope_mision = tinymce.get('ope_mision').getContent()
    let ope_ejecucion = tinymce.get('ope_ejecucion').getContent()

   // console.log(formZarpe);

    if (validarFormulario(formZarpe, ['codigo','ope_situacion','ope_mision','ope_ejecucion']) && ope_situacion != '' && ope_mision != ''&& ope_ejecucion != '') {

        // console.log('hola');
        try {

            const url = '/sicomar/API/operaciones/guardar'

            const body = new FormData(formZarpe);
            body.append('ope_situacion', ope_situacion)
            body.append('ope_mision', ope_mision)
            body.append('ope_ejecucion', ope_ejecucion)
            body.delete('ope_reutilizar')
            body.append('ope_reutilizar', formZarpe.ope_reutilizar.checked?'1':'0')
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
                    recargarModalArmas(formZarpe.topico.value)
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

    } else {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los campos'
        });
    }

}



// const guardarOperaciones = async (evento) => {
//     evento.preventDefault();

//     let formularioValido = validarFormulario(formZarpe, ['id']);
//     if (!formularioValido) {
//         Toast.fire({
//             icon: 'warning',
//             title: 'Debe llenar todos los campos'
//         })
//         return;
//     }



//     try {
//         //Crear el cuerpo de la consulta
//         const url = '/sicomar/API/operaciones/guardar'

//         const body = new FormData(formZarpe);
//         body.delete('id');
//         const headers = new Headers();
//         headers.append("X-Requested-With", "fetch");

//         const config = {
//             method: 'POST',
//             headers,
//             body
//         }

//         const respuesta = await fetch(url, config);
//         const data = await respuesta.json();
//         console.log(data);
//         const { mensaje, codigo, detalle } = data;
//         // const resultado = data.resultado;
//         let icon = "";
//         switch (codigo) {
//             case 1:
//                 icon = "success"
//                 formZarpe.reset();
               
//                 break;
//             case 2:
//                 icon = "warning"
//                 formZarpe.reset();

//                 break;
//             case 3:
//                 icon = "error"

//                 break;
//             case 4:
//                 icon = "error"
//                 console.log(detalle)

//                 break;

//             default:
//                 break;
//         }

//         Toast.fire({
//             icon: icon,
//             title: mensaje,
//         })


//        // buscararmas()

//     } catch (error) {
//         console.log(error);
//     }
// }


// inputCatalogo.addEventListener('change', buscarCatalogo);
agregarInputsorden.addEventListener('click', agregarInputsPersonal)
quitarInputsorden.addEventListener('click', quitarInputsOrden)
btnGuardar.addEventListener('click', guardarOperaciones)
