<?php
/**
 * ConfirmController.
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\ConfirmType;

/**
 * Confirm controller.
 */
class ConfirmController extends Controller
{
    /**
     * Confirm action.
     *
     * @param Symfony\Component\HttpFoundation\Request $request Request
     * @param array                                    $args    Arguments
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse HTTP Response
     */
    public function confirmAction(Request $request, array $args = [])
    {
        $form = $this->createForm(ConfirmType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // call the callback
            $args['confirmCallback']($args['confirmCallbackArgs']);

            return $this->redirect($args['targetUrl']);
        }

        return $this->render(
            'confirm/confirm.html.twig',
            [
                'form' => $form->createView(),
                'message' => $args['message'],
                'cancel_url' => $args['cancelUrl'],
            ]
        );
    }
}
