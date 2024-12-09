<?php

namespace Croco\Articles\Block;

use Magento\Framework\View\Element\Template;

class Articles extends Template
{
    /**
     * Get articles data passed from controller
     *
     * @return array
     */
    public function getArticles()
    {
        return $this->getData('articles') ?: [];
    }
}
