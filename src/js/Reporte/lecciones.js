import { Dropdown } from "bootstrap";
import { validarFormulario, Toast } from "../funciones";
import Datatable from 'datatables.net-bs5';
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";




const formRecomendaciones = document.getElementById('formRecomendaciones');
const divRecomendaciones = document.getElementById('divRecomendaciones');
const buttonAgregarRecomendaciones = document.querySelector('#buttonAgregarRecomendaciones');
const buttonQuitarRecomendaciones = document.querySelector('#buttonQuitarRecomendaciones');
let inputRecomendaciones = 0;


const traer_lecciones = async (evento) => {
    evento && evento.preventDefault();
   
    try {
        const llevar = document.getElementById('ope_id').value;
        // console.log(llevar)
        divRecomendaciones.innerHTML= ""
        inputRecomendaciones = 0;
        const url = `/sicomar/API/reporte/lecciones/BusLecciones?id=${llevar}`
        const headers = new Headers();
        headers.append("X-requested-With", "fetch");

        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const recomendaciones = await respuesta.json();
        console.log(recomendaciones)
    

        if(recomendaciones){

            while (inputRecomendaciones > 0) {
                quitarInputsRecomendaciones()
            }


            recomendaciones.forEach(recomendacion => {
                agregarInputsRecomendaciones(null,recomendacion.rec_recomendacion
                    )
            });
        }
    } catch (error) {
        console.log(error);
    }
}


const agregarInputsRecomendaciones = async (e, recomendacionbd = '') => {
    inputRecomendaciones++;
    // console.log(novedadbd,fechahorabd);
    const fragment = document.createDocumentFragment();
    const divRow = document.createElement('div');
    const divCol1 = document.createElement('div');
    const textarea = document.createElement('textarea')
    const label1 = document.createElement('label')


    divRow.classList.add("row", "justify-content-center" ,"border" ,"rounded", "py-2" ,"mb-2");
   
    divCol1.classList.add("col-lg-12");





    textarea.classList.add("form-control")
    textarea.name = `recomendacion${inputRecomendaciones}`
    textarea.id = `recomendacion${inputRecomendaciones}`
    label1.innerText = `Lección ${inputRecomendaciones}`
    label1.htmlFor = `recomendacion${inputRecomendaciones}`



    textarea.value = recomendacionbd;


    divCol1.appendChild(label1)




    divCol1.appendChild(textarea)

    divRow.appendChild(divCol1)

    fragment.appendChild(divRow)

    divRecomendaciones.appendChild(fragment)


}
const quitarInputsRecomendaciones = e => {
    e && e.preventDefault();
    if(inputRecomendaciones > 0){
        inputRecomendaciones--
        divRecomendaciones.removeChild(divRecomendaciones.lastElementChild);
    }else{
        Toast.fire({
            icon: 'warning',
            title: 'Debe ingresar almenos 1 campo, verifique sus datos'
        })
    }
}

const guardarRecomendaciones = async e => {
    e.preventDefault();
    const llevar = document.getElementById('ope_id').value;

    if(validarFormulario(formRecomendaciones)){
      
        let recomendaciones = [];
        let inputRecomendaciones = formRecomendaciones.querySelectorAll("[id^='recomendacion']");
        inputRecomendaciones.forEach(input => {
            recomendaciones = [...recomendaciones, input.value ]
        })
        const url = '/sicomar/API/reporte/lecciones/GuardarLec'
        const body = new FormData();
        const headers = new Headers();

  
        body.append('recomendaciones', recomendaciones)
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

                traer_lecciones()
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

    }else {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los Campos, verifique sus datos'
        })
    }

}

traer_lecciones()
buttonAgregarRecomendaciones.addEventListener('click', agregarInputsRecomendaciones );
buttonQuitarRecomendaciones.addEventListener('click', quitarInputsRecomendaciones)
formRecomendaciones.addEventListener('submit', guardarRecomendaciones)
