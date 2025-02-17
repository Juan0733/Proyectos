import {ent_no_registrada, modal_vehicular, modal_peatonal, insertar_tablas} from '../modales/modales.js'


document.addEventListener('DOMContentLoaded', ()=>{
    
    let btn_peatonal = document.getElementById('btn_peatonal');
    let btn_vehicular = document.getElementById('btn_vehicular');

    insertar_tablas('salida');

    btn_peatonal.addEventListener('click', ()=>{
        modal_peatonal('salida peatonal');
        
        // alert_persona_existente()
        // alert_vehiculo_existente()
        // alert_propetario()
        // ent_no_registrada()
    })

    btn_vehicular.addEventListener('click', ()=>{
        modal_vehicular('salida vehicular');
    })

})