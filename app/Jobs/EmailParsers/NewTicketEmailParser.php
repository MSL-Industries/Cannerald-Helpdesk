<?php

namespace App\Jobs\EmailParsers;

use App\Attachment;
use App\Ticket;
use App\TicketType;

class NewTicketEmailParser
{
    public function handle($message)
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::createAndNotify(['name' => $message->fromName, 'email' => $message->fromAddress], $message->subject, $message->body(), ['email']);
        Attachment::storeAttachmentsFromEmail($message, $ticket);

        if (strpos(strtolower($message->subject), 'recovery') !== false) {
            $ticket->type()->associate(TicketType::where('name', 'recovery')->first());
            $ticket->save();
        }

        return true;
    }
}
