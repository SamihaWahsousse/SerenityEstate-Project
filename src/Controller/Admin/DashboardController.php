<?php

namespace App\Controller\Admin;

use App\Entity\CityRegion;
use App\Entity\Operation;
use App\Entity\User;
use App\Entity\PropertyType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_panel')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboardAdmin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // ->setTitle('SerenityEstate Project')
            ->renderContentMaximized()
            // ->setTitle('<img src=""> renityEstate <span class="text-small">Agency.</span>');
            ->setTitle('<img src="../../upload/images/logo.png" width="200" height="100">');


            
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-gauge-high');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Property type', 'fas fa-building', PropertyType::class);
        yield MenuItem::linkToCrud('Operation RealEstate', 'fas fa-exchange', Operation::class);
        yield MenuItem::linkToCrud('City-Region', 'fas fa-city', CityRegion::class);
        yield MenuItem::linkToRoute('Home page', 'fas fa-home','app_home');
    }


public function configureUserMenu(UserInterface $user): UserMenu
    {
    // use the given $user object to get the user name
        if(!$user instanceof User){
            throw new Exception(('Wrong user'));
        }

        return parent::configureUserMenu($user)
            ->setName($user->getFullName())
            ->setAvatarUrl($user->getAvatar())
            ->addMenuItems([MenuItem::linkToUrl('Edit Profile','fas fa-user',$this->generateUrl('app_edit_profile')), //add redirection to Edit Profile Page in login/logout Menu for connected user-admin-
            ]);

    }

// Customize the css syle of easyAdmin pages
public function configureAssets(): Assets
{
    return parent::configureAssets()
    ->addCssFile('assets/css/customizeEA.css')
    ;
}

}