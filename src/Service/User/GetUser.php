<?php

namespace App\Service\User;
use App\Entity\User;
use App\Model\Exception\UserNotFound;
use App\Repository\UserRepository;

class GetUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(string $id): User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            UserNotFound::throwException();
        }
        return $user;
    }
}