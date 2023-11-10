// (async () => {
//     const { status, result: { data } } = await Fetch(`//panel.${DOMAIN}/api/business/session/${SERVICE}`)

//     $(businessbyservice).empty()

//     data.sort((a, b) => {
//         if (a.active) return -1;
//         if (b.active) return 1;
//         return a.business.business.localeCompare(b.business.business);
//     });

//     data.forEach(({ active, business, service }) => {
//         const div = $('<li class="list-group-item mb-1"d style="cursor: pointer">')
//         const a = $('<a href="#" class="user-list-item">')
//         a.append(`<div class="user float-start me-3">
//             <i class="mdi mdi-circle text-${active ? 'success' : 'muted'}"></i>
//         </div>`)
//         a.append(`<div class="user-desc overflow-hidden">
//             <h5 class="name mt-0 mb-1">${business.business}</h5>
//             <span class="desc text-muted font-12 text-truncate d-block">${active ? 'Sesion activa' : 'Click para iniciar sesion'}</span>
//         </div>`)
//         div.html(a)
//         !active && div.on('click', async () => {
//             const { status, result } = await Fetch(`//auth.${DOMAIN}/api/auth/session`, {
//                 method: 'POST',
//                 headers: {
//                     Accept: 'application/json',
//                     'Content-Type': 'application/json'
//                 },
//                 body: JSON.stringify({
//                     service: service.id,
//                     business: business.id
//                 })
//             })
//             if (!status) {
//                 Notify.add({
//                     title: 'Error',
//                     body: result?.message || 'Ocurrio un error al cambiar la sesion de empresa',
//                     type: 'error'
//                 })
//                 return
//             }

//             Notify.add({
//                 title: 'Operacion correcta',
//                 body: `Se le redigira a la nueva sesion con la empresa ${business.business}`
//             })
//             location.reload()
//         })
//         $(businessbyservice).append(div)
//     });
// })();