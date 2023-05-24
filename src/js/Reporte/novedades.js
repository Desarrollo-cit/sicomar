import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";


const formNovedades = document.getElementById('formNovedades');
const divNovedades = document.getElementById('divNovedades');
const buttonAgregarNovedades = document.querySelector('#buttonAgregarNovedades');
const buttonQuitarNovedades = document.querySelector('#buttonQuitarNovedades');
let inputNovedades = 0;

const traerNovedaes = async (evento) => {
    evento && evento.preventDefault();
    const llevar = document.getElementById('ope_id').value;
    try {
        const url = `/sicomar/API/reporte/novedades/BusNovedades?id=${llevar}`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const novedades = await respuesta.json();
        console.log(novedades)


        if (novedades) {

            while (inputNovedades > 0) {
                quitarInputsNovedades()
            }


            novedades.forEach(novedad => {
                agregarInputsNovedades(null, novedad.nov_novedad, novedad.nov_fechahora.replace(' ', 'T'))
            });
        }
    } catch (error) {
        console.log(error);
    }
}


const agregarInputsNovedades = async (e, novedadbd = '', fechahorabd = '') => {
    inputNovedades++;
    // console.log(novedadbd,fechahorabd);
    const fragment = document.createDocumentFragment();
    const divRow = document.createElement('div');
    const divCol1 = document.createElement('div');
    const divCol2 = document.createElement('div');
    const inputFecha = document.createElement('input')
    const textarea = document.createElement('textarea')
    const label1 = document.createElement('label')
    const label2 = document.createElement('label')



    divRow.classList.add("row", "justify-content-center", "border", "rounded", "py-2", "mb-2");

    divCol1.classList.add("col-lg-4");
    divCol2.classList.add("col-lg-8");



    inputFecha.classList.add("form-control")
    inputFecha.type = 'datetime-local'
    inputFecha.name = `fechahora${inputNovedades}`
    inputFecha.id = `fechahora${inputNovedades}`
    label1.innerText = `Fecha y hora`
    label1.htmlFor = `Fechahora${inputNovedades}`

    inputFecha.value = fechahorabd

    textarea.classList.add("form-control")
    textarea.name = `novedad${inputNovedades}`
    textarea.id = `novedad${inputNovedades}`
    label2.innerText = `Novedad`
    label2.htmlFor = `novedad${inputNovedades}`



    textarea.value = novedadbd;


    divCol1.appendChild(label1)
    divCol2.appendChild(label2)



    divCol1.appendChild(inputFecha)
    divCol2.appendChild(textarea)

    divRow.appendChild(divCol1)
    divRow.appendChild(divCol2)

    fragment.appendChild(divRow)

    divNovedades.appendChild(fragment)


}


// const quitarInputsNovedades = e => {
//     e && e.preventDefault();

//     if(inputNovedades > 0){
//         console.log(inputNovedades)
//         inputNovedades--
//         divNovedades.removeChild(divNovedades.lastElementChild);
//     }
// }

const quitarInputsNovedades = e => {
    e && e.preventDefault();

    if (inputNovedades > 0) {
        inputNovedades--
        divNovedades.removeChild(divNovedades.lastElementChild);
    }
}


const guardarNovedades = async e => {
    e.preventDefault();
    const llevar = document.getElementById('ope_id').value;

    if (validarFormulario(formNovedades)) {

        let fechas = [];
        let inputFechas = formNovedades.querySelectorAll("[id^='fechahora']");
        inputFechas.forEach(input => {
            fechas = [...fechas, input.value]
        })
        let novedades = [];
        let inputNovedades = formNovedades.querySelectorAll("[id^='novedad']");
        inputNovedades.forEach(input => {
            novedades = [...novedades, input.value]
        })




        const url = '/sicomar/API/reporte/novedades/GuardarNov'
        const body = new FormData();
        const headers = new Headers();

        body.append('fechas', fechas)
        body.append('novedades', novedades)
        body.append('id_ope', llevar)
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
        let icon = "";
        switch (codigo) {
            case 1:
                icon = "success"

                traerNovedaes()
                break;

            case 0:
                icon = "error"

                break;
            case 4:
                icon = "error"
                console.log(detalle)
                // buscarTipo();

                break;

            default:
                break;
        }

        Toast.fire({
            icon: icon,
            title: mensaje,
        })




    } else {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los Campos, verifique sus datos'
        })
    }
}

traerNovedaes()
buttonAgregarNovedades.addEventListener('click', agregarInputsNovedades);
buttonQuitarNovedades.addEventListener('click', quitarInputsNovedades)
formNovedades.addEventListener('submit', guardarNovedades)
