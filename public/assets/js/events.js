const onLoadAdjunto = async (files) => {
    const container = $('#container-ticket-lista-adjuntos')

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

const onNewTicketSubmit = async (e) => {
    e.preventDefault()
    try {
        const ticket = {}
        ticket._tipo = $('#cbo-ticket-tipo').val()
        ticket.asunto = $('#txt-ticket-asunto').val()
        ticket.descripcion = ticketEditor.root.innerHTML
        const attachments = [...$('#container-ticket-lista-adjuntos button')]

        if (ticketEditor.getLength() == 1) throw new Error('Es necesario agregar una descripcion')
        if (attachments.length == 0) throw new Error('Es necesario agregar al menos un adjunto')

        const { status: ticketStatus, result: ticketResult } = await Fetch('./api/tickets', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ticket)
        })
        if (!ticketStatus) throw new Error(ticketResult?.message || 'Ocurrio un error al crear el ticket')

        Notify.add({
            title: 'Operacion correcta',
            body: 'El ticket se ha creado correctamente'
        })

        const { id: ticketId } = ticketResult.data

        attachments.forEach(async button => {
            try {
                const url = button.getAttribute('data-url')
                const filename = button.getAttribute('data-filename')
                const blob = await File.fromURL(url)
                const newBlob = new File([blob], filename, {
                    type: blob.type
                })
                const formData = new FormData()
                formData.append('ticket', ticketId)
                formData.append('blob', newBlob)
                const { status, result } = await fetch('./api/adjuntos', {
                    method: 'POST',
                    headers: {
                        'Auth-User': Cookies.get('Auth-User'),
                        'Auth-Token': Cookies.get('Auth-Token'),
                    },
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

        $('#modal-newTicket').modal('hide')
        onTicketModalReset()

    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const onTicketModalReset = () => {
    $('#cbo-ticket-tipo').val(null).trigger('change')
    $('#txt-ticket-asunto').val(null)
    ticketEditor.setContents(null)
    $('#container-ticket-lista-adjuntos').empty()
}