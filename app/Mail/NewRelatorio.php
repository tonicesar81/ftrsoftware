<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewRelatorio extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $shopping;
    protected $loja;
    protected $sistema;
    protected $rev;
    
    public function __construct($shopping,$loja,$sistema,$rev)
    {
        //
//        $this->dados = $dados;
        $this->shopping = $shopping;
        $this->loja = $loja;
        $this->sistema = $sistema;
        $this->rev = $rev;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->subject('Novo relatório')
                ->markdown('emails.relatorio')
                ->with([
                    'shopping' => $this->shopping,
                    'loja' => $this->loja,
                    'sistema' => $this->sistema,
                    'rev' => $this->rev
                    ]);
    }
}
