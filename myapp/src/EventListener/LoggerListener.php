<?php


namespace App\EventListener;


use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class LoggerListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        $log = new Log();
        $log
            ->setIp($request->getClientIp())
            ->setRoute($request->getRequestUri())
            ->setDate(new \DateTime('now'))
        ;

        $this->em->persist($log);
        $this->em->flush();

        $response->headers->set('X-ROUTE-APP', $request->getPathInfo());

    }

}