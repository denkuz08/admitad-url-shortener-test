<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Url;
use App\Form\UrlType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Rest\Route("/api/url")
 */
class UrlController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/create.{_format}", name="api_url_create", defaults={"_format"="json"})
     * @Rest\RequestParam(
     *     name="url",
     *     nullable=false,
     *     requirements={
     *         @Assert\Url(
     *             protocols=App\Entity\Url::URL_FIELD_POSSIBLE_PROTOCOLS,
     *             relativeProtocol=App\Entity\Url::URL_FIELD_ALLOW_RELATIVE_PROTOCOL,
     *             message=App\Entity\Url::URL_FIELD_ERROR_MSG
     *         )
     *     }
     * )
     * @Rest\RequestParam(
     *     name="shortCode",
     *     nullable=true,
     *     requirements={
     *          @Assert\Length(min=1, max=App\Entity\Url::SHORT_CODE_FIELD_MAX_LENGTH),
     *          @Assert\Regex(
     *              App\Entity\Url::SHORT_CODE_FIELD_REGEX,
     *              message=App\Entity\Url::SHORT_CODE_FIELD_REGEX_ERROR_MSG
     *          )
     *     }
     * )
     * @Rest\View()
     */
    public function create(ParamFetcher $paramFetcher, EntityManagerInterface $em)
    {
        $url = new Url();

        $form = $this->createForm(UrlType::class, $url);
        $form->submit($paramFetcher->all(true));

        if (!$form->isValid()) {
            return $form;
        }

        $em->persist($url);
        $em->flush();

        return [
            'url' => $url->getUrl(),
            'short_code' => $url->getShortCode(),
            'short_url' => $this->generateUrl(
                'redirect_url',
                ['shortCode' => $url->getShortCode()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];
    }
}
