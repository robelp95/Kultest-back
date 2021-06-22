<?php
namespace App\Service\User;

use App\Entity\Menu;
use App\Entity\User;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Menu\MenuManager;
use App\Service\Payku\PaykuCreateClient;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserFormProcessor
{

    private UserRepository $userRepository;
    private EntityManagerInterface $em;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;
    private UserManager $userManager;
    private MenuManager $menuManager;
    private PaykuCreateClient $paykuCreateClient;
    private GetUser $getUser;
    /*
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param FileUploader $fileUploader
     */


    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $em,
                                FileUploader $fileUploader,
                                FormFactoryInterface $formFactory,
                                UserManager $userManager,
                                MenuManager $menuManager,
                                PaykuCreateClient $paykuCreateClient,
                                GetUser $getUser
    )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->menuManager = $menuManager;
        $this->paykuCreateClient = $paykuCreateClient;
        $this->getUser = $getUser;

    }

    public function __invoke(Request $request, $userId = null): array
    {

        $user = null;
        $userDto = null;
        $coin = $this->userManager->findDefaultCoin();
        $category = null;
        $orderVia = null;


        if ($userId === null) {
            $userDto = UserDto::createEmpty();
        } else {
            $user = ($this->getUser)($userId);
            $userDto = UserDto::createFromUser($user);
        }



        $form = $this->formFactory->create(UserFormType::class, $userDto);



        $form->handleRequest($request);

        if ($userId === null){
            $category = $this->userManager->findDefaultCategory();
            $orderVia = $this->userManager->findDefaultOrderVia();
        }else{
            $orderVia = $this->userManager->findOrderVia($userDto->orderViaId);
            $category = $this->userManager->findCategory($userDto->categoryId);
        }



        if (!$form->isSubmitted()) return [null, 'Form is not submitted'];
        if (!$form->isValid()) return [null, $form];


        $menu = new Menu();

        $filename = null;
        if ($userDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($userDto->base64Image, 'user_');
        }

        if ($user === null){
            $user = User::create(
                $userDto->address,
                $userDto->brandName,
                $category,
                $orderVia,
                $coin,
                $userDto->deliveryCharge,
                $userDto->description,
                $userDto->email,
                $userDto->name,
                $userDto->minDelivery,
                $userDto->open,
                $userDto->opening,
                $userDto->paymentInstructions,
                $userDto->phoneNumber,
                $userDto->username,
                $menu,
                $filename
            );

            try {
                $paykuClientDto = ($this->paykuCreateClient)($user->getName(), $user->getEmail(), $user->getPhoneNumber());
                $user->setPaykuId($paykuClientDto->getPaykuId());
            }catch (\Exception $e){
                //TODO log create client error
                return [null, 'Payment service error'];
            }
            $this->menuManager->persist($menu);
        }else{
            $user->update(
                $userDto->address,
                $userDto->brandName,
                $category,
                intval($userDto->deliveryCharge),
                $userDto->description,
                $userDto->name,
                intval($userDto->minDelivery),
                intval($userDto->open),
                $userDto->opening,
                $userDto->paymentInstructions,
                $userDto->phoneNumber,
                $filename === null ? $user->getImage() : $filename
            );
        }
        $this->userManager->save($user);
        $this->userManager->reload($user);
        return [$user, null];
    }
}