const onLoginSubmit = async (e) => {
    e.preventDefault()

    try {
        const usuario = $('#txt-usuario').val()
        const clave = $('#txt-clave').val()

        if (!usuario || !clave) throw new Error('Ingrese todos los campos')

        const { status, result } = await Fetch('./api/auth', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ usuario, clave })
        })

        if (!status) throw new Error(result?.message || 'Error al iniciar sesion')

        console.log(result.data.usuario, result.data.token)

        Cookies.set('SoDe-Auth-User', result.data.usuario, 7)
        Cookies.set('SoDe-Auth-Token', result.data.token, 7)

        location.href = '/'

    } catch (error) {
        console.log(error)
        Notify.add({
            title: 'Error',
            body: error.message,
            type: 'danger'
        })
    }
}