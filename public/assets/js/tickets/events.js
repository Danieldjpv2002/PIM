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

const onLoadAdjuntoSolucion = async (files) => {
    const container = $('#container-ticket-lista-adjuntos-solucion')

    files.forEach(async file => {
        const url = URL.createObjectURL(file)

        let icon = 'mdi-file-outline'
        if (file.type.startsWith('application/vnd.')) icon = 'mdi-file-excel-outline'
        if (file.type.startsWith('application/vnd.') && file.type.includes('word')) icon = 'mdi-file-word-outline'
        if (file.type.startsWith('image/')) icon = 'mdi-file-image-outline'
        if (file.type.startsWith('application/json')) icon = 'mdi-file-code-outline'
        if (file.type.startsWith('application/pdf')) icon = 'mdi-file-pdf-outline'

        const button = $('<button type="button" class="btn btn-xs btn-dark waves-effect waves-light mt-1 me-1">')
        button.append(`<span class="btn-label me-0"><i class="mdi ${icon}"></i></span>`)
        button.append(file.name)
        button.attr('title', 'Click para quitar')
        button.attr('data-url', url)
        button.attr('data-filename', file.name)

        button.on('click', () => button.remove())

        tippy(button.get(0), { arrow: true })
        container.append(button)
    });
}

const onModalSolucionSubmit = async (e) => {
    e.preventDefault()
    try {
        const ticket = {}
        ticket.id = $('#txt-ticket-id').val()
        ticket.solucion = $('#txt-solucion').val()
        const attachments = [...$('#container-ticket-lista-adjuntos-solucion button')]

        const { status: ticketStatus, result: ticketResult } = await Fetch('./api/tickets', {
            method: 'PATCH',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ticket)
        })
        if (!ticketStatus) throw new Error(ticketResult?.message || 'Ocurrio un error al actualizar el ticket')

        Notify.add({
            title: 'Operacion correcta',
            body: 'El ticket se ha actualizado correctamente'
        })

        attachments.forEach(async button => {
            try {
                const url = button.getAttribute('data-url')
                const filename = button.getAttribute('data-filename')
                const blob = await File.fromURL(url)
                const newBlob = new File([blob], filename, {
                    type: blob.type
                })
                const formData = new FormData()
                formData.append('ticket', ticket.id)
                formData.append('tipo', 'solucion')
                formData.append('blob', newBlob)
                const { status, result } = await fetch('./api/adjuntos', {
                    method: 'POST',
                    body: formData
                })
                if (!status) throw new Error(result?.message || 'Error inesperado al cargar el adjunto')
                Notify.add({
                    title: 'Operacion correcta',
                    body: 'Se ha cargado el adjunto correctamente'
                })
            } catch (error) {
                Notify.add({
                    title: 'Error',
                    body: error.message,
                    type: 'danger'
                })
            }

        })

        $('#modal-solucion').modal('hide')

    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const onButtonSolutionClicked = (data) => {
    $('#txt-ticket-id').val(data.id)
    $('#txt-solucion').val(null)
    $('#container-ticket-lista-adjuntos-solucion').empty()
    $('#modal-solucion').modal('show')
}