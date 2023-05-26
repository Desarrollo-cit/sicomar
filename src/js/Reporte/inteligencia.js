import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";




const formInformacion = document.getElementById('formInformacion');
const divInformacion = document.getElementById('divInformacion');
const buttonAgregarInformacion = document.querySelector('#buttonAgregarInformacion');
const buttonQuitarInformacion = document.querySelector('#buttonQuitarInformacion');
let inputInformacion = 0;


const traer_Inteligencia = async (evento) => {
    evento && evento.preventDefault();
   
    try {
      
    divInformacion.innerHTML= ""
    inputInformacion = 0;
    const llevar = document.getElementById('ope_id').value;
  
    const url = `/sicomar/API/reporte/inteligencia/BusInteligencia?id=${llevar}`
    const headers = new Headers();
    headers.append("X-requested-With", "fetch");

    const config = {
        method: 'GET',
    }

    const respuesta = await fetch(url, config);
    const informacion = await respuesta.json();
    //console.log(informacion)


    if(informacion){
        while (inputInformacion > 0) {
            quitarInputsInformacion()
        }
        informacion.forEach( r => {
            agregarInputsInformacion(null, r.info_descripcion)
        });
    }
}
 catch (error) {
    console.log(error);
}
}


const agregarInputsInformacion = async (e, informacionbd = '') => {
    inputInformacion++;

    // console.log(novedadbd,fechahorabd);
    const fragment = document.createDocumentFragment();
    const divRow = document.createElement('div');
    const divCol1 = document.createElement('div');
    const textarea = document.createElement('textarea')
    const label1 = document.createElement('label')


    divRow.classList.add("row", "justify-content-center" ,"border" ,"rounded", "py-2" ,"mb-2");
   
    divCol1.classList.add("col-lg-12");





    textarea.classList.add("form-control")
    textarea.name = `informacion${inputInformacion}`
    textarea.id = `informacion${inputInformacion}`
    label1.innerText = `Información ${inputInformacion}`
    label1.htmlFor = `informacion${inputInformacion}`



    textarea.value = informacionbd;


    divCol1.appendChild(label1)




    divCol1.appendChild(textarea)

    divRow.appendChild(divCol1)

    fragment.appendChild(divRow)

    divInformacion.appendChild(fragment)


}
const quitarInputsInformacion = e => {
    e && e.preventDefault();
    if(inputInformacion > 0){
        inputInformacion--
        divInformacion.removeChild(divInformacion.lastElementChild);
    }else{
        alertToast('warning', 'No puede eliminar más')
    }
}

const guardarInformacion = async e => {
    e.preventDefault();
    const llevar = document.getElementById('ope_id').value;

    if(validarFormulario(formInformacion)){
      
        let informacion = [];
        let inputInformacion = formInformacion.querySelectorAll("[id^='informacion']");
        inputInformacion.forEach(input => {
            informacion = [...informacion, input.value ]
        })



        const url = '/sicomar/API/reporte/inteligencia/GuardarInf'
        const body = new FormData();
        const headers = new Headers();

        body.append('informacion', informacion)
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

                traer_Inteligencia()
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



buttonAgregarInformacion.addEventListener('click', agregarInputsInformacion );
buttonQuitarInformacion.addEventListener('click', quitarInputsInformacion)
formInformacion.addEventListener('submit', guardarInformacion)

traer_Inteligencia()