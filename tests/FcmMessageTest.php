<?php

namespace Benwilkins\FCM\Tests;

use Benwilkins\FCM\FcmMessage;
use PHPUnit\Framework\Attributes\Test;

class FcmMessageTest extends TestCase
{
    /** @var FcmMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = new FcmMessage();
    }

    #[Test] public function it_has_default_priority()
    {
        $priority = json_decode($this->message->formatData(), true)['priority'];
        $this->assertEquals($priority, FcmMessage::PRIORITY_NORMAL);
    }
}
