<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDataOdoo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email, $file;
    public function __construct($email, $file)
    {
        $this->email = $email;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.sendData.dataOdoo')
            ->with('url', url('/'))
            ->subject('Informaicion Extraida de Odoo')
            ->from('gerencia@trademarket.com.mx', 'Cotizador BH - PL')
            ->attach(public_path() . $this->file, [
                'as' => 'name.txt',
                'mime' => 'text/plain',
            ]);
    }
}
