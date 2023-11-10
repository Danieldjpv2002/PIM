// Cuando presionamos el boton guardar del modal
const onModalCategoriasSubmit = async (e) => {
    e.preventDefault()
    try {
        const request = {
            id: txt_id.value || undefined,
            categoria: txt_categoria.value,
            descripcion: txt_descripcion.value
        }
        const { status, result } = await Fetch('./api/categorias', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al guardar la categoria')
        $('#modal-categorias').modal('hide')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La categoria se ha guardado correctamente'
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
    const modal = $('#modal-categorias')
    if (data?.id) modal.find('.modal-title').text('Editar categoria')
    else modal.find('.modal-title').text('Nueva categoria')

    txt_id.value = data?.id ?? ''
    txt_categoria.value = data?.categoria ?? ''
    txt_descripcion.value = data?.descripcion ?? ''

    modal.modal('show')
}

// Cuando presionamos el boton eliminar de la fila
const onButtonDeleteClicked = async ({ id }) => {
    try {
        const { status, result } = await Fetch(`./api/categorias/${id}`, {
            method: 'DELETE'
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al eliminar la categoria')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La categoria se ha eliminado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

const onButtonStatusClicked = async ({ id, estado: statusCategory }) => {
    try {
        const { status, result } = await Fetch(`./api/categorias/${id}`, {
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
            type: 'danger'
        })
    }
}