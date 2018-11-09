<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
   
    
    public function __construct()
    {
        //
//        $this->dados = $dados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {
        if($request->filled('password')){
            $pw = $request->password;
        }else{
            $pw = '123456';
        }
        return $this->subject('Dados de acesso')
                ->markdown('emails.newuser')
                ->with([
                    'name' => $request->name,
                    'username' => $request->username,
                    'password' => $pw
                    ]);
    }
}
