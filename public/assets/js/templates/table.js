var dataGrid

const loadCategories = async () => {
    const { status, result } = await Fetch('./api/categories')
    if (!status) return
    const { data } = result
    const cbo = $(cbo_category)
    cbo.html('<option value>- Seleccione una categoria -</option>')
    data.forEach(({ id, category }) => {
        const opt = $('<option>')
        opt.val(id)
        opt.text(category)
        cbo.append(opt)
    })
    cbo.select2({ dropdownParent: $('#modal-templates') })
}

(async () => {
    DevExpress.localization.locale('es');
    dataGrid = $("#dataGrid").dxDataGrid({
        dataSource: {
            load: async (params) => {
                const { result } = await Fetch('./api/templates/paginate', {
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
                    saveAs(new Blob([buffer], { type: 'application/octet-stream' }), `templates.${SERVICE}.xlsx`);
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
                dataField: 'category.category',
                caption: 'Categoria',
                dataType: 'string'
            },
            {
                dataField: 'template',
                caption: 'Plantilla',
                dataType: 'string'
            },
            {
                dataField: 'description',
                caption: 'Descripcion',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.html(data.description || `<i class="text-muted">- Sin descripción -</i>`)
                }
            },
            {
                dataField: 'status',
                caption: 'Estado',
                dataType: 'boolean',
                cellTemplate: (container, { data }) => {
                    if (data.status) {
                        container.html('<span class="badge bg-success rounded-pill">Activo</span>')
                    } else if (data.status == false) {
                        container.html('<span class="badge bg-danger rounded-pill">Inactivo</span>')
                    } else {
                        container.html('<span class="badge bg-light rounded-pill">Eliminado</span>')
                    }
                }
            },
            {
                caption: 'Acciones',
                cellTemplate: (container, { data }) => {
                    container.attr('style', 'display: flex; gap: 4px; overflow: unset')

                    const btnEdit = $('<button>').addClass('btn btn-xs btn-soft-primary')
                    btnEdit.append('<i class="mdi mdi-pencil"></i>')
                    btnEdit.attr('title', 'Modificar metadatos')
                    btnEdit.on('click', () => onButtonUpdateClicked(data))
                    tippy(btnEdit.get(0), { arrow: true })
                    container.append(btnEdit)

                    const btnWrite = $('<button>').addClass('btn btn-xs btn-light')
                    btnWrite.append('<i class="mdi mdi-book-edit-outline"></i>')
                    btnWrite.attr('title', 'Editar plantilla')
                    btnWrite.on('click', () => onButtonUpdateClicked(data))
                    tippy(btnWrite.get(0), { arrow: true })
                    container.append(btnWrite)

                    const btnStatus = $('<button>').addClass('btn btn-xs btn-light')
                    if (data.status == 1) btnStatus.append('<i class="fa fa-toggle-on text-success"></i>')
                    else if (data.status == 0) btnStatus.append('<i class="fa fa-toggle-off text-danger"></i>')
                    else btnStatus.append('<i class="fa fa-pen"></i>')
                    btnStatus.attr('title', 'Cambiar estado')
                    btnStatus.on('click', () => onButtonStatusClicked(data))
                    tippy(btnStatus.get(0), { arrow: true })
                    container.append(btnStatus)

                    const btnDelete = $('<button>').addClass('btn btn-xs btn-soft-danger')
                    btnDelete.append('<i class="mdi mdi-trash-can-outline"></i>')
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
    loadCategories()
})();