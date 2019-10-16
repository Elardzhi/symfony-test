<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Form\CustomerType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends ApiBaseController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/products", methods={"POST"})
     */
    public function create(Request $request)
    {
        $form = $this->createForm(ProductType::class);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $product = $form->getData();
        $product->setCreatedAt(new \DateTime());

        $this->em->persist($product);
        $this->em->flush();

        return $this->json([
            'issn' => $product->getIssn()
        ], 201);
    }

    /**
     * @Route("/api/products/{issn}", methods={"GET"})
     */
    public function getOne($issn)
    {
        $repository = $this->em->getRepository(Product::class);

        if (!$product = $repository->findOneByIssn($issn)) {
            throw $this->createNotFoundException(sprintf(
                'No product found with uuid %s',
                $issn
            ));
        }

        $data = $this->serializeProduct($product);

        return $this->json($data);
    }

    /**
     * @Route("/api/products", methods={"GET"})
     */
    public function listAll()
    {
        $repository = $this->em->getRepository(Product::class);

        $products = $repository->findAllNotDeleted();

        $data = ['data' => []];

        foreach ($products as $product) {
            $data['data'][] = $this->serializeProduct($product);
        }

        return $this->json($data);
    }

    /**
     * @Route("/api/products/{issn}", methods={"PUT","PATCH"})
     */
    public function update($issn, Request $request)
    {
        $repository = $this->em->getRepository(Product::class);
        $product    = $repository->findOneByIssn($issn);

        if (!$product) {
            throw $this->createNotFoundException(sprintf(
                'No product found with issn %s',
                $issn
            ));
        }

        $body         = $request->getContent();
        $requestData  = json_decode($body, true);
        $clearMissing = $request->getMethod() != 'PATCH';

        $form = $this->createForm(ProductType::class, $product, ['is_edit' => true]);
        $form->submit($requestData, $clearMissing);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $product->setUpdatedAt(new \DateTime());

        $this->em->persist($product);
        $this->em->flush();

        $data = $this->serializeProduct($product);

        return $this->json($data);
    }

    /**
     * @Route("/api/products/{issn}", methods={"DELETE"})
     */
    public function delete($issn)
    {
        $repository = $this->em->getRepository(Product::class);
        $product    = $repository->findOneByIssn($issn);

        if ($product) {
            $product->setStatus('deleted');
            $product->setDeletedAt(new \DateTime());

            $this->em->persist($product);
            $this->em->flush();
        }

        return new Response(null, 204);

    }

}
