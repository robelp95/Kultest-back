<?php

namespace App\Controller;

use App\Entity\PaykuPlan;
use App\Entity\Suscription;
use App\Form\Type\SuscriptionFormType;
use App\Model\DTO\PaykuSuscriptionDto;
use App\Repository\PaykuPlanRepository;
use App\Repository\SuscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Payku\NotifyNewSuscription;
use App\Service\Payku\PaykuCreateSuscription;
use App\Service\Payku\PaykuDeleteSuscription;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaykuController extends AbstractController
{

    private PaykuCreateSuscription $paykuCreateSuscription;
    private PaykuDeleteSuscription $paykuDeleteSuscription;
    private NotifyNewSuscription $notifyNewSuscription;

    /**
     * PaykuController constructor.
     * @param PaykuCreateSuscription $paykuCreateSuscription
     * @param PaykuDeleteSuscription $paykuDeleteSuscription
     * @param NotifyNewSuscription $notifyNewSuscription
     */
    public function __construct(
        PaykuCreateSuscription $paykuCreateSuscription,
        PaykuDeleteSuscription $paykuDeleteSuscription,
        NotifyNewSuscription $notifyNewSuscription
    )
    {
        $this->paykuCreateSuscription = $paykuCreateSuscription;
        $this->paykuDeleteSuscription = $paykuDeleteSuscription;
        $this->notifyNewSuscription = $notifyNewSuscription;
    }

    /**
     * @Rest\Post(path="/users/{id}/suscriptions", name="suscribe_user_to_plan", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     * @param int $id
     * @param UserRepository $userRepository
     * @param PaykuPlanRepository $paykuPlanRepository
     * @param EntityManagerInterface $em
     * @return FormInterface|JsonResponse
     */
    public function suscribeUser(Request $request,
                                 int $id,
                                 UserRepository $userRepository,
                                 PaykuPlanRepository $paykuPlanRepository,
                                 EntityManagerInterface $em){
        $user = $userRepository->find($id);
        if (!$user) return $this->json('', 400);

        $paykuSuscriptionDto = new PaykuSuscriptionDto();
        $form = $this->createForm(SuscriptionFormType::class, $paykuSuscriptionDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            try {

                //Delete active suscription
                $activeSuscriptions = $user->getSuscription()->filter(function (Suscription $sus){
                    return !empty($sus->getStatus());
                });

                foreach ($activeSuscriptions as $suscription){
                    ($this->paykuDeleteSuscription)($suscription);
                    $suscription->setStatus(false);
                    $em->persist($suscription);
                }
                $em->flush();

                $suscriptionDto = ($this->paykuCreateSuscription)($paykuSuscriptionDto);
                $suscription = new Suscription();
                $plan = $paykuPlanRepository->findOneBy(array("paykuId" => $suscriptionDto->getPlanId()));
                $suscription->setPlan($plan);
                $suscription->setCreatedAt(new Datetime());
                $suscription->setUrl($suscriptionDto->getUrl());
                $suscription->setPaykuId($suscriptionDto->getId());
                $suscription->setUser($user);
                $suscription->setStatus($suscriptionDto->getStatus());
                $em->persist($suscription);
                $user->addSuscription($suscription);
                $em->persist($user);
                $em->flush();
                $em->refresh($user);

                ($this->notifyNewSuscription)([
                    "from" => "kulko.app@gmail.com",
                    "dest" => $user->getEmail(),
                    "username" => $user->getName(),
                    "planName" => $plan->getName(),
                    "url" => $suscriptionDto->getUrl()
                ]);



                return $user;
            } catch (\Exception $e) {
                //TODO remove return $e
                return $this->json($e->getMessage() . ' - ' . $e->getTraceAsString(), 400);
                return $this->json(null, Response::HTTP_BAD_REQUEST);
            }
        }
        return $form;

    }

    /**
     * @Rest\Get(path="/payku/plans", name="get_user_suscriptions")
     * @Rest\View(serializerGroups={"plan"}, serializerEnableMaxDepthChecks=true)
     * @param PaykuPlanRepository $paykuPlanRepository
     * @return PaykuPlan[]
     */
    public function getUserSuscriptions(PaykuPlanRepository $paykuPlanRepository){

        return $paykuPlanRepository->findAll();
    }

    /**
     * @Rest\Post(path="/payku/suscription/callback", name="payku_suscription_callback")
     * @param Request $request
     * @param SuscriptionRepository $suscriptionRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function paykuSuscriptionCallback(Request $request,
                                             SuscriptionRepository $suscriptionRepository,
                                             EntityManagerInterface $em){

        $data = json_decode($request->getContent(), true);
        $sus = $suscriptionRepository->findOneBy(array("paykuId" => $data["id"]));
        if (!$sus) return new Response(null, Response::HTTP_BAD_REQUEST);
        $sus->setStatus($data["status"] == "active");
        $em->persist($sus);
        $em->flush();
        return new Response(null, Response::HTTP_OK);

    }
}
