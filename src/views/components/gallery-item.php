<script type="module">
    import {define, html} from "https://esm.sh/hybrids@8.2.5";


    const getKey = (forget) => forget ? 'itemStore-forget' : 'itemStore-remember'

    export function addItemToStore(host, event) {
        const key = getKey(host.forget)
        const storedItems = JSON.parse(sessionStorage.getItem(key)) ?? {}
        if (event.target.checked && host.id)
            storedItems[host.id] = true
        else
            delete storedItems[host.id]
        sessionStorage.setItem(key, JSON.stringify(storedItems))
    }

    addItemToStore.options = true

    export default define({
        tag: "gallery-item",
        previewSrc: "",
        title: "Hello world",
        uploader: "",
        fullSrc: "",
        id: undefined,
        saved: false,
        forget: false,
        checked: ({id, forget}) => {
            const storedItems = JSON.parse(sessionStorage.getItem(getKey(forget)))
            console.log(storedItems)
            return storedItems?.[id] === true
        },
        render:
            ({title, fullSrc, previewSrc, uploader, checked, saved}) => html`
                <div class="gallery-item">
                    <div class="item-info">
                        <h3>${uploader}</h3>
                        <h3>${title}
                            <input type="checkbox" name="save" disabled="${saved}" checked="${checked || saved}"
                                   onchange="${addItemToStore}"/>
                        </h3>

                    </div>
                    <a href="${fullSrc}">
                        <img width="200" height="125" src="${previewSrc}"
                             alt="${title}"/>
                    </a>

                </div>
            `.css`
                .gallery-item {
                    display: flex;
                    gap: 1rem;
                    width: 50vw;
                    justify-content: space-between;
                    padding: 0.5rem 0;
                    border-top: 1px solid black;
                }
                h1, h3 {
                    display: block;
                    margin: 0.25rem;
                }
            `
    })
</script>