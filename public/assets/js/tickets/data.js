const getAdjuntos = async (ticket) => {
    try {
        const { status, result } = await Fetch(`./api/adjuntos/ticket/${ticket}`)
        if (!status) new Error(result?.message || 'No se pudo obtener los adjuntos')

        const { data } = result
        const container_problema = $(contenedor_adjuntos)
        container_problema.empty()

        const container_solucion = $(contenedor_adjuntos_solucion)
        container_solucion.empty()

        data.forEach(({ id, nombre, tipo, mimetipo }) => {
            const a = $('<a>')
            a.attr('href', `./api/adjuntos/${id}`)
            a.attr('target', '_blank')
            a.attr('title', nombre)

            const img = $('<img>')
            img.attr('alt', nombre)
            img.attr('type', mimetipo)
            img.attr('src', `./api/adjuntos/${id}`)
            a.html(img)

            tippy(a.get(0), { arrow: true })

            if (tipo == 'solucion') {
                container_solucion.append(a)
            } else {
                container_problema.append(a)
            }
        });

    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const loadEstados = (estados) => {
    const cbo = $('#cbo-estado')
    cbo.empty()
    estados.forEach(({ id, estado }) => {
        const option = $('<option>')
        option.val(id)
        option.text(estado)
        cbo.append(option)
    })

    cbo.select2({
        dropdownParent: '#modal-solucion'
    })
}