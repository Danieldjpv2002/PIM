const onRefreshPreview = () => {
    const radio = document.querySelector('[name="rd_preview"]:checked')
    let content = templateEditor.getContent()
    if (radio.id == 'rd_whatsapp') {
        content = `<body style="background-color: #0a151a; padding: 20px; margin: 0; font-family: Poppins,sans-serif">
            <div style="position: absolute; top: 0; left: 0; height: 100vh; width: 100vw; border: 0; background-image: url('//${SERVICE}.${DOMAIN}/assets/images/bg-whatsapp.png')"></div>
            <div style="display: block; width: 200px; height: 40px; background-color: #005c4b; position: absolute; border-radius: 10px 0 10px 10px; right: 20px;">
                <svg style="position: absolute; top: 0; right: -8px;" viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 8 13"><path opacity="0.13" d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z"></path><path fill="#005c4b" d="M5.188,0H0v11.193l6.467-8.625C7.526,1.156,6.958,0,5.188,0z"></path></svg>
            </div>
            <div style="display: block; width: 200px; height: 40px; background-color: #202c33; position: absolute; border-radius: 0 10px 10px 10px; left: 20px; top: 80px">
                <svg style="position: absolute; top: 0; left: -8px;" viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 8 13"><path opacity="0.13" fill="#202c33" d="M1.533,3.568L8,12.193V1H2.812 C1.042,1,0.474,2.156,1.533,3.568z"></path><path fill="#202c33" d="M1.533,2.568L8,11.193V0L2.812,0C1.042,0,0.474,1.156,1.533,2.568z"></path></svg>
            </div>
            <div style="display: block; min-width: 200px; max-width: 75%; min-height: 40px; background-color: #005c4b; position: absolute; border-radius: 10px 0 10px 10px; right: 20px; top: 140px; color: #e9edef; padding: 10px">
                <svg style="position: absolute; top: 0; right: -8px;" viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 8 13"><path opacity="0.13" d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z"></path><path fill="#005c4b" d="M5.188,0H0v11.193l6.467-8.625C7.526,1.156,6.958,0,5.188,0z"></path></svg>
                ${content.HTML2WA().WA2HTML()}
            </div>
        </body>`
    } else {
        previewer.style.backgroundColor = '#ffffff'
    }
    const dataURI = `data:text/html,${encodeURIComponent(content)}`
    previewer.src = dataURI
}