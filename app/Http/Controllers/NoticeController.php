<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * メッセージを通知する
     *
     * @param String $message
     * @return void
     */
    private function send_text_mail(String $send_message) {
       // テキストメール送信
       $from_email = "test@test.server.com";
       $from_name = "MusicDiggers";
       $mail_subject = "Message From MusicDiggers";
       $mail_content = $send_message;
       $to_email = "customer@user.com";
       Mail::send([], [], function($message) use ($from_email, $from_name, $mail_subject, $mail_content, $to_email ) {
           $message->from( $from_email, $from_name );
           $message->to( $to_email );
           $message->subject( $mail_subject );
           $message->setBody($mail_content);
       });
    }
}
