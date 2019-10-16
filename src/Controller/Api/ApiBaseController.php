<?php

namespace App\Controller\Api;

use App\Entity\Customer;
use App\Entity\Product;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiBaseController extends AbstractController
{
    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    protected function serializeCustomer(Customer $customer)
    {
        return [
            'uuid'        => $customer->getUuid(),
            'firstName'   => $customer->getFirstName(),
            'lastName'    => $customer->getLastName(),
            'dateOfBirth' => $customer->getDateOfBirth(),
            'status'      => $customer->getStatus(),
            'createdAt'   => $customer->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    protected function serializeProduct(Product $product)
    {
        return [
            'issn'      => $product->getIssn(),
            'name'      => $product->getName(),
            'status'    => $product->getStatus(),
            'customer'  => $product->getCustomer()->getUuid(),
            'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    protected function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);

        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }
}
