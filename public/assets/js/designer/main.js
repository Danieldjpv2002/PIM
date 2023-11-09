var templateEditor = null;

(async () => {
    [templateEditor] = await tinymce.init({
        selector: '#template_editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'SoDe World',
        language: 'es',
        height: 'calc(100vh - 170px)',
        paste_data_images: false
    });
})()