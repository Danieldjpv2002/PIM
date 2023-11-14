const onLoadAdjunto = async (files) => {
    const container = $('#container-ticket-lista-adjuntos')

    files.forEach(async file => {
        const base64 = await blobToBase64(file)

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
        button.attr('data-b64', base64)

        button.on('click', () => button.remove())

        tippy(button.get(0), { arrow: true })
        container.append(button)
    });
}

const blobToBase64 = async (blob) => {
    return new Promise((resolve, _) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.readAsDataURL(blob);
    });
}

const onNewTicketSubmit = async (e) => {
    e.preventDefault()
    try {
        const ticket = {}
        ticket._tipo = $('#cbo-ticket-tipo').val()
        ticket.asunto = $('#txt-ticket-asunto').val()
        ticket.descripcion = JSON.stringify(ticketEditor.getContents())

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

        const { id: ticketId } = ticketResult

        const attachments = [...$('#container-ticket-lista-adjuntos button')]
        attachments.forEach(button => {

        })

    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
        })
    }
}