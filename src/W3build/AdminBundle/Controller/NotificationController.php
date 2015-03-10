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
 * Class ProfileController
 * @package W3build\AdminBundle\Controller
 *
 * @Route("/admin", service="controller.admin.notification")
 */
class NotificationController {

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
     * @Method("POST")
     * @Route("/get-new-count/", name="route.admin.notification.get_new_count")
     */
    public function getNewCountAction(Request $request){

        return array(
        );
    }

}