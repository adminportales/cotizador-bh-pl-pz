<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuotePL extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'pl';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $vendedor, $cliente, $file;
    public function __construct($vendedor, $cliente, $file)
    {
        $this->vendedor = $vendedor;
        $this->cliente = $cliente;
        $this->file = $file;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.quotepdf.promolife')
            ->with('cliente', $this->cliente)
            ->with('vendedor', $this->vendedor)
            ->subject('Cotizacion BH TradeMarket')
            ->from(auth()->user()->email, 'BH TradeMarket')
            ->attach(public_path() . $this->file, [
                'as' => 'Hoja de Cotizacion.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
