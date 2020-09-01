<?php

class Document
{
    private $title;
    private $description;
    private $keywords;
    private $metas = [];
    private $links = [];
    private $styles = [];
    private $scripts = [];

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function addMeta($name, $content)
    {
        $this->metas[$name] = [
            'name' => $name,
            'content' => $content,
        ];
    }

    public function getMetas()
    {
        return $this->metas;
    }

    public function addLink($href, $rel)
    {
        $this->links[$href] = [
            'href' => $href,
            'rel' => $rel,
        ];
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function addStyle($href, $rel = 'stylesheet', $media = 'screen')
    {
        $this->styles[$href] = [
            'href' => $href,
            'rel' => $rel,
            'media' => $media,
        ];
    }

    public function getStyles()
    {
        return $this->styles;
    }

    public function addScript($script)
    {
        $this->scripts[md5($script)] = $script;
    }

    public function getScripts()
    {
        return $this->scripts;
    }
}
