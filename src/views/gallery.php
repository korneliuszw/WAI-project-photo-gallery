<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "commons/head.php" ?>
</head>
<body>
<?php include "commons/navbar.php" ?>
<?php include "components/gallery-item.php" ?>
<?php include "components/pagination.php" ?>
<script>
    let lastValue;

    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, timeout);
        };
    }

    function search(value) {
        lastValue = value
        const path = "<?php echo $model->view_saved ? 'saved' : 'gallery'?>"
        return fetch(`/${path}?searchPhrase=${encodeURIComponent(value)}`, {
            headers: {
                'Accept': 'application/json',
            },
        }).then(res => res.json()).then((response) => {
            setGalleryItems(response.uploads)
            $('pagination-buttons')
                .attr('items-total', response.pagination.total)
                .attr('current-page', response.pagination.page)
                .attr('page-size', response.pagination.pageSize)
                .attr('search-value', lastValue)
            renderPaginations()
        })
    }

    const debounceSearch = debounce(search)

    function setGalleryItems(items) {
        $('.gallery').empty().each(function () {
            for (const item of items) {
                $el = $('<gallery-item>')
                    .attr('title', item.title)
                    .attr('uploader', item.uploader ?? '')
                    .attr('full-src', item.fullImagePath)
                    .attr('preview-src', item.miniatureImagePath)
                    .attr('id', item.id)
                <? echo $model->view_saved ? ".attr('forget', true)\n" : '' ?>

                <? echo $model->view_saved ? "" : 'if (item.saved) $el.attr("saved", item.saved)'?>

                $(this).append($el)
            }
        })
    }

    function onSearchChange() {
        const value = document.getElementById('search').value
        if (value !== lastValue)
            debounceSearch(document.getElementById('search').value)
    }

    <? if ($model->view_saved) { ?>
    function handleForget() {
        const items = JSON.parse(sessionStorage.getItem('itemStore-forget'))
        const ids = Object.keys(items)
        fetch('/saved', {
            method: 'POST',
            body: JSON.stringify({
                photoId: ids,
                forget: true
            }),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            console.debug('forgot!')
            sessionStorage.removeItem('itemStore-forget')
            const savedItems = JSON.parse(sessionStorage.getItem('itemStore-remember'))
            for (const id of ids) {
                if (savedItems[id]) delete savedItems[id]
            }
            sessionStorage.setItem('itemStore-remember', JSON.stringify(savedItems))
            window.location = '/';
        })

    }
    <? } else { ?>
    function handleSave() {
        const items = JSON.parse(sessionStorage.getItem('itemStore-remember'))
        const ids = Object.keys(items)
        fetch('/saved', {
            method: 'POST',
            body: JSON.stringify({
                photoId: ids
            }),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            console.debug('saved!')
            window.location = '/saved';
        })
    }
    <? } ?>

    window.addEventListener('DOMContentLoaded', () => {
        console.log('loaded')
        document.querySelector('.search').innerHTML = `
                <input class="form-control me-2" type="search" placeholder="Search" name="search" id="search" oninput="onSearchChange(event)" aria-label="Search"
            <? if (strlen($model->pagination->searchPhrase) > 0) echo 'value="' . $model->pagination->searchPhrase . '"'; ?>
        >
        `
    })

</script>

<article class="container">
    <h3 class="mr-auto ml-auto text-center">
        <?php echo $model->view_saved ? 'Saved images' : 'Gallery' ?>
    </h3>
    <main class="gallery">
        <?php foreach ($model->uploads as $photo) { ?>
            <gallery-item class="d-flex justify-content-center" title="<? echo $photo->title ?>"
                          uploader="<? echo $photo->uploader ?>"
                          full-src="<? echo $photo->fullImagePath ?>"
                          preview-src="<? echo $photo->miniatureImagePath ?>"
                <? echo $photo->saved && !$model->view_saved ? 'saved' : '' ?>
                          id="<? echo $photo->id ?>"
                <? echo $model->view_saved ? 'forget' : '' ?>
            ></gallery-item>
        <?php } ?>
    </main>
    <button class="btn btn-secondary" type="submit"
            onclick="<?php echo $model->view_saved ? 'handleForget' : 'handleSave' ?>(event)">
        <?php echo $model->view_saved ? 'Forget selected items' : 'Remember selected items' ?>
    </button>
    <pagination-buttons items-total="<? echo $model->pagination->total ?>"
                        current-page="<? echo $model->pagination->page ?>"
                        page-size="<? echo $model->pagination->pageSize ?>"
        <? if (strlen($model->pagination->searchPhrase) > 0) echo 'search-phrase="' . $model->pagination->searchPhrase . '"'; ?>
    ></pagination-buttons>
</article>


</body>
</html>