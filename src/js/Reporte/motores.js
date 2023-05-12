import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";


const formMotores = document.getElementById('formMotores');
const divMotores = document.getElementById('divMotores');
const formId = document.getElementById('formId')
let inputsMotores = 0;


const traerMotores = async (evento) => {
    evento && evento.preventDefault();

    const llevar = document.getElementById('id').value;

    try {
        const url = `/sicomar/API/reporte/motores/BusMotor?id=${llevar}`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
       // console.log(data);
        
        while(inputsMotores > 0){
            quitarInputsMotores();
        }

        if (data){
     
            data.forEach(async motor => {
               // console.log(motor.mot_id)
          
                const url = `/sicomar/API/reporte/motores/BusTrabajo?id=${llevar}&motor=${motor.mot_id}`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const trabajo = await respuesta.json();
       // console.log(trabajo);
          
                //console.log(trabajoGuardado);
                agregarInputsMotores(motor.mot_serie, motor.mot_id, trabajo != null && trabajo);
        
            })    
      
        }
    } catch (error) {
        console.log(error);
    }
}



const guardarTrabajos= async e => {
    e.preventDefault();

    try {
        
        const llevar = document.getElementById('id').value;
        if (validarFormulario(formMotores)) {
            let horas = [], rpm = [], fallas = [], observaciones = [], ids = [];
        
    
            let inputNits = document.querySelectorAll("[id^='horas']");
            inputNits.forEach(input => {
                horas = [...horas, input.value]
            })
    
            let inputRPM = document.querySelectorAll("[id^='rpm']");
            inputRPM.forEach(input => {
                rpm = [...rpm, input.value]
            })
            let inputfallas = document.querySelectorAll("[id^='fallas']");
            inputfallas.forEach(input => {
                fallas = [...fallas, input.value]
            })
            let inputObservaciones = document.querySelectorAll("[id^='observaciones']");
            inputObservaciones.forEach(input => {
                observaciones = [...observaciones, input.value]
            })
            let inputsIds = document.querySelectorAll("[id^='id']");
            inputsIds.forEach(input => {
                ids = [...ids, input.value]
            })

            const url = '/sicomar/API/reporte/motores/GuardarTrabajo'
            const body = new FormData();
            const headers = new Headers();
    
            body.append('horas', horas)
            body.append('rpm', rpm)
            body.append('fallas', fallas)
            body.append('observaciones', observaciones)
            body.append('ids', ids)            
            headers.append("X-Requested-With", "fetch");
    
            const config = {
                method: 'POST',
                headers,
                body
            }
    
            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            console.log(data);
    
    
        } else {
            alertToast("warning", "Debe ingresar todos los campos")
        }

    } catch (error) {
        console.error(error);
    }    

}

const agregarInputsMotores = (nombre, id, data = []) => {
   // console.log(data);

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

    divRow.classList.add("row", "justify-content-center", "border", "rounded", "py-2", "mb-2");
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
    label1.setAttribute('for', `horas${inputsMotores}`)
    label1.innerText = "Horas"
    input1.type = "number"
    input1.classList.add('form-control')
    input1.name = `horas${inputsMotores}`
    input1.id = `horas${inputsMotores}`
    input1.value = data.length > 0 ? data[0].tra_horas : ''

    divCol2.appendChild(label1)
    divCol2.appendChild(input1)

    // tercer div
    divCol3.classList.add("col-lg-2");
    label2.setAttribute('for', `rpm${inputsMotores}`)
    label2.innerText = "R.P.M"
    input2.type = "number"
    input2.classList.add('form-control')
    input2.name = `rpm${inputsMotores}`
    input2.id = `rpm${inputsMotores}`
    input2.value = data.length > 0 ? data[0].tra_rpm : ''

    divCol3.appendChild(label2)
    divCol3.appendChild(input2)

    // cuarto div
    divCol4.classList.add("col-lg-3");
    label3.setAttribute('for', `fallas${inputsMotores}`)
    label3.innerText = "Fallas"
    textarea1.classList.add('form-control')
    textarea1.name = `fallas${inputsMotores}`
    textarea1.id = `fallas${inputsMotores}`
    textarea1.style.height = "38px"
    textarea1.value = data.length > 0 ? data[0].tra_fallas : ''

    divCol4.appendChild(label3)
    divCol4.appendChild(textarea1)

    // quinto div
    divCol5.classList.add("col-lg-3");
    label4.setAttribute('for', `observaciones${inputsMotores}`)
    label4.innerText = "Observaciones"
    textarea2.classList.add('form-control')
    textarea2.name = `observaciones${inputsMotores}`
    textarea2.id = `observaciones${inputsMotores}`
    textarea2.style.height = "38px"
    textarea2.value = data.length > 0 ? data[0].tra_observacion : ''

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
    if (inputsMotores > 0) {
        inputsMotores--
        divMotores.removeChild(divMotores.lastElementChild);
    } else {
        alertToast('warning', 'Debe ingresar al menos un motor')
    }
}


traerMotores()

formMotores.addEventListener('submit', guardarTrabajos);




