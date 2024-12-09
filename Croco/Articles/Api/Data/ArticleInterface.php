<?php

declare(strict_types=1);
namespace Croco\Articles\Api\Data;

interface ArticleInterface
{
    const ARTICLE_ID = 'article_id';
    const TITLE = 'title';
    const SHORT_DESCRIPTION = 'short_description';
    const BODY = 'body';
    const IMAGE = 'image';
    const PUBLISHED_AT = 'published_at';
    const STATUS = 'status';

    public function getId();
    public function setId($id);

    public function getTitle();
    public function setTitle($title);

    public function getShortDescription();
    public function setShortDescription($shortDescription);

    public function getBody();
    public function setBody($body);

    public function getImage();
    public function setImage($image);

    public function getPublishedAt();
    public function setPublishedAt($publishedAt);

    public function getStatus();
    public function setStatus($status);
}
