<?php


namespace App\Service\User;


use App\Repository\SuscriptionRepository;
use App\Repository\UserRepository;
use App\Service\Payku\PaykuGetSuscriptionStatus;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUserStatus
{
    private UserRepository $userRepository;
    private PaykuGetSuscriptionStatus $paykuGetSuscriptionStatus;
    /**
     * @var SuscriptionRepository
     */
    private SuscriptionRepository $suscriptionRepository;

    public function __construct(UserRepository $userRepository,
                                PaykuGetSuscriptionStatus $paykuGetSuscriptionStatus, 
                                SuscriptionRepository $suscriptionRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->paykuGetSuscriptionStatus = $paykuGetSuscriptionStatus;
        $this->suscriptionRepository = $suscriptionRepository;
        $this->em = $em;
    }

    public function __invoke($userId): Bool
    {

        $user = $this->userRepository->find($userId);

        $suscriptionId = null;
        $array = $user->getActiveSuscription();
        $suscriptionId = count($array)>0 ? $array->first()->getPaykuId() : null;

        if (!$suscriptionId) return true;

        try {
            $status = ($this->paykuGetSuscriptionStatus)($suscriptionId);
            $sus = $this->suscriptionRepository->findOneBy(array("paykuId" => $suscriptionId));
            if (!$sus) return true;
            $sus->setStatus($status == "active");
            $this->em->persist($sus);
            $this->em->flush();
        } catch (\Exception $e) {
        }
        return true;
    }
}