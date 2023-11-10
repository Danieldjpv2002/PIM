// Cuando presionamos el boton guardar del modal
const onModalEstadosSubmit = async (e) => {
    e.preventDefault()
    try {
        const request = {
            id: txt_id.value || undefined,
            estado: txt_estado.value,
            descripcion: txt_descripcion.value
        }
        const { status, result } = await Fetch('./api/estados', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al guardar el estado')
        $('#modal-estados').modal('hide')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El estado se ha guardado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
        })
    }
}

// Cuando presionamos el boton editar de la fila
const onButtonUpdateClicked = async (data) => {
    const modal = $('#modal-estados')
    if (data?.id) modal.find('.modal-title').text('Editar estado')
    else modal.find('.modal-title').text('Nuevo estado')

    txt_id.value = data?.id ?? ''
    txt_estado.value = data?.estado ?? ''
    txt_descripcion.value = data?.descripcion ?? ''

    modal.modal('show')
}

// Cuando presionamos el boton eliminar de la fila
const onButtonDeleteClicked = async ({ id }) => {
    try {
        const { status, result } = await Fetch(`./api/estados/${id}`, {
            method: 'DELETE'
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al eliminar el estado')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El estado se ha eliminado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const onButtonStatusClicked = async ({ id, status: statusCategory }) => {
    try {
        const { status, result } = await Fetch(`./api/categories/${id}`, {
            method: 'PATCH',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: statusCategory
            })
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al cambiar el estado de la categoria')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La categoria ha cambiado de estado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
        })
    }
}