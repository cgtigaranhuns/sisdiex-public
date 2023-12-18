<?php

namespace App\Mail;

use App\Models\Inscricao;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovaInscricao extends Mailable
{
    use Queueable, SerializesModels;

   
    
    public function __construct(
        protected Inscricao $inscricao,
    ) {}

    
    /**
     * Get the message envelope.
     */
 /*   public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova Inscrção '.$this->inscricao->n,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.inscricao',
            with: [
                'inscricaoNome' => $this->inscricao->user->nome,
                'acaoTitulo' => $this->inscricao->acao->titulo,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
