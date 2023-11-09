const intervalId = setInterval(() => {
    if (templateEditor != null) {
        clearInterval(intervalId)
        templateEditor.on('change', () => {
            onRefreshPreview()
        })
        templateEditor.on('keyup', () => {
            onRefreshPreview()
        })
    }
}, 100);

$(document).on('change', '[name="rd_preview"]', () => onRefreshPreview())