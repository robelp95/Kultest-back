<?php


namespace App\Serializer;


use App\Entity\Product;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductNormalizer implements ContextAwareNormalizerInterface
{

    private ObjectNormalizer $normalizer;
    private UrlHelper $urlHelper;

    public function __construct(
        ObjectNormalizer $objectNormalizer,
        UrlHelper $urlHelper
    ){
        $this->normalizer = $objectNormalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($product, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($product, $format, $context);
        if (!empty($product->getImage())){
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/' . $product->getImage());
        }
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Product;
    }


}