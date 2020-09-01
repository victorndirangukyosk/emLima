<?php

class ModelAccountRefer extends Model
{
    public function send($data)
    {
        // Create the mail transport configuration
        /*   $transport = Swift_MailTransport::newInstance();

          // Create the message
          $message = Swift_Message::newInstance();
          $message->setTo(array(
          "aurelioderosa@gmail.com" => "Aurelio De Rosa",
          "info@audero.it" => "Audero"
          ));
          $message->setSubject("This email is sent using Swift Mailer");
          $message->setBody("You're our best client ever.");
          $message->setFrom("account@bank.com", "Your bank");

          // Send the email
          $mailer = Swift_Mailer::newInstance($transport);
          $mailer->send($message); */
    }
}
