<?php

namespace App\Controller\Api;

use App\Entity\Customer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CustomerType;
use Symfony\Component\HttpFoundation\Response;

class CustomersController extends ApiBaseController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/customers", methods={"POST"})
     */
    public function create(Request $request)
    {
        $form = $this->createForm(CustomerType::class);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $customer = $form->getData();
        $customer->setCreatedAt(new \DateTime());

        foreach ($customer->getProducts() as $product) {
            $product->setCreatedAt(new \DateTime());
        }

        $this->em->persist($customer);
        $this->em->flush();

        return $this->json([
            'uuid' => $customer->getUuid()
        ], 201);
    }

    /**
     * @Route("/api/customers/{uuid}", methods={"GET"})
     */
    public function getOne($uuid)
    {
        $repository = $this->em->getRepository(Customer::class);

        if (!$customer = $repository->findOneByUuid($uuid)) {
            throw $this->createNotFoundException(sprintf(
                'No customer found with uuid %s',
                $uuid
            ));
        }

        $data = $this->serializeCustomer($customer);

        foreach ($customer->getProducts() as $product) {
            $data['products'][] = $this->serializeProduct($product);
        }

        return $this->json($data);
    }

    /**
     * @Route("/api/customers", methods={"GET"})
     */
    public function listAll()
    {
        $repository = $this->em->getRepository(Customer::class);

        $customers = $repository->findAllNotDeleted();

        $data = ['data' => []];

        foreach ($customers as $customer) {
            $data['data'][] = $this->serializeCustomer($customer);
        }

        return $this->json($data);
    }

    /**
     * @Route("/api/customers/{uuid}", methods={"PUT","PATCH"})
     */
    public function update($uuid, Request $request)
    {
        $repository = $this->em->getRepository(Customer::class);
        $customer   = $repository->findOneByUuid($uuid);

        if (!$customer) {
            throw $this->createNotFoundException(sprintf(
                'No customer found with uuid %s',
                $uuid
            ));
        }

        $form = $this->createForm(CustomerType::class, $customer, ['is_edit' => true]);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $customer->setUpdatedAt(new \DateTime());

        $this->em->persist($customer);
        $this->em->flush();

        $data = $this->serializeCustomer($customer);

        return $this->json($data);
    }

    /**
     * @Route("/api/customers/{uuid}", methods={"DELETE"})
     */
    public function delete($uuid)
    {
        $repository = $this->em->getRepository(Customer::class);
        $customer   = $repository->findOneByUuid($uuid);

        if ($customer) {
            $customer->setStatus('deleted');
            $customer->setDeletedAt(new \DateTime());

            foreach ($customer->getProducts() as &$product) {
                $product->setStatus('deleted');
                $product->setDeletedAt(new \DateTime());
            }

            $this->em->persist($customer);
            $this->em->flush();
        }

        return new Response(null, 204);

    }


}
