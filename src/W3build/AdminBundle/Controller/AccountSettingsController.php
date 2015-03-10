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
use W3build\AdminBundle\Form\AccountType;
use W3build\AdminBundle\Repository\UserRepository;
use W3build\AdminBundle\Entity\User;
use W3build\AdminBundle\Security\Facade\Security;

/**
 * Class ProfileController
 * @package W3build\AdminBundle\Controller
 *
 * @Route("/admin/settings/account", service="controller.admin.settings.account")
 */
class AccountSettingsController {

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
     * @var AccountType
     */
    private $accountType;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param Router $router
     * @param FormFactory $formFactory
     * @param Session $session
     * @param AccountType $accountType
     */
    function __construct(Router $router, FormFactory $formFactory, Session $session, Security $security, AccountType $accountType, UserRepository $userRepository)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->accountType = $accountType;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }


    /**
     * @param Request $request
     * @return array
     *
     * @Route("/", name="route.admin.settings.account")
     * @Template()
     */
    public function accountAction(Request $request){
        $user = $this->security->getLoggedUser();

        $form = $this->formFactory->create($this->accountType, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($newPassword = $form->get('newPassword')->getData()){
                $user->regenerateSalt();
                $user->setPassword($this->security->encodePassword($user, $newPassword));
            }

            $this->userRepository->save($user);
            $this->session->getFlashBag()->add('success', 'AccountUpdated');
        }

        return array(
            'form' => $form->createView()
        );
    }

}