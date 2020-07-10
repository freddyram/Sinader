<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCertIndDeclaration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $objSend;

    public function __construct()
    {
        //
        $this->objSend = $objSend
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Certificado de Declaración')
                    ->view('mails.MailCertDeclaration')
                    ->text('text.MailCertDeclaration')
                    ->attach($objSend->zip_file);
}
