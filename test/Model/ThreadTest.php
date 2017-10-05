<?php

namespace BiffBangPow\MessageBoard\Model;

use PHPUnit\Framework\TestCase;

class ThreadTest extends TestCase
{
    public function testGetExcerpt_ContentLessThanLength_ReturnsFullContent()
    {
        $thread = new Thread();
        $content = "Lorem Ipsum";
        $thread->setContent($content);

        $excerpt = $thread->getExcerpt(100);

        $this->assertEquals($content, $excerpt);
    }

    public function testGetExcerpt_ContentMoreThanLength_ReturnsContentTruncatedToLength()
    {
        $thread = new Thread();
        $content = "Lorem Ipsum";
        $thread->setContent($content);

        $excerpt = $thread->getExcerpt(10);

        $this->assertEquals("Lorem Ipsu", $excerpt);
    }
}
