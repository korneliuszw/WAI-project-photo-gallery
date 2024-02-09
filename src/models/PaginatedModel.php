<?php

namespace Project\Models {
    class PaginatedModel
    {
        public $page;
        public $pageSize;
        public $total;
        public $searchPhrase;

        public function __construct($page, $pageSize, $total, $searchPhrase)
        {
            $this->pageSize = $pageSize;
            $this->page = $page;
            $this->total = $total;
            $this->searchPhrase = $searchPhrase;

        }
    }
}