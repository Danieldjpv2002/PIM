// Cuando presionamos el boton guardar del modal
const onModalTiposSubmit = async (e) => {
    e.preventDefault()
    try {
        const request = {
            id: txt_id.value || undefined,
            _categoria: cbo_categoria.value,
            tipo: txt_tipo.value,
            descripcion: txt_descripcion.value
        }
        const { status, result } = await Fetch('./api/tipos', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al guardar el tipo')
        $('#modal-tipos').modal('hide')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El tipo se ha guardado correctamente'
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
const onButtonDetailClicked = async (data) => {
    const modal = $('#modal-tickets')

    modal.modal('show')
}

// Cuando presionamos el boton eliminar de la fila
const onButtonDeleteClicked = async ({ id }) => {
    try {
        const { status, result } = await Fetch(`./api/tipos/${id}`, {
            method: 'DELETE'
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al eliminar el tipo')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El tipo se ha eliminado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const onButtonStatusClicked = async ({ id, estado }) => {
    try {
        const { status, result } = await Fetch(`./api/tipos/${id}`, {
            method: 'PATCH',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: estado
            })
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al cambiar el estado de el tipo')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El tipo ha cambiado de estado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}