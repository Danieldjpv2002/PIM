const onModalTemplatesSubmit = async (e) => {
    e.preventDefault()
    try {
        const request = {
            id: txt_id.value || undefined,
            category: cbo_category.value,
            template: txt_template.value,
            description: txt_description.value
        }
        const { status, result } = await Fetch('./api/templates', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al guardar la plantilla')
        $('#modal-templates').modal('hide')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La plantilla se ha guardado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
        })
    }
}

const onButtonUpdateClicked = async (data) => {
    const modal = $('#modal-templates')
    if (data?.id) modal.find('.modal-title').text('Editar plantilla')
    else modal.find('.modal-title').text('Nueva plantilla')

    txt_id.value = data?.id ?? ''
    cbo_category.value = data?.category?.id ?? ''
    $(cbo_category).trigger('change')
    txt_template.value = data?.template ?? ''
    txt_description.value = data?.description ?? ''

    modal.modal('show')
}

const onButtonDeleteClicked = async ({ id }) => {
    try {
        const { status, result } = await Fetch(`./api/templates/${id}`, {
            method: 'DELETE'
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al borrar la plantilla')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La plantilla se ha eliminado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
        })
    }
}

const onButtonStatusClicked = async ({ id, status: statusTemplate }) => {
    try {
        const { status, result } = await Fetch(`./api/templates/${id}`, {
            method: 'PATCH',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: statusTemplate
            })
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al cambiar el estado de la plantilla')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La plantilla ha cambiado de estado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
        })
    }
}