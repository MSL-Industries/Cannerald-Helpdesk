<?php

namespace App\Listeners;

use App\Events\TicketCommented;
use GuzzleHttp\Client;

class SendChatMessage
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
            $client = new Client();

            $options = [
                'form_params' => [
                    'message' => $event->comment->body,
                    'api_key' => env('API_TOKEN')
                ]
            ];

            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];

            $client->request('post', env('APP_AUTH_ENDPOINT') . 'support/ticket/' . $event->ticket->id . '/messages', $options);

        } catch (\Exception $e) {

        }

    }

}
