<?php

namespace App\Listeners;

use App\Events\TicketCommented;
use App\Events\TicketCreated;
use GuzzleHttp\Client;

class SendNewCommentEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TicketCreated $event
     *
     * @return void
     */
    public function handle(TicketCommented $event)
    {

        try {
            if (!$event->comment->private && $event->comment->user_id !== null) {

                $client = new Client();

                $options = [
                    'form_params' => [
                        'message' => $event->comment->body,
                        'email' => $event->ticket->requester->email,
                        'api_key' => env('API_TOKEN')
                    ]
                ];

                $options['headers'] = [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ];

                $client->request('post', env('APP_AUTH_ENDPOINT') . 'support/ticket/' . $event->ticket->id . '/comment-event', $options);
            }
        } catch (\Exception $e) {

        }

    }

}
