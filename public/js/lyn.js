
const lynCode = '\x1b[47m\x1b[30mLyn:\x1b[0m';
const endCode = '\x1b[0m'
const greenStartCode = '\x1b[32m'
async function getWebComponent(comp, query = 'all') {
    const myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/fragment");
    log(myHeaders);
    const requestOptions = {
        method: 'GET',
        headers: {
            Accept: "application/fragment",
        },
        redirect: 'follow'
    };
    let url = urlBasePath + 'http/component/{comp}?class=shoe'.replace('{comp}', comp);
    return await fetch(url, requestOptions)
        .then(response => response.text())
        .then(result => {
            return result;
        })
        .catch(error => error('error', error));
}

window.addEventListener('load', async () => {
    var elems = document.body.getElementsByTagName("x-lyn-component");
    for (var i = 0; i < elems.length; i++) {
        const elem = elems.item(i);
        const renderType = elem.getAttribute('render');
        if (renderType == null || renderType === 'csr') {
            const response = await getWebComponent(elem.attributes.class.value);
            elem.innerHTML = response;
        }
    }
})
var js_ready_time;
function __captureReady() {
    js_ready_time = Date.now()
    const txSec = ((1/(php_end_time - php_start_time))*php_num_cores).toFixed(0);
    __dbg('Request received time was ' + greenStartCode + '%f' + endCode, php_start_time)
    __dbg('Server rendered time was ' + greenStartCode + '%f' + endCode, php_end_time)
    __dbg('Page served in ' + greenStartCode + '%f' + endCode + ' seconds', ((php_end_time - php_start_time).toFixed(4)))
    __dbg('Current state of Lyn App can handle' + greenStartCode + ' %f' + endCode + ' trx/sec', (txSec));
    __dbg('Page is ready in ' + greenStartCode + '%f' + endCode + ' seconds', ((js_ready_time/1000)-php_start_time).toFixed(4))
    /*__dbg('js_ready_time: %f',js_ready_time);
    __dbg('js_ready_time: %f',js_ready_time/1000);
    __dbg('php_start_time: %f',php_start_time);
    __dbg('js_ready_time: %f',((js_ready_time/1000)-php_start_time).toFixed(4));*/
}

console.log('【Ｌｙｎ　ＰＨＰ　Ｆｒａｍｅｗｏｒｋ】')
function __calculateLoadTime() {
    const js_load_time = Date.now();
    __dbg('Page from ready to loaded in ' + greenStartCode + '%f' + endCode + ' seconds', ((js_load_time - js_ready_time) / 1000).toFixed(4))
    __dbg('Page loaded in ' + greenStartCode + '%f' + endCode + ' seconds', ((js_load_time / 1000) - php_start_time).toFixed(4))
}
document.addEventListener("readystatechange", (event) => {
    if (event.target.readyState === "interactive") {
        __captureReady();
    } else if (event.target.readyState === "complete") {
        __calculateLoadTime();
    }
});

function debug(message, ...params) {
    console.debug(lynCode + ': ' + new Date().toLocaleTimeString() + ' [DEBUG] ' + message, params)
    console.debug('\x1b[35m                         ↖ ' + new Error().stack.split('\n')[2].trim() + endCode);
}
function log(message, ...params) {
    console.log(lynCode + ': ' + new Date().toLocaleTimeString() + ' [LOG  ] ' + message, params)
    console.debug('\x1b[35m                         ↖ ' + new Error().stack.split('\n')[2].trim() + endCode);
}
function warn(message, ...params) {
    console.warn(lynCode + ': ' + new Date().toLocaleTimeString() + ' [WARN ] ' + message, params)
    console.debug('\x1b[35m                         ↖ ' + new Error().stack.split('\n')[2].trim() + endCode);
}
function error(message, ...params) {
    console.error(lynCode + ': ' + new Date().toLocaleTimeString() + ' [ERROR] ' + message, params)
    console.debug('\x1b[35m                         ↖ ' + new Error().stack.split('\n')[2].trim() + endCode);
}

function __err(message, ...params) {
    console.error(lynCode + ': ' + new Date().toLocaleTimeString() + ' [ERROR] ' + message, params)
}
function __wrn(message, ...params) {
    console.warn(lynCode + ': ' + new Date().toLocaleTimeString() + ' [WARN ] ' + message, params)
}
function __dbg(message, ...params) {
    console.debug(lynCode + ': ' + new Date().toLocaleTimeString() + ' [DEBUG] ' + message, params)
}
function __lg(message, ...params) {
    console.log(lynCode + ': ' + new Date().toLocaleTimeString() + ' [LOG  ] ' + message, params)
}

if(typeof(EventSource) !== "undefined") {
    let source_init = new EventSource(window.location.href);

    source_init.onmessage = function(event) {
        if(sessionStorage.getItem('url')!==window.location.href){
            sessionStorage.clear()
        }else{
            let inputs = document.getElementsByTagName('input');
            for(let i=0;i<inputs.length ;i++){
                console.log(sessionStorage.getItem(inputs[i].name))
                inputs[i].value=sessionStorage.getItem(inputs[i].name);
            }
        }
        sessionStorage.setItem("url", window.location.href);
        source_init.close();
        let source = new EventSource("/hotreload");
        source.onmessage = function(event) {
            let data = JSON.parse(event.data);
            if(data.reload){
                __dbg('Reloading...');
                let inputs = document.getElementsByTagName('input');
                for(let i=0;i<inputs.length ;i++){
                    sessionStorage.setItem(inputs[i].name, inputs[i].value);
                }
                window.location.reload();
            }
        }
    };
} else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
}