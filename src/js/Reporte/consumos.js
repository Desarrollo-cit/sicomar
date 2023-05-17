import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";


//const formMotores = document.getElementById('formMotores');
const formConsumos = document.getElementById('formConsumos');
const divConsumos = document.getElementById('divConsumos');
const buttonAgregarConsumos = document.querySelector('#buttonAgregarConsumos');
const buttonQuitarConsumos = document.querySelector('#buttonQuitarConsumos');
let inputConsumos = 0;

const traer_consumo = async (evento) => {
    evento && evento.preventDefault();
    const llevar = document.getElementById('ope_id').value;


    try {
        const url = `/sicomar/API/reporte/consumos/BusConsumos?id=${llevar}`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const consumos = await respuesta.json();
        console.log(consumos)

    while (inputConsumos > 0) {
        quitarInputsConsumos();
    }
    if(consumos){
        consumos.forEach(consumo => {
            agregarInputsConsumos(null, consumo.con_insumo, consumo.con_cantidad)
        });
    }
} catch (error) {
    console.log(error);
}
}


const agregarInputsConsumos = async (e, insumo = '', cantidad = '' ) => {
    inputConsumos++;
    const fragment = document.createDocumentFragment();
    const divRow = document.createElement('div');
    const divCol1 = document.createElement('div');
    const divCol2 = document.createElement('div');
    const input = document.createElement('input')
    const select = document.createElement('select')
    const label1 = document.createElement('label')
    const label2 = document.createElement('label')
    const option = document.createElement('option')
    option.value = ""
    option.innerText = "SELECCIONE..."
    select.appendChild(option)


    divRow.classList.add("row", "justify-content-center" ,"border" ,"rounded", "py-2" ,"mb-2");
   
    divCol1.classList.add("col-lg-6");
    divCol2.classList.add("col-lg-6");
    input.classList.add("form-control")
    input.name = `cantidad${inputConsumos}`
    input.id = `cantidad${inputConsumos}`
    select.classList.add("form-control")
    select.name = `insumo${inputConsumos}`
    select.id = `insumo${inputConsumos}`
    label1.innerText = `Insumo ${inputConsumos}`
    label1.htmlFor = `insumo${inputConsumos}`
    label2.innerText = `Cantidad ${inputConsumos}`
    label2.htmlFor = `cantidad${inputConsumos}`


    const url = `/sicomar/API/reporte/consumos/BusInsumos`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const insumos = await respuesta.json();
        console.log(insumos)

    

    insumos.forEach(insumo => {
        const option = document.createElement('option')
        option.value = insumo.insumo_id
        option.innerText = `${insumo.insumo_desc} (${insumo.uni_desc})`
        select.appendChild(option)
    })

    select.value = insumo;
    input.value = cantidad;


    divCol1.appendChild(label1)
    divCol2.appendChild(label2)
    divCol1.appendChild(select)
    divCol2.appendChild(input)

    divRow.appendChild(divCol1)
    divRow.appendChild(divCol2)
    fragment.appendChild(divRow)

    divConsumos.appendChild(fragment)


}

const quitarInputsConsumos = e => {
    e && e.preventDefault();
    if(inputConsumos > 0){
        inputConsumos--
        divConsumos.removeChild(divConsumos.lastElementChild);
    }else{
        alertToast('warning', 'Debe ingresar al menos un insumo')
    }
}

const guardarConsumos = (event) => {
    event.preventDefault();

    if(validarFormulario(formConsumos, ['codigoOperacion2']) && inputConsumos > 0){

        let insumos = [], cantidades = [];
        let operacion = formConsumos.codigoOperacion2.value


        let inputInsumos = document.querySelectorAll("[id^='insumo']");
        inputInsumos.forEach(input => {
            insumos = [...insumos, input.value ]
        })
        let inputCantidades = document.querySelectorAll("[id^='cantidad']");
        inputCantidades.forEach(input => {
            cantidades = [...cantidades, input.value ]
        })

        xajax_guardarConsumos(insumos,cantidades, operacion);

    }else{
        alertToast("warning", "Debe ingresar todos los campos")
    }
}

traer_consumo()
buttonAgregarConsumos.addEventListener('click', agregarInputsConsumos );
buttonQuitarConsumos.addEventListener('click', quitarInputsConsumos );
formConsumos.addEventListener('submit', guardarConsumos )




