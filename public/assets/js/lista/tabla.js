var dataGrid;

(async () => {
    DevExpress.localization.locale('es');

    const { result } = await Fetch('./api/estados')
    const estados = result.data ?? []

    dataGrid = $("#dataGrid").dxDataGrid({
        dataSource: {
            load: async (params) => {
                const { result } = await Fetch('./api/tickets/paginado', {
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
                dataField: 'tipo.categoria.categoria',
                caption: 'Categoria',
                dataType: 'string'
            },
            {
                dataField: 'tipo.tipo',
                caption: 'Tipo',
                dataType: 'string'
            },
            {
                dataField: 'asunto',
                caption: 'Asunto',
                dataType: 'string'
            },
            {
                dataField: 'estado.estado',
                caption: 'Estado',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.css('overflow', 'unset')
                    const btnGroup = $('<div class="btn-group">')

                    const button = $('<button class="btn btn-xs btn-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">')
                    button.html(data?.estado?.estado || '<i class="text-muted">- Sin estado -</i>')
                    btnGroup.append(button)

                    if (data.estado.id != 3) {
                        const dropdownMenu = $('<div class="dropdown-menu">')

                        estados.forEach(({ id, estado, descripcion }) => {
                            const item = $('<span class="dropdown-item">')
                            item.text(estado)
                            item.attr('title', descripcion)

                            if (data?.estado?.id != id) {
                                item.css('cursor', 'pointer')
                                item.on('click', (e) => onTicketEstadoClicked(id, data.id))
                            }

                            tippy(item.get(0), { arrow: true })

                            dropdownMenu.append(item)
                        })

                        btnGroup.append(dropdownMenu)
                    }

                    container.html(btnGroup)
                }
            },
            {
                dataField: 'informador.importancia',
                caption: 'Importancia',
                dataType: 'number'
            },
            {
                dataField: 'informador.nombres',
                caption: 'Informador',
                dataType: 'string'
            },
            {
                dataField: 'responsable.nombres',
                caption: 'Responsable',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.html(data?.responsable?.nombres || '<i class="text-muted">- Sin responsable -</i>')
                }
            },
            {
                dataField: 'fecha_creacion',
                caption: 'Fecha creacion',
                dataType: 'string'
            },
            {
                caption: 'Acciones',
                cellTemplate: (container, { data }) => {
                    container.attr('style', 'display: flex; gap: 4px; overflow: unset')

                    const btnDetalles = $('<button>').addClass('btn btn-xs btn-soft-primary')
                    btnDetalles.append('<i class="fa fa-list"></i>')
                    btnDetalles.attr('title', 'Ver detalles')
                    btnDetalles.on('click', () => onButtonDetailClicked(data))
                    tippy(btnDetalles.get(0), { arrow: true })
                    container.append(btnDetalles)
                },
                allowFiltering: false,
                allowExporting: false
            }
        ]
    }).dxDataGrid('instance');
})();