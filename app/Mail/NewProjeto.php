<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewProjeto extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $shopping;
    protected $loja;
   
    
    public function __construct($shopping,$loja)
    {
        //
//        $this->dados = $dados;
        $this->shopping = $shopping;
        $this->loja = $loja;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->subject('Novo projeto para análise')
                ->markdown('emails.newprojeto')
                ->with([
                    'shopping' => $this->shopping,
                    'loja' => $this->loja
                    ]);
    }
}
