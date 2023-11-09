const onServicesClicked = async (business, button) => {
    const dropdown = button.next()
    if (!dropdown.hasClass('show')) return

    dropdown.html('<span class="dropdown-item text-muted disabled"><i class="fa fa-spin fa-spinner"></i> Cargando...</span>')

    try {
        const { status, result } = await Fetch(`./api/services/business/${business}`)
        dropdown.empty()
        if (!status) throw new Error(result?.message || 'Ocurrio un error inesperado')

        if (result.data.length == 0) {
            dropdown.html('<i class="dropdown-item text-muted disabled">- Esta empresa no tiene servicios -</i>')
            throw new Error('Esta empresa no tiene servicios')
        }
        result.data.forEach(({ service, business }) => {
            const a = $(`<a id="btn-open-service" class="dropdown-item">`)
            a.attr('title', service.description)
            a.css('cursor', `pointer`)
            a.prop('data-business', business.id)
            a.prop('data-service', JSON.stringify(service))
            a.append(`Abrir <b>${service.service}</b>`)
            a.append(' <i class="fe-arrow-up-right"></i>')
            dropdown.append(a)
        });
    } catch (error) {
        console.log(error)
    }
    /*
    <div class="dropdown-menu" style="">
        <a class="dropdown-item" href="#">Dropdown link</a>
        <a class="dropdown-item" href="#">Dropdown link</a>
    </div>
    */

}

const onOpenServiceClicked = async (e) => {
    const button = $(e.currentTarget)
    const businessId = button.prop('data-business')
    const { id: serviceId, service: serviceName } = JSON.parse(button.prop('data-service'))

    const { status, result } = await Fetch(`//auth.${DOMAIN}/api/auth/session`, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            service: serviceId,
            business: businessId
        })
    })

    console.log({ status, result })
}

const onModalCategoriesSubmit = async (e) => {
    e.preventDefault()
    try {
        const request = {
            id: txt_id.value || undefined,
            category: txt_category.value,
            description: txt_description.value
        }
        const { status, result } = await Fetch('./api/categories', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request)
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al guardar la categoria')
        $('#modal-categories').modal('hide')
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

const onButtonUpdateClicked = async (data) => {
    const modal = $('#modal-categories')
    if (data?.id) modal.find('.modal-title').text('Editar categoria')
    else modal.find('.modal-title').text('Nueva categoria')

    txt_id.value = data?.id ?? ''
    txt_category.value = data?.category ?? ''
    txt_description.value = data?.description ?? ''

    modal.modal('show')
}

const onButtonDeleteClicked = async ({ id }) => {
    try {
        const { status, result } = await Fetch(`./api/categories/${id}`, {
            method: 'DELETE'
        })
        if (!status) throw new Error(result?.message || 'Ocurrio un error al borrar la categoria')
        dataGrid.refresh()
        Notify.add({
            title: 'Operacion correcta',
            body: 'La categoria se ha eliminado correctamente'
        })
    } catch (error) {
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'error'
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