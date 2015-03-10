<?php
namespace W3build\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use W3build\AdminBundle\Form\LoginType;
use W3build\AdminBundle\Repository\UserRepository;
use W3build\AdminBundle\Entity\User;

/**
 * Class SecurityController
 * @package W3build\AdminBundle\Controller
 *
 * @Route("/admin", service="controller.admin.security")
 */
class SecurityController {

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var LoginType
     */
    private $loginType;

    /**
     * @param Router $router
     * @param FormFactory $formFactory
     * @param Session $session
     * @param LoginType $loginType
     */
    function __construct(Router $router, FormFactory $formFactory, Session $session, LoginType $loginType)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->loginType = $loginType;

    }


    /**
     * @param Request $request
     * @return array
     *
     * @Route("/login/", name="route.admin.security.login")
     * @Template()
     */
    public function loginAction(Request $request){
        $form = $this->formFactory->create(
            $this->loginType,
            array(
                'email' => $this->session->get(SecurityContext::LAST_USERNAME)
            ),
            array(
                'action' => $this->router->generate('route.admin.security.login.check'),
                'method' => 'POST'
            )
        );

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->session->getFlashBag()->add('error', $error);
        } else {
            $this->session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     *
     * @Method("POST")
     * @Route("/login-check/", name="route.admin.security.login.check")
     * @Template()
     */
    public function checkLogin(Request $request){
    }

    /**
     * @Route("/logout/", name="route.admin.security.logout")
     * @Template()
     */
    public function logoutAction(){
        return array();
    }

}