// Configuración de solicitudes Fetch
FetchParams = {
    headers: {
        'Auth-User': Cookies.get('Auth-User'),
        'Auth-Token': Cookies.get('Auth-Token')
    }
}