// Cuando presionamos el boton editar de la fila
const onButtonDetailClicked = async (data) => {
    const modal = $('#modal-tickets')
    console.log(data);
    txt_informador.textContent = `${data.informador.nombres} ${data.informador.apellidos}`
    txt_categoria.textContent = data.tipo.categoria.categoria
    txt_tipo.textContent = data.tipo.tipo
    txt_resumen.textContent = data.asunto
    contenedor_descripcion.innerHTML = data.descripcion
    getAdjuntos(data.id)
    modal.modal('show')
}
