<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 13.1.15
 * Time: 1:27
 */

namespace W3build\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use W3build\Admin\DataGridBundle\DataGrid;
use W3build\AdminBundle\Form\UserType;
use W3build\AdminBundle\Repository\UserRepository;
use W3build\AdminBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use W3build\AdminBundle\Security\Facade\Security;
use W3build\PaginateBundle\Paginate;
use W3build\PaginateBundle\Adapter\QueryBuilder as PaginateQueryBuilder;
use W3build\AdminBundle\Security\Facade\Acl;

/**
 * Class UserController
 * @package W3build\AdminBundle\Controller
 *
 * @Route("/admin/user", service="controller.admin.user")
 */
class UserController {

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var UserType
     */
    private $userType;

    /**
     * @var DataGrid
     */
    private $dataGrid;

    /**
     * @var Paginate
     */
    private $paginate;

    /**
     * @var Security
     */
    private $security;

    /**
     * @param Router $router
     * @param FormFactory $formFactory
     * @param Session $session
     * @param UserRepository $userRepository
     * @param UserType $userType
     * @param DataGrid $dataGrid
     * @param Paginate $paginate
     * @param Security $security
     */
    function __construct(
        Router $router, FormFactory $formFactory, Session $session, UserRepository $userRepository,
        UserType $userType, DataGrid $dataGrid, Paginate $paginate, Security $security
    )
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->userType = $userType;
        $this->dataGrid = $dataGrid;
        $this->paginate = $paginate;
        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("s/", name="route.admin.user.list")
     * @Template()
     */
    public function listAction(Request $request){
        $paginate = $this->paginate->setAdapter(new PaginateQueryBuilder($this->userRepository->adminDataGridQuery()))
            ->setItemsPerPage(15)
            ->setPage($request->get('page', 1));

        $dataGrid =$this->dataGrid->setResults($paginate)
            ->addColumn(new DataGrid\Column('id', 'ID'))
            ->addColumn(new DataGrid\Column('fullName'))
            ->addColumn(new DataGrid\Column('active'))
            ->addAction(new DataGrid\Action\Edit('route.admin.user.edit'))
            ->addAction(new DataGrid\Action\Delete('route.admin.user.delete'))
        ;

        return array(
            'dataGrid' => $dataGrid
        );
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("/add/", name="route.admin.user.add")
     * @Template()
     */
    public function addAction(Request $request){

        $user = new User();

        $form = $this->formFactory->create($this->userType, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($this->security->encodePassword($user));

            $this->userRepository->save($user);

            return new RedirectResponse($this->router->generate('route.admin.user.list'));
        }

        return array('form' => $form->createView());
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     *
     * @Route("/edit/{id}/", name="route.admin.user.edit")
     * @Template("W3buildAdminBundle:User:add.html.twig")
     */
    public function editAction(User $user, Request $request){
        $form = $this->formFactory->create($this->userType, $user);

        $oldPassword = $user->getPassword();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($oldPassword != $user->getPassword()){
                $user->setPassword($this->security->encodePassword($user));
            }

            $this->userRepository->save($user);

            return new RedirectResponse($this->router->generate('route.admin.user.list'));
        }

        return array('form' => $form->createView());
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     *
     * @Route("/delete/{id}/", name="route.admin.user.delete")
     * @Template()
     */
    public function deleteAction(User $user, Request $request){

    }

}