Clipboard.paste('#container-ticket-adjunto-solucion', (files) => onLoadAdjuntoSolucion(files), {
    'pasting': 'true'
})

$(document).on('change', '#file-ticket-adjunto-solucion', (e) => {
    const input = e.target
    const files = [...input.files] ?? []
    input.value = null
    onLoadAdjuntoSolucion(files)
})

$(document).on('submit', '#modal-solucion', (e) => onModalSolucionSubmit(e))