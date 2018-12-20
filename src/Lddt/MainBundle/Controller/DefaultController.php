<?php

namespace Lddt\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Lddt\MainBundle\Entity\Category;
use Lddt\MainBundle\Entity\Color;
use Lddt\MainBundle\Entity\Comment;
use Lddt\MainBundle\Entity\Draw;
use Lddt\MainBundle\Entity\Tag;
use Lddt\MainBundle\Form\CommentType;
use Lddt\MainBundle\Form\DrawType;
use Lddt\MainBundle\Form\FormHandler;
use Lddt\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //Récupérer topus les dessins
        $draws=$this->get('doctrine')
            ->getRepository('LddtMainBundle:Draw')
            ->findAllDraws();
        //Passage des données à la vue, les clés du tableau associatif deviennent le nom des variables dans les vues twig
        $datas=['draws'=>$draws];
        return $this->render('LddtMainBundle:Default:index.html.twig',$datas);
    }

    /*public function showAction(Request $request)
    {
        //request permet de récupérer les informations des superglobales $_GET.
        $id=$request->get('id');
        $draw=$this->get('doctrine')
            ->getRepository('LddtMainBundle:Draw')
            ->findOneby(array('id'=>$id));
        $datas=['draw'=> $draw];
        return $this->render('LddtMainBundle:Default:show.html.twig',$datas);
    }*/

    /**
     * Ajout manuel du Template
     * Lié au namespace Template, ce qui permet de lié le path du routing avec la méthode et d'envoyer l'instance du path dans la méthode
     * @Template()
     */
    public function showAction(Draw $draw,Request $request){
//    public function showAction(Draw $draw){
        $user=$this->getUser();
        $is_proprio=false;
        if ($user==$draw->getAuthor()){
            $is_proprio=true;
        }
        $datas=['draw'=>$draw,'is_proprio'=>$is_proprio];
        if ($user){
    //        Affichage du formulaire des commentaires
            $commentForm=$this->createForm(CommentType::class,new Comment($draw,$user));
            $datas=['draw'=>$draw,'commentForm'=>$commentForm->createView(),'is_proprio'=>$is_proprio];
            //Insertion des superglobale dans la base
            $em=$this->get('doctrine')->getManager();
            $formHandler= new FormHandler($commentForm,$request,$em);
            if ($formHandler->process()){
    //            var_dump("commentaire chargé");
                return $this->redirect($this->generateUrl('lddt_main_show',['id'=>$draw->getId()]));
            }
        }
        return $datas;
    }

    /*public function createAction()
    {
        //1 instanciation de l'entité Category et hydratation
        $category=new Category();
        $category->setName("Vestimentaire");
        //Création des couleurs
        $color=new Color();
        $color->setName('rouge');
        $color->setCode('FF0000');
        $color2=new Color();
        $color2->setName('bleu');
        $color2->setCode('000FFF');
        $color3=new Color();
        $color3->setName('noir');
        $color3->setCode('000000');
        //1-bis instanciation de l'entité Draw et hydratation
        $draw=new Draw();
        $draw->setTitle('chaussure');
        $draw->setDrawPath('chaussure.jpg');
        $draw->setIsOnline(true);
        $draw->setAuthorName('fanch');
        $draw->setAvatarPath('fanch-ico.jpg');
        $draw->setCreatedAt(new \DateTime());
        $draw->setUpdatedAt(new \DateTime());
        //On lie la catégorie et le dessin
        $draw->setCategory($category);
        //On lie les couleurs au dessin
        $draw->addColor($color);
        $draw->addColor($color2);
        $draw->addColor($color3);
        //2 Appel de l'entity Manager de Doctrine
        $em=$this->get('doctrine')->getManager();
        //On persiste l'instance (on prépare la requête)
        $em->persist($category);
        $em->persist($color);
        $em->persist($color2);
        $em->persist($color3);
        $em->persist($draw);
        //On execute la requête
        $em->flush();
        //renvoyer vers l'alias du route
        return $this->redirect($this->generateUrl('lddt_main_homepage'));
    }*/

    public function createAction(Request $request){
        //Récupérer l'utilisateur connecté (méthode Symfony lié à FOS via la security)
        $author=$this->getUser();
//        var_dump($author);
//        die();
        $datas=[];
        //Appel de l'instance DrawType (pour afficher le formulaire)
        if ($author){
        $form=$this->createForm(DrawType::class,new Draw($author));
        //Variabiliser la vue du formulaire
        $datas=['form'=>$form->createView()];
            //variabiliser l'entité manager
            $em=$this->get('doctrine')->getManager();
            //créer l'instance de prise en main du formulaire'
            $formHandler=new FormHandler($form,$request,$em);
            if ($formHandler->process()){
                $this->addFlash('success','Le dessin a bien été créé, il est en attente de modération');
                return $this->redirect($this->generateUrl('lddt_main_homepage'));
            }
        }
        return $this->render('LddtMainBundle:Default:create.html.twig',$datas);
    }

    public function deleteAction(Draw $draw){
        if (!$this->checkAuthorization($draw->getAuthor())){
            return $this->redirect($this->generateUrl('lddt_main_homepage'));
        }
        $em=$this->get('doctrine')->getManager();
        $em->remove($draw);
        $em->flush();
        $this->addFlash('success','Le dessin a bien été supprimé');
        return $this->redirect($this->generateUrl('lddt_main_homepage'));
    }

    public function editAction(Draw $draw,Request $request){
        if (!$this->checkAuthorization($draw->getAuthor())){
            return $this->redirect($this->generateUrl('lddt_main_homepage'));
        }
        $form=$this->createForm(DrawType::class,$draw);
        $datas=['form'=>$form->createView(),'draw'=>$draw];
        //variabiliser l'entité manager
        $em=$this->get('doctrine')->getManager();
        //créer l'instance de prise en main du formulaire'
        $formHandler=new FormHandler($form,$request,$em);
        if ($formHandler->process()){
            $this->addFlash('success','Le dessin a bien été modifié');
            return $this->redirect($this->generateUrl('lddt_main_homepage'));
        }
         return $this->render('LddtMainBundle:Default:edit.html.twig',$datas);
    }

    /**
     * @Template("LddtMainBundle:Default:index.html.twig")
     * @param Category $category
     */
    public function listDrawsByCatAction(Category $category){
        //Req dql pour filtrer les dessins d'une catégorie
             $draws=$this->get('doctrine')->getRepository("LddtMainBundle:Draw")->findAllDrawsByCat($category);
              $datas=['draws'=>$draws,'category'=>$category];
             //Retour des datas au template ci-dessus
             return $datas;
    }

    /**
     * @Template("LddtMainBundle:Default:index.html.twig")
     * @param Category $category
     */
    public function listDrawsByAuthorAction(User $user){
        //Req dql pour filtrer les dessins d'une catégorie
        $draws=$this->get('doctrine')->getRepository("LddtMainBundle:Draw")->findAllDrawsByAuthor($user);
        $datas=['draws'=>$draws,'user'=>$user];
        //Retour des datas au template ci-dessus
        return $datas;
    }

    /**
     * @Template("LddtMainBundle:Default:index.html.twig")
     * @param Color $color
     */
    public function listDrawsByColorAction(Color $color){
        $draws=$this->get('doctrine')->getRepository("LddtMainBundle:Draw")->findAllDrawsByColor(array($color->getName()));
        $datas=['draws'=>$draws,'color'=>$color];
        return $datas;
    }

    /**
     * @Template("LddtMainBundle:Default:index.html.twig")
     * @param Tag $tag
     */
    public function listDrawsByTagAction(Tag $tag){
        $draws=$this->get('doctrine')->getRepository("LddtMainBundle:Draw")->findAllDrawsByTag(array($tag->getName()));
        $datas=['draws'=>$draws,'tags'=>$tag];
        return $datas;
    }

    private function checkAuthorization($instance){
        //Return true si le user est admin
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return true;
        }
        //Return true si le user est proprio
        elseif ($this->getUser()==$instance){
            return true;
        }
        //Return redirect vers URI si le user non autorisé
        else{
            return false;
        }
    }
}
