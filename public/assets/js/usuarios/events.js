// Cuando presionamos el boton guardar del modal
const onModalUsuariosSubmit = async (e) => {
    e.preventDefault()
    try {
        const request = {
            id: txt_id.value || undefined,
            usuario: txt_usuario.value,
            clave: txt_clave.value || undefined,
            nombres: txt_nombres.value,
            apellidos: txt_apellidos.value,
            correo: txt_correo.value,
            telefono: txt_telefono.value,
            ip: txt_ip.value,
            anydesk: txt_anydesk.value,
            rol: cbo_rol.value,
            importancia: txt_importancia.value,
        }
        const { status, result } = await Fetch('./api/usuarios', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al guardar el usuario')
        $('#modal-usuarios').modal('hide')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El usuario se ha guardado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}

// Cuando presionamos el boton editar de la fila
const onButtonUpdateClicked = async (data) => {
    const modal = $('#modal-usuarios')
    if (data?.id) modal.find('.modal-title').text('Editar usuario')
    else modal.find('.modal-title').text('Nuevo usuario')

    txt_id.value = data?.id ?? ''
    txt_usuario.value = data?.usuario ?? ''
    txt_clave.value = ''
    txt_nombres.value = data?.nombres ?? ''
    txt_apellidos.value = data?.apellidos ?? ''
    txt_correo.value = data?.correo ?? ''
    txt_telefono.value = data?.telefono ?? ''
    txt_ip.value = data?.ip ?? ''
    txt_anydesk.value = data?.anydesk ?? ''
    cbo_rol.value = data?.rol ?? ''
    txt_importancia.value = data?.importancia ?? ''

    $(cbo_rol).select2({
        dropdownParent: '#modal-usuarios'
    })

    modal.modal('show')
}

// Cuando presionamos el boton eliminar de la fila
const onButtonDeleteClicked = async ({ id }) => {
    try {
        const { status, result } = await Fetch(`./api/usuarios/${id}`, {
            method: 'DELETE'
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al eliminar el usuario')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El usuario se ha eliminado correctamente'
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
        const { status, result } = await Fetch(`./api/usuarios/${id}`, {
            method: 'PATCH',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: estado
            })
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al cambiar el estado del usuario')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'El usuario ha cambiado de estado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}