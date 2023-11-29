const drawSession = (data) => {
    const flatten = JSON.flatten(data)

    $(`[rol-${data.rol}]`).fadeIn(125)

    const tags = document.querySelectorAll('[session]')
    tags.forEach(e => {
        let attr = e.getAttribute('session');
        if (!attr.includes(':')) {
            e.textContent = flatten[attr]
            return
        }
        let parts = attr.split(';').map(x => x.trim())
        parts.forEach(part => {
            const [key, _pseudo] = part.split(':').map(x => x.trim())
            if (!_pseudo) {
                e.textContent = key
                return
            }
            const value = _pseudo.replace(/{([^}]+)}/g, (match, variable) => flatten[variable])
            e.setAttribute(key, value)
        })
    })
}

(async () => {
    let username = Cookies.get('Auth-User')
    let token = Cookies.get('Auth-Token')

    try {
        if (!token || !username) throw new Error('No tienes sesión')

        const { status, result } = await Fetch(`./api/auth/verify`, {
            origin: 'same',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })

        if (!status) throw new Error(result?.message ?? 'No existe una sesión activa')

        drawSession(result.data)

    } catch (error) {
        console.warn(error)
        Cookies.delete('Auth-Token')
        if (SERVICE)
            location.href = `/login`
    }
})()

document.querySelectorAll('#btn-signout').forEach(e => {
    e.onclick = () => {
        Cookies.delete('Auth-User')
        Cookies.delete('Auth-Token')
        location.href = `/login`
    }
})
document.querySelectorAll('#btn-lockout').forEach(e => {
    e.onclick = () => {
        Cookies.delete('Auth-Token')
        location.href = `/login`
    }
})