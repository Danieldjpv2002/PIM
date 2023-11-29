var dataGrid

const loadCategorias = async () => {
    const { status, result } = await Fetch('./api/categorias')
    if (!status) return
    const { data } = result
    const cbo = $(cbo_categoria)
    cbo.html('<option value>- Seleccione una categoria -</option>')
    data.forEach(({ id, categoria }) => {
        const opt = $('<option>')
        opt.val(id)
        opt.text(categoria)
        cbo.append(opt)
    })
    cbo.select2({ dropdownParent: $('#modal-templates') })
}

(async () => {
    DevExpress.localization.locale('es');
    dataGrid = $("#dataGrid").dxDataGrid({
        dataSource: {
            load: async (params) => {
                const { result } = await Fetch('./api/usuarios/paginado', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(params)
                })
                return result
            },
        },
        onToolbarPreparing: (e) => {
            const { items } = e.toolbarOptions;
            items.unshift({
                widget: 'dxButton',
                location: 'after',
                options: {
                    icon: 'refresh',
                    hint: 'REFRESCAR TABLA',
                    onClick: () => dataGrid.refresh()
                }
            });
            items.unshift({
                widget: 'dxButton',
                location: 'after',
                options: {
                    icon: 'plus',
                    hint: 'NUEVO REGISTRO',
                    onClick: () => onButtonUpdateClicked()
                }
            });
        },
        remoteOperations: true,
        columnResizingMode: "widget",
        columnAutoWidth: true,
        showBorders: true,
        scrollbars: 'auto',
        filterPanel: { visible: true },
        searchPanel: { visible: true },
        headerFilter: { visible: true },
        height: 'calc(100vh - 185px)',
        export: {
            enabled: true
        },
        onExporting: function (e) {
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Main sheet');
            DevExpress.excelExporter.exportDataGrid({
                worksheet: worksheet,
                component: e.component,
                customizeCell: function (options) {
                    // options.excelCell.font = { name: 'Arial', size: 12 };
                    options.excelCell.alignment = { horizontal: 'left' };
                }
            }).then(function () {
                workbook.xlsx.writeBuffer().then(function (buffer) {
                    saveAs(new Blob([buffer], { type: 'application/octet-stream' }), `categorias.${SERVICE}.xlsx`);
                });
            });
        },
        filterRow: {
            visible: true,
            applyFilter: "auto"
        },
        filterBuilderPopup: {
            visible: false,
            position: {
                of: window, at: 'top', my: 'top', offset: { y: 10 },
            },
        },
        paging: {
            pageSize: 10,
        },
        pager: {
            visible: true,
            allowedPageSizes: [5, 10, 25, 50, 100],
            showPageSizeSelector: true,
            showInfo: true,
            showNavigationButtons: true,
        },
        allowFiltering: true,
        scrolling: {
            mode: 'standard',
            useNative: true,
            preloadEnabled: true,
            rowRenderingMode: 'standard'
        },
        columnChooser: {
            enabled: true,
            mode: 'select'
        },
        columns: [
            {
                dataField: 'id',
                caption: 'ID',
                dataType: 'number'
            },
            {
                dataField: 'rol',
                caption: 'Rol',
                dataType: 'string'
            },
            {
                dataField: 'usuario',
                caption: 'Usuario',
                dataType: 'string'
            },
            {
                dataField: 'nombres',
                caption: 'Nombres',
                dataType: 'string'
            },
            {
                dataField: 'apellidos',
                caption: 'Apellidos',
                dataType: 'string'
            },
            {
                caption: 'Acciones',
                cellTemplate: (container, { data }) => {
                    container.attr('style', 'display: flex; gap: 4px; overflow: unset')

                    const btnEdit = $('<button>').addClass('btn btn-xs btn-soft-primary')
                    btnEdit.append('<i class="fa fa-pen"></i>')
                    btnEdit.attr('title', 'Editar')
                    btnEdit.on('click', () => onButtonUpdateClicked(data))
                    tippy(btnEdit.get(0), { arrow: true })
                    container.append(btnEdit)

                    const btnStatus = $('<button>').addClass('btn btn-xs btn-light')
                    if (data.estado == 1) btnStatus.append('<i class="fa fa-toggle-on text-success"></i>')
                    else if (data.estado == 0) btnStatus.append('<i class="fa fa-toggle-off text-danger"></i>')
                    else btnStatus.append('<i class="fa fa-pen"></i>')
                    btnStatus.attr('title', 'Cambiar estado')
                    btnStatus.on('click', () => onButtonStatusClicked(data))
                    tippy(btnStatus.get(0), { arrow: true })
                    container.append(btnStatus)

                    const btnDelete = $('<button>').addClass('btn btn-xs btn-soft-danger')
                    btnDelete.append('<i class="fa fa-trash-alt"></i>')
                    btnDelete.attr('title', 'Eliminar')
                    btnDelete.on('click', () => onButtonDeleteClicked(data))
                    tippy(btnDelete.get(0), { arrow: true })
                    container.append(btnDelete)
                },
                allowFiltering: false,
                allowExporting: false
            }
        ]
    }).dxDataGrid('instance');
    loadCategorias()
})();