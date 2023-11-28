// Cuando presionamos el boton editar de la fila
const onButtonDetailClicked = async (data) => {
    const modal = $('#modal-tickets')
    txt_informador.textContent = `${data.informador.nombres} ${data.informador.apellidos}`
    txt_correo.textContent = data.informador.correo
    txt_telefono.textContent = data.informador.telefono
    txt_anydesk.textContent = data.informador.anydesk
    txt_ip.textContent = data.informador.ip
    txt_categoria.textContent = data.tipo.categoria.categoria
    txt_tipo.textContent = data.tipo.tipo
    txt_estado.innerHTML = data?.estado?.estado ?? '<i class="text-muted">- Sin estado -</i>'
    contenedor_descripcion_solucion.innerHTML = data.solucion

    txt_resumen.textContent = data.asunto
    contenedor_descripcion.innerHTML = data.descripcion
    contenedor_adjuntos.innerHTML = null
    getAdjuntos(data.id)
    modal.modal('show')
}