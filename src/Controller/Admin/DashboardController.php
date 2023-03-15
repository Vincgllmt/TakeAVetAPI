<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Agenda;
use App\Entity\Animal;
use App\Entity\AnimalRecord;
use App\Entity\Appointment;
use App\Entity\MediaObject;
use App\Entity\NewsletterEntry;
use App\Entity\Receipt;
use App\Entity\Thread;
use App\Entity\ThreadReply;
use App\Entity\TypeAnimal;
use App\Entity\TypeAppointment;
use App\Entity\Unavailability;
use App\Entity\User;
use App\Entity\Vacation;
use App\Entity\Vaccine;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Take\'A\'Vet Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('FAQ');
        yield MenuItem::linkToCrud('Questions', 'fa fa-database', Thread::class);
        yield MenuItem::linkToCrud('Messages', 'fa fa-database', ThreadReply::class);
        yield MenuItem::section('Animals');
        yield MenuItem::linkToCrud('Types', 'fa fa-database', TypeAnimal::class);
        yield MenuItem::linkToCrud('Animal', 'fa fa-database', Animal::class);
        yield MenuItem::linkToCrud('Enregistrement', 'fa fa-database', AnimalRecord::class);
        yield MenuItem::linkToCrud('Vaccin', 'fa fa-database', Vaccine::class);
        yield MenuItem::section('Agenda');
        yield MenuItem::linkToCrud('Agenda', 'fa fa-database', Agenda::class);
        yield MenuItem::linkToCrud('Indisponibilité', 'fa fa-database', Unavailability::class);
        yield MenuItem::linkToCrud('Vacance', 'fa fa-database', Vacation::class);
        yield MenuItem::linkToCrud('Types de rendez-vous', 'fa fa-database', TypeAppointment::class);
        yield MenuItem::linkToCrud('Rendez-vous', 'fa fa-database', Appointment::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Tout les utilisateurs', 'fa fa-database', User::class);
        yield MenuItem::linkToCrud('Adresses', 'fa fa-database', Address::class);
        yield MenuItem::linkToCrud('Receipt', 'fa fa-database', Receipt::class);
        yield MenuItem::section('Media & Autres');
        yield MenuItem::linkToCrud('Media', 'fa fa-photo-film', MediaObject::class);
        yield MenuItem::linkToCrud('Newsletter', 'fa fa-newspaper', NewsletterEntry::class);
        yield MenuItem::section('Admin');

        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $avatarUrl = ($user instanceof User && ($avatarObj = $user->getAvatar()) !== null)
                        ? $this->storage->resolveUri($avatarObj, 'file')
                        : 'media/default-avatar.png';

        return parent::configureUserMenu($user)
            ->setAvatarUrl($avatarUrl)
            ->addMenuItems([
                MenuItem::linkToRoute('Mon profil', 'fa fa-id-card', 'app_me'),
            ]);
    }
}
