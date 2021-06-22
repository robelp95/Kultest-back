<?php


namespace App\Service\Payku;


use Exception;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class NotifyNewSuscription
{
    private Swift_Mailer $mailer;
    private Environment $templating;

    public function __construct(Swift_Mailer $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * [
     *  'from' => kulkoapp@gmail.com,
     *  'dest' => emailusuario@gmail.com,
     *  'username' => "Nombre del usuario",
     *  'planName' => "Nombre del plan",
     *  'url' => "url de la inscripcion"
     * ]
     * @param $data
     * @return int
     * @throws Exception
     */
    public function __invoke($data){
        try {
            $message = (new Swift_Message('Nueva suscripcion a Kulko.app!'))
                ->setFrom($data["from"])
                ->setTo($data["dest"])
                ->setBody(
                    $this->templating->render('new-suscription.html.twig', $data),
                    'text/html'
                )
            ;
            return $this->mailer->send($message);
        }catch (Exception $e){
            throw $e;
        }


    }

}