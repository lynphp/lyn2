
async function get(comp, query = 'all') {
    var myHeaders = new Headers();
    myHeaders.append("Lyn-Request-Header", "application/fragment");

    var requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
    };
    let url = '/lyn/http/component/{comp}?class=shoe'.replace('{comp}', comp);
    return await fetch(url, requestOptions)
        .then(response => response.text())
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));
}

window.addEventListener('load', async () => {
    var elems = document.body.getElementsByTagName("x-lyn-component");
    for (var i = 0; i < elems.length; i++) {
        var elem = elems.item(i)
        var renderType = elem.getAttribute('render');
        if (renderType == null || renderType === 'csr') {
            var response = await get(elem.attributes.class.value);
            elem.innerHTML = response;
        }
    }
})