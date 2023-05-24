import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";

const formComunicacion = document.getElementById('formComunicacion');
const divComunicacion = document.getElementById('divComunicacion');
const buttonAgregarComunicacion = document.querySelector('#buttonAgregarComunicacion');
const buttonQuitarComunicacion = document.querySelector('#buttonQuitarComunicacion');
let inputComunicacion = 0;


const traerComunicaciones = async (evento) => {
    evento && evento.preventDefault();
    const llevar = document.getElementById('ope_id').value;
    //console.log(llevar)
    try {
        const url = `/sicomar/API/reporte/comunicaciones/BusComuni?id=${llevar}`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const comunicaciones = await respuesta.json();
       // console.log(comunicaciones)
      
       while(inputComunicacion > 0){
        quitarInputsComunicacion();
    }
    
  
        if (comunicaciones) {
        
            comunicaciones.forEach(comunicacion => {
                agregarInputsComunicaciones(null, comunicacion.com_medio, comunicacion.com_receptor, comunicacion.com_calidad, comunicacion.com_observacion)
            });

        }
    } catch (error) {
        console.log(error);
    }
}



const agregarInputsComunicaciones = async (e, mediobd = '', receptorbd = '', calidadbd = '', observacionbd = '') => {
    inputComunicacion++;
   // console.log(mediobd, receptorbd, calidadbd, observacionbd);

    const fragment = document.createDocumentFragment();
    const divRow = document.createElement('div');
    const divCol1 = document.createElement('div');
    const divCol2 = document.createElement('div');
    const divCol3 = document.createElement('div');
    const divCol4 = document.createElement('div');
    const select1 = document.createElement('select')
    const select2 = document.createElement('select')
    const select3 = document.createElement('select')
    const textarea = document.createElement('textarea')
    const label1 = document.createElement('label')
    const label2 = document.createElement('label')
    const label3 = document.createElement('label')
    const label4 = document.createElement('label')
    const option1 = document.createElement('option')
    const option2 = document.createElement('option')
    const option3 = document.createElement('option')
    option1.value = ""
    option1.innerText = "SELECCIONE..."
    option2.value = ""
    option2.innerText = "SELECCIONE..."
    option3.value = ""
    option3.innerText = "SELECCIONE..."
    select1.appendChild(option1)
    select2.appendChild(option2)
    select3.appendChild(option3)


    divRow.classList.add("row", "justify-content-center", "border", "rounded", "py-2", "mb-2");

    divCol1.classList.add("col-lg-3");
    divCol2.classList.add("col-lg-3");
    divCol3.classList.add("col-lg-3");
    divCol4.classList.add("col-lg-3");


    select1.classList.add("form-control")
    select1.name = `medio${inputComunicacion}`
    select1.id = `medio${inputComunicacion}`
    label1.innerText = `Medio ${inputComunicacion}`
    label1.htmlFor = `medio${inputComunicacion}`

    select2.classList.add("form-control")
    select2.name = `receptor${inputComunicacion}`
    select2.id = `receptor${inputComunicacion}`
    label2.innerText = `Receptor ${inputComunicacion}`
    label2.htmlFor = `receptor${inputComunicacion}`

    select3.classList.add("form-control")
    select3.name = `calidad${inputComunicacion}`
    select3.id = `calidad${inputComunicacion}`
    label3.innerText = `Calidad ${inputComunicacion}`
    label3.htmlFor = `calidad${inputComunicacion}`

    textarea.classList.add("form-control")
    textarea.name = `observacion${inputComunicacion}`
    textarea.id = `observacion${inputComunicacion}`
    label4.innerText = `Observacion ${inputComunicacion}`
    label4.htmlFor = `observacion${inputComunicacion}`



//medios
const url = `/sicomar/API/reporte/comunicaciones/BusMedios`
const headers = new Headers();
headers.append("X-requested-With", "fetch");

const config = {
    method: 'GET',
}

const respuesta = await fetch(url, config);
const medios = await respuesta.json();
//console.log(medios)

    medios.forEach(medio => {
        const option = document.createElement('option')
        option.value = medio.medio_id
        option.innerText = `${medio.medio_desc}`
        select1.appendChild(option)
    })
   // console.log(mediobd);
    select1.value = mediobd;
//receptores
    const url1 = `/sicomar/API/reporte/comunicaciones/BusReceptores`
    const headers1 = new Headers();
    headers1.append("X-requested-With", "fetch");
    
    const config1 = {
        method: 'GET',
    }
    
    const respuesta1 = await fetch(url1, config1);
    const receptores = await respuesta1.json();
    //console.log(receptores)

    receptores.forEach(receptor => {
        const option = document.createElement('option')
        option.value = receptor.rec_id
        option.innerText = `${receptor.rec_desc}`
        select2.appendChild(option)
    })

    select2.value = receptorbd;

    for (let index = 1; index <= 5; index++) {
        const option = document.createElement('option')
        option.value = index
        option.innerText = `QRK ${index}`
        select3.appendChild(option)

    }
    select3.value = calidadbd;

    textarea.value = observacionbd;


    divCol1.appendChild(label1)
    divCol2.appendChild(label2)
    divCol3.appendChild(label3)
    divCol4.appendChild(label4)


    divCol1.appendChild(select1)
    divCol2.appendChild(select2)
    divCol3.appendChild(select3)
    divCol4.appendChild(textarea)

    divRow.appendChild(divCol1)
    divRow.appendChild(divCol2)
    divRow.appendChild(divCol3)
    divRow.appendChild(divCol4)
    fragment.appendChild(divRow)

    divComunicacion.appendChild(fragment)


}



const quitarInputsComunicacion = e => {
    e && e.preventDefault();
    if (inputComunicacion > 0) {
        inputComunicacion--
        divComunicacion.removeChild(divComunicacion.lastElementChild);
    } 
}

const guardarComunicacion  = async e => {
    e.preventDefault();

    const llevar = document.getElementById('ope_id').value;
    if (validarFormulario(formComunicacion)) {

        let medios = [];
        let inputMedios = formComunicacion.querySelectorAll("[id^='medio']");
        inputMedios.forEach(input => {
            medios = [...medios, input.value]
        })
        let receptores = [];
        let inputReceptores = formComunicacion.querySelectorAll("[id^='receptor']");
        inputReceptores.forEach(input => {
            receptores = [...receptores, input.value]
        })
        let calidades = [];
        let inputCalidades = formComunicacion.querySelectorAll("[id^='calidad']");
        inputCalidades.forEach(input => {
            calidades = [...calidades, input.value]
        })
        let observaciones = [];
        let inputObservaciones = formComunicacion.querySelectorAll("[id^='observacion']");
        inputObservaciones.forEach(input => {
            observaciones = [...observaciones, input.value]
        })
 
//inicia guardado
        const url = '/sicomar/API/reporte/comunicaciones/GuardarCom'
        const body = new FormData();
        const headers = new Headers();

        body.append('medios', medios)
        body.append('receptores', receptores)
        body.append('calidades', calidades)
        body.append('observaciones', observaciones)
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
           
                traerComunicaciones()
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

traerComunicaciones()
buttonAgregarComunicacion.addEventListener('click', e => agregarInputsComunicaciones(e));
buttonQuitarComunicacion.addEventListener('click', quitarInputsComunicacion)
formComunicacion.addEventListener('submit', guardarComunicacion)
