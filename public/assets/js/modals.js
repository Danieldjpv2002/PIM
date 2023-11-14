var ticketEditor = null

const loadTipos = async () => {
    try {
        const { status, result } = await Fetch('./api/tipos')
        const { data } = result

        const cbo = $('#cbo-ticket-tipo')
        cbo.html('<option value>- Seleccione un tipo -</option>')

        const categorias = [...new Set(data.map(({ categoria }) => categoria.categoria))]
        categorias.forEach(categoria => {
            const optGroup = $('<optgroup>')
            optGroup.attr('label', categoria)
            cbo.append(optGroup)
        })

        data.forEach(({ id, tipo, descripcion, estado, categoria }) => {
            const opt = $('<option>')
            opt.val(id)
            opt.text(tipo)
            opt.attr('title', descripcion || '')
            if (estado != 1) {
                opt.prop('disabled', true)
            }
            tippy(opt.get(0), { arrow: true })
            cbo.find(`optgroup[label="${categoria.categoria}"]`).append(opt)
        })

        cbo.select2({
            dropdownParent: '#modal-newTicket'
        })
    } catch (e) {
        console.error(e)
    }
}

const loadEditor = async () => {
    ticketEditor = new Quill("#txt-ticket-descripcion", { theme: "bubble" })
}

const loadPanelAdjuntos = async () => {
    Clipboard.paste('#container-ticket-adjunto', (files) => onLoadAdjunto(files), {
        'pasting': 'true'
    })
}

(async () => {
    tippy(document.getElementById('btn-newTicket'), { arrow: true })
    loadTipos()
    loadEditor()
    loadPanelAdjuntos()
})()
