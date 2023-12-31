var dataGrid

(async () => {
    DevExpress.localization.locale('es');
    dataGrid = $("#dataGrid").dxDataGrid({
        dataSource: {
            load: async (params) => {
                const { result } = await Fetch('./api/estados/paginado', {
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
                    saveAs(new Blob([buffer], { type: 'application/octet-stream' }), `estados.${SERVICE}.xlsx`);
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
                dataField: 'estado',
                caption: 'Estado',
                dataType: 'string'
            },
            {
                dataField: 'descripcion',
                caption: 'Descripcion',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.html(data.descripcion || `<i class="text-muted">- Sin descripción -</i>`)
                }
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
})();