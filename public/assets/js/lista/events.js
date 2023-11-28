// Cuando presionamos el boton editar de la fila
const onButtonDetailClicked = async (data) => {
    const modal = $('#modal-tickets')
    console.log(data);
    txt_informador.textContent = `${data.informador.nombres} ${data.informador.apellidos}`
    txt_correo.textContent = data.informador.correo
    txt_telefono.textContent = data.informador.telefono
    txt_anydesk.textContent = data.informador.anydesk
    txt_ip.textContent = data.informador.ip
    txt_categoria.textContent = data.tipo.categoria.categoria
    txt_tipo.textContent = data.tipo.tipo
    txt_resumen.textContent = data.asunto
    contenedor_descripcion.innerHTML = data.descripcion
    contenedor_adjuntos.innerHTML = null
    getAdjuntos(data.id)
    modal.modal('show')
}

const onTicketEstadoClicked = async (estado, id) => {
    try {
        const { status, result } = await Fetch('./api/tickets/estado', {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id, estado
            })
        })

        if (!status) throw new Error(result?.message || 'Ocurrio un error al actualizar el estado')

        Notify.add({
            title: result.message,
            body: 'El estado del ticket se actualizo correctamente'
        })

        dataGrid.refresh()
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const onTicketResponsableClicked = async (responsable, id) => {
    try {
        const { status, result } = await Fetch('./api/tickets/responsable', {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id, responsable
            })
        })

        if (!status) throw new Error(result?.message || 'Ocurrio un error al actualizar el responsable')

        Notify.add({
            title: result.message,
            body: 'El responsable del ticket se actualizo correctamente'
        })

        dataGrid.refresh()
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}