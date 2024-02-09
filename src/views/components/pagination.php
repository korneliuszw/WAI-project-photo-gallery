<script>

    function getPageUrlWithSearch(searchPhrase, page) {
        let str = `?page=${page}`
        if (searchPhrase?.length)
            return str + `&searchPhrase=${searchPhrase}`
        return str
    }

    function renderPagination() {
        const itemsTotal = $(this).attr('items-total')
        const currentPage = $(this).attr('current-page')
        const pageSize = $(this).attr('page-size')
        const searchPhrase = $(this).attr('search-phrase')
        const totalPages = Math.ceil(itemsTotal / pageSize)
        const pagesToDisplay = (() => {
            const pages = []
            for (let i = currentPage; i < Math.min(totalPages, currentPage + 2); i++) {
                pages.push(+i)
            }
            return pages
        })()
        console.log(itemsTotal, currentPage, pagesToDisplay, pageSize)
        const getPageUrl = getPageUrlWithSearch.bind(this, searchPhrase)
        $(this).html(
            `
                <section class="pagination-container mt-3">
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><a class="page-link" href="${getPageUrl(0)}">First</a></li>
                        <li class="page-item"><a class="page-link"
                                                 href="${getPageUrl(Math.max(currentPage - 1, 0))}">Previous</a>
                        </li>
                        ${pagesToDisplay.map(page => `
                <li class="page-item"><a class="page-link page-number"
                                         href="${getPageUrl(page)}">${page + 1}</a>
                </li>`).join('\n')}
                        <li class=" page-item"><a class="page-link"
                                                  href="${getPageUrl(Math.min(currentPage + 1, totalPages - 1))}">Next</a>
                        </li>
                        <li class="page-item"><a class="page-link"
                                                 href="${getPageUrl(Math.max(totalPages - 1, 0))}">Last</a>
                        </li>
                    </ul>
                    <div>
                        Total images: ${itemsTotal}
                    </div>
                </section>
            `
        )
    }

    function renderPaginations() {
        $('pagination-buttons').each(renderPagination)
    }

    document.addEventListener('DOMContentLoaded', () => renderPaginations())
</script>