<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 16.1.15
 * Time: 6:26
 */

namespace W3build\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use W3build\AdminBundle\Security\Facade\Security;

/**
 * Class DashboardController
 * @package W3build\AdminBundle\Controller
 *
 * @Route("/admin", service="controller.admin.dashboard")
 */
class DashboardController {

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
     * @var Security
     */
    private $security;

    /**
     * @param Router $router
     * @param FormFactory $formFactory
     * @param Session $session
     * @param Security $security
     */
    function __construct(Router $router, FormFactory $formFactory, Session $session, Security $security)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->security = $security;
    }

    /**
     * @Route("/", name="route.admin.dashboard")
     * @Template()
     */
    public function indexAction(){
        return array();
    }



}