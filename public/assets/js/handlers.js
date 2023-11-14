$(document).on('change', '#file-ticket-adjunto', (e) => {
    const input = e.target
    const files = [...input.files] ?? []
    input.value = null
    onLoadAdjunto(files)
})

$(document).on('change', '#cbo-ticket-tipo', (e) => {
    const cbo = e.target
    if (cbo.value) {
        $('#modal-description').show(250)
    } else {
        $('#modal-description').hide(250)
    }
})

$(document).on('submit', '#modal-newTicket', (e) => onNewTicketSubmit(e))