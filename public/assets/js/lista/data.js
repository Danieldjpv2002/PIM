const getAdjuntos = async (ticket) => {
    try {
        const { status, result } = await Fetch(`./api/adjuntos/ticket/${ticket}`)
        if (!status) new Error(result?.message || 'No se pudo obtener los adjuntos')

        const { data } = result
        const container = $(contenedor_adjuntos)
        container.empty()
        data.forEach(({ id, nombre, mimetipo }) => {
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

            container.append(a)
        });

    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}