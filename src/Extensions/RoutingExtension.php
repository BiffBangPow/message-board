<?php

namespace BiffBangPow\MessageBoard\Extensions;

class RoutingExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'paginationUrl' => new \Twig_SimpleFunction('paginationUrl', array($this, 'paginationUrl')),
            'threadUrl' => new \Twig_SimpleFunction('threadUrl', array($this, 'threadUrl')),
            'reportURL' => new \Twig_SimpleFunction('reportURL', array($this, 'reportURL')),
            'indexURL' => new \Twig_SimpleFunction('indexURL', array($this, 'indexURL')),
            'newCommentURL' => new \Twig_SimpleFunction('newCommentURL', array($this, 'newCommentURL')),
            'newThreadURL' => new \Twig_SimpleFunction('newThreadURL', array($this, 'newThreadURL')),
            'loginURL' => new \Twig_SimpleFunction('loginURL', array($this, 'loginURL')),
            'registerURL' => new \Twig_SimpleFunction('registerURL', array($this, 'registerURL')),
        );
    }

    public function paginationUrl(int $id)
    {
        return '?page='.$id;
    }

    public function threadUrl(int $id)
    {
        return '/thread/'.$id;
    }

    public function reportURL(int $id)
    {
        return '/comments/'.$id.'report';
    }

    public function indexURL()
    {
        return '/';
    }

    public function newCommentURL(int $id)
    {
        return '/threads/'.$id.'/comments/new';
    }

    public function newThreadURL()
    {
        return '/threads/new';
    }

    public function loginURL()
    {
        return '/user/login';
    }

    public function registerURL()
    {
        return '/user/register';
    }


}