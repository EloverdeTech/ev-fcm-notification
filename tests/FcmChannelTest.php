<?php

namespace Benwilkins\FCM\Tests;

use Mockery as m;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Benwilkins\FCM\FcmChannel;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Notifications\Notification;
use PHPUnit\Framework\Attributes\Test;

class FcmChannelTest extends TestCase
{
    /**
     * @var Client|\Mockery\MockInterface
     */
    protected $client;

    /**
     * @var FcmChannel
     */
    protected $channel;

    /** @var TestNotification */
    protected $notification;

    /** @var Notifiable|\Mockery\MockInterface */
    protected $notifiable;

    public function setUp(): void
    {
        $this->events = m::mock(FcmChannel::class);
        $this->client = m::mock(Client::class);
        $this->channel = new FcmChannel($this->client, '');
        $this->notification = new TestNotification;
        $this->notifiable = m::mock(Notifiable::class);
    }

    #[Test] public function it_can_send_a_notification()
    {
        $response = new Response(200, [], '{}');

        $this->notifiable->shouldReceive('routeNotificationFor')
            ->andReturnTrue();

        $this->client->shouldReceive('post')
            ->once()
            ->withAnyArgs()
            ->andReturn($response);

        $this->channel->send($this->notifiable, $this->notification);
    }

    #[Test] public function ic_cannot_send_a_notification()
    {
        $this->notifiable->shouldReceive('routeNotificationFor')
            ->andReturnNull();

        $this->client->shouldNotReceive('post');

        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotification extends Notification
{
    public function toFcm($notifiable)
    {
        return new FcmMessage();
    }
}
