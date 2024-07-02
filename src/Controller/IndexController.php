<?php

namespace App\Controller;

use App\Service\MailServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var MailServiceInterface
     */
    protected MailServiceInterface $mailService;

    /**
     * @param MailServiceInterface $mailService
     */
    public function __construct(MailServiceInterface $mailService)
    {
        $this->response = new Response();
        $this->mailService = $mailService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'index', methods: ['POST'])]
    public function actionIndex(Request $request): Response
    {
        try {
            $users = $request->toArray();

            $cnt = count($users);

            if ($cnt < 4) {
                throw new \LengthException('Минимальное кол-во участников: 4, в списке участников: ' . $cnt);
            }

            shuffle($users);

            $offsetCnt = $cnt-1;

            for($i = 0; $i <= $offsetCnt; $i++) {
                $receiver = ($i === $offsetCnt) ? 0 : $i+1;
                $this->sendMsg(santa: $users[$i], receiver: $users[$receiver]);
            }

            $this->response->headers->set('Content-Type', 'text/html');
            $this->response->setStatusCode(Response::HTTP_OK);
            $this->response->setContent('Игра началась !');
        }
        catch (\Exception $e) {
            $this->response->setContent($e->getMessage());
        }

        return $this->response->send();
    }

    /**
     * @param array $santa
     * @param array $receiver
     * @return void
     */
    protected function sendMsg(array $santa, array $receiver): void
    {
        $subject = "Тайный Санта !";
        $msg = "{$santa['fullName']} поздравляем! Вы тайный Санта для: {$receiver['fullName']}, {$receiver['email']}";
        $this->mailService->sendEmail(email: $santa['email'], subject: $subject, msg: $msg);
    }
}