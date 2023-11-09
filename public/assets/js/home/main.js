(async () => {
    const { status, result } = await Fetch('api/services')
    if (!status) return
    result.data.forEach(service => {
        $('#services').append(`<div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-xs-8 col-xxs-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <img class="my-2" src="//${DOMAIN}/img/icons/${service.correlative}.icon.svg" width="100">
                    </div>
                    <h3>${service.service}</h3>
                    <p>${service.description}</p>
                        <button class="btn btn-sm btn-primary">Ver mas</button>
                        <a class="btn btn-sm btn-success" href="//${service.correlative}.${DOMAIN}/home" target="_blank">Acceder</a>
                </div>
            </div>
        </div>`)
    });
})()