<?php
    namespace Jouw\Namespace\Hier;

    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
    use Symfony\Component\Mime\Email;

    class mailHelper{
        public static function sendMail(string $author, string $content, string $email) : void {
            // De 2 parameters zijn hier de outgoing mailserver die gaat versturen en de SMTP port.
            $transport = new EsmtpTransport('reawebdev.nl',465);
            // Dit is de mailbox op de webserver die geconfigureerd staat
            $transport->setUsername('studentenmail@reawebdev.nl');
            // Dit is het gekoppelde wachtwoord
            $transport->setPassword('7_0Qmx7h6');
            
            // Hier maken we een Mailer object met de geconfigureerde transport
            $mailer = new Mailer($transport);

            // Hier zet je de gegevens die op de daadwerkelijke email komen
            // Je kan hier bij from() dus wel nog je eigen subdomein plaatsen
            $email = (new Email())
                ->from('no-reply@reawebdev.nl')
                ->to($email)
                ->subject('New comment on your post')
                ->text($author)
                ->html('<p>'.$content.'</p>');

            $mailer->send($email);
        }
    }
?>