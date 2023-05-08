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
const formulario = document.querySelector('#formDerrota')

// motores
const formMotores = document.getElementById('formMotores');
const divMotores = document.getElementById('divMotores');
let inputsMotores = 0;

const guardarTrabajo = (e) =>{
    e.preventDefault();

    if(validarFormulario(formMotores)){
        let horas = [], rpm = [], fallas = [], observaciones = [], ids = [];
        let operacion = formMotores.codigoOperacion.value
        
        let inputNits = document.querySelectorAll("[id^='horas']");
        inputNits.forEach(input => {
            horas = [...horas, input.value ]
        })

        let inputRPM = document.querySelectorAll("[id^='rpm']");
        inputRPM.forEach(input => {
            rpm = [...rpm, input.value ]
        })
        let inputfallas = document.querySelectorAll("[id^='fallas']");
        inputfallas.forEach(input => {
            fallas = [...fallas, input.value ]
        })
        let inputObservaciones = document.querySelectorAll("[id^='observaciones']");
        inputObservaciones.forEach(input => {
            observaciones = [...observaciones, input.value ]
        })
        let inputsIds = document.querySelectorAll("[id^='id']");
        inputsIds.forEach(input => {
            ids = [...ids, input.value ]
        })

        xajax_guardarTrabajoMotores(operacion, horas, rpm, fallas, observaciones,ids);
    }else{
        alertToast("warning", "Debe ingresar todos los campos")
    }
}

const agregarInputsMotores = (nombre, id, data = []) => {
    inputsMotores++;
    const fragment = document.createDocumentFragment();
    const divRow = document.createElement('div');
    const divCol1 = document.createElement('div');
    const divCol2 = document.createElement('div');
    const divCol3 = document.createElement('div');
    const divCol4 = document.createElement('div');
    const divCol5 = document.createElement('div');
    const parrafoMotor = document.createElement('p');
    const label1 = document.createElement('label')
    const label2 = document.createElement('label')
    const label3 = document.createElement('label')
    const label4 = document.createElement('label')
    const input1 = document.createElement('input')
    const input2 = document.createElement('input')
    const inputhidden = document.createElement('input')
    const textarea1 = document.createElement('textarea')
    const textarea2 = document.createElement('textarea')

    divRow.classList.add("row", "justify-content-center" ,"border" ,"rounded", "py-2" ,"mb-2");
    inputhidden.type = "hidden"
    inputhidden.value = id
    inputhidden.id = `id${inputsMotores}`

    // primer div
    divCol1.classList.add("col-lg-2", "d-flex", "align-items-end", "justify-content-center");
    parrafoMotor.classList.add("fw-bold");
    parrafoMotor.innerText = nombre
    divCol1.appendChild(parrafoMotor);
    // segundo div
    divCol2.classList.add("col-lg-2");
    label1.setAttribute('for',`horas${inputsMotores}`)
    label1.innerText = "Horas"
    input1.type = "number"
    input1.classList.add('form-control')
    input1.name = `horas${inputsMotores}`
    input1.id = `horas${inputsMotores}`
    input1.value = data.length > 0 ? data[0].TRA_HORAS : '' 

    divCol2.appendChild(label1)
    divCol2.appendChild(input1)

    // tercer div
    divCol3.classList.add("col-lg-2");
    label2.setAttribute('for',`rpm${inputsMotores}`)
    label2.innerText = "R.P.M"
    input2.type = "number"
    input2.classList.add('form-control')
    input2.name = `rpm${inputsMotores}`
    input2.id = `rpm${inputsMotores}`
    input2.value = data.length > 0 ? data[0].TRA_RPM : '' 

    divCol3.appendChild(label2)
    divCol3.appendChild(input2)

    // cuarto div
    divCol4.classList.add("col-lg-3");
    label3.setAttribute('for',`fallas${inputsMotores}`)
    label3.innerText = "Fallas"
    textarea1.classList.add('form-control')
    textarea1.name = `fallas${inputsMotores}`
    textarea1.id = `fallas${inputsMotores}`
    textarea1.style.height = "38px"
    textarea1.value = data.length > 0 ? data[0].TRA_FALLAS : '' 

    divCol4.appendChild(label3)
    divCol4.appendChild(textarea1)
    
    // quinto div
    divCol5.classList.add("col-lg-3");
    label4.setAttribute('for',`observaciones${inputsMotores}`)
    label4.innerText = "Observaciones"
    textarea2.classList.add('form-control')
    textarea2.name = `observaciones${inputsMotores}`
    textarea2.id = `observaciones${inputsMotores}`
    textarea2.style.height = "38px"
    textarea2.value = data.length > 0 ? data[0].TRA_OBSERVACION : '' 

    divCol5.appendChild(label4)
    divCol5.appendChild(textarea2)


    divRow.appendChild(divCol1)
    divRow.appendChild(divCol2)
    divRow.appendChild(divCol3)
    divRow.appendChild(divCol4)
    divRow.appendChild(divCol5)
    divRow.appendChild(inputhidden)

    fragment.appendChild(divRow)
    divMotores.appendChild(fragment)

}


const quitarInputsMotores = e => {
    e && e.preventDefault();
    if(inputsMotores > 0){
        inputsMotores--
        divMotores.removeChild(divMotores.lastElementChild);
    }else{
        alertToast('warning', 'Debe ingresar al menos un motor')
    }
}

const modalElement2 = document.getElementById('modalDetalle2')
modalElement2.addEventListener('show.bs.modal', async function  (event) {
    let button = event.relatedTarget;
    let id = button.getAttribute('data-unidad');
    let operacion = button.getAttribute('data-operacion');
    // console.log(operacion);
    let inputCodigo = document.getElementById('codigoOperacion');
    inputCodigo.value = operacion;
    const url = `motores.php?id=${id}`
    const config = { method : "GET" }
    const response = await fetch(url, config);
    const motores = await response.json()
   
    // console.log(motores);
    while(inputsMotores > 0){
        quitarInputsMotores();
    }

    motores.forEach(async motor => {
        const url2 = `trabajo.php?operacion=${operacion}&motor=${motor.MOT_ID}`
        const config2 = { method : "GET" }
        const response2 = await fetch(url2, config2);
        const trabajoGuardado = await response2.json()

        console.log(trabajoGuardado);
        agregarInputsMotores(motor.MOT_SERIE, motor.MOT_ID, trabajoGuardado != null && trabajoGuardado);

    })
})
 
formMotores.addEventListener('submit', guardarTrabajo)




formPuntos.addEventListener('submit', agregarPunto)
btnModificar.addEventListener('click', guardarDerrota)
formulario.addEventListener('submit', guardarDerrota)

