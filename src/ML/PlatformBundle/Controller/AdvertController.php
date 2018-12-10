<?php

namespace ML\PlatformBundle\Controller;

use ML\PlatformBundle\Entity\Advert;
use ML\PlatformBundle\Entity\Image;
use ML\PlatformBundle\Entity\AdvertSkill;
use ML\PlatformBundle\Entity\Application;
use ML\PlatformBundle\Form\AdvertType;
use ML\PlatformBundle\Form\AdvertEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AdvertController extends Controller
{
  public function indexAction($page)
  {
    if ($page < 1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }
    $listAdverts = array(
      array(
        'title' => 'Vente meuble IKEA',
        'id' => 1,
        'author' => 'Alexandre',
        'content' => 'meuble kallax...dimension...blablabla',
        'date' => new \DateTime()),
      array(
        'title' => 'Vente meuble salon',
        'id' => 2,
        'author' => 'Alexandre',
        'content' => 'meuble salon...dimension...blablabla',
        'date' => new \DateTime())
    );

    return $this->render('@MLPlatform/Advert/index.html.twig', array(
  'listAdverts' => $listAdverts
    ));

    // On a donc accès au conteneur :
   $mailer = $this->container->get('mailer');
   // On peut envoyer des e-mails, etc.
  }

  public function viewAction($id)
  {
    // On récupère le repository
   $em = $this->getDoctrine()->getManager();
   // on recupere l'annonce $id
   $advert = $em->getRepository('MLPlatformBundle:Advert')->find($id);

   // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
   // ou null si l'id $id  n'existe pas, d'où ce if :
   if (null === $advert) {
     throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
   }

   // on récupère la list des annonces
   $listApplications = $em
      ->getRepository('MLPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
      ;
    return $this->render('MLPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert,
      'listApplications' => $listApplications
    ));
  }

/*************************************************************************/
//            Add action
/*************************************************************************/
  public function addAction(Request $request)
  {
    // Création de l'entité Advert
    $advert = new Advert();

    //On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->create(AdvertType::class, $advert);
    //Methode raccourcie :
    // $form = $this->createForm(AdvertType::class, $advert)

    // Si la requete est en POST
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

      //$advert->getImage()->upload();
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'annonce bien enregistrée.');

        //On redirige vers la page de visualisation de l'annonce nouvellement crée
      return $this->redirectToRoute('ml_platform_view', array('id' => $advert->getId()));
    }

    // On passe la méthode createView() du formulaire à la vue
    // Afin qu'elle puisse afficher le formumlaire toute seule
    return $this->render('MLPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));

  }

  /*************************************************************************/
  //          Edit action >>>> Modifier l'annonce
  /*************************************************************************/

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

     // On récupère l'annonce $id
     $advert = $em->getRepository('MLPlatformBundle:Advert')->find($id);

     if (null === $advert) {
       throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
     }

     $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
    }

    return $this->render('MLPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));

  }

  /*************************************************************************/
  //          Delete action >>>> Supprimer l'annonce
  /*************************************************************************/

  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('MLPlatformBundle:Advert')->find($id);

    if (null === $advert) {
         throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // formulaire vide qui ne continedra sue le champ CSRF
    // cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirectToRoute('ml_platform_homepage');
    }

    return $this->render('MLPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert,
      'form' => $form->createView(),
    ));
  }

  /*************************************************************************/
  //         Menu action >>>> affichage du menu
  /*************************************************************************/
  public function menuAction()
  {

    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listAdverts = array(
      array('id' => 2, 'title' => 'Vente meuble ikea'),
      array('id' => 5, 'title' => 'Vente vélo Lapierre'),
      array('id' => 9, 'title' => 'Offre service ménage à domicile')
    );

    return $this->render('MLPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
  }

  public function testAction()
  {
    $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('MLPlatformBundle:Advert')
    ;

    $listAdverts = $repository->myFindAll();
  }

}
