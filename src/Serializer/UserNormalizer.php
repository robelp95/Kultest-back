<?php


namespace App\Serializer;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface
{

    private ObjectNormalizer $normalizer;
    private UrlHelper $urlHelper;
    private EntityManagerInterface $em;

    public function __construct(
        ObjectNormalizer $objectNormalizer,
        UrlHelper $urlHelper,
        EntityManagerInterface $em
    ){
        $this->normalizer = $objectNormalizer;
        $this->urlHelper = $urlHelper;
        $this->em = $em;
    }

    public function normalize($user, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($user, $format, $context);
        if (!empty($user->getImage())){
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/' . $user->getImage());
        }
        $data['status'] = $user->getStatus();
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User;
    }


}