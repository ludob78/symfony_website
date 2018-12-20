<?php
/**
 * Created by PhpStorm.
 * User: ludob
 * Date: 26/06/2017
 * Time: 14:44
 */

namespace Lddt\MainBundle\Controller;
use Lddt\MainBundle\Entity\Draw;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(){
        $draws=$this->get('doctrine')->getRepository('LddtMainBundle:Draw')->findAllDrawsToPushOnline();
        $datas=['draws'=>$draws];
        return $datas;
    }

    public function pushOnlineAction(Draw $draw){
        $draw->setIsOnline(true);
        $em=$this->get('doctrine')->getManager();
        $em->persist($draw);
        $em->flush();
        $this->addFlash('success','le dessin '.$draw->getTitle().' est en ligne');
        //Envoi du mail de confirmation de mise en ligne
        $message=\Swift_Message::newInstance()
            ->setSubject("Votre dessin {$draw->getTitle()}")
            ->setFrom("lbarjonnet@gmail.com")
            ->setTo($draw->getAuthor()->getEmail())
            ->setBody($this->renderView('LddtMainBundle:Emails:confirmation_online.html.twig',array('draw'=>$draw)),'text/html');
            $this->get('mailer')->send($message);
        return $this->redirect($this->generateUrl('lddt_admin_homepage'));
    }

    public function pushOfflineAction(Draw $draw){
//        $draw->setIsOnline(false);
        $em=$this->get('doctrine')->getManager();
        $em->remove($draw);
        $em->flush();
        $this->addFlash('success','le dessin '.$draw->getTitle().' n\'a pas été mis en ligne');
        //Envoi du mail de confirmation de suppression en ligne
        $message=\Swift_Message::newInstance()
            ->setSubject("Votre dessin {$draw->getTitle()}")
            ->setFrom("lbarjonnet@gmail.com")
            ->setTo($draw->getAuthor()->getEmail())
            ->setBody($this->renderView('LddtMainBundle:Emails:confirmation_offline.html.twig',array('draw'=>$draw)),'text/html');
        $this->get('mailer')->send($message);
        return $this->redirect($this->generateUrl('lddt_admin_homepage'));
    }
}