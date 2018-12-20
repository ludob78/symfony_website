<?php
/**
 * Created by PhpStorm.
 * User: ludob
 * Date: 27/06/2017
 * Time: 11:55
 */

namespace Lddt\MainBundle\Controller;
use Lddt\MainBundle\Entity\Draw;
use Lddt\MainBundle\Entity\Pic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InstagramController extends Controller
{
    //Récupérer les dessins postés sur Instagram avec le #lesdessinsdutelephone
    public function fetchDrawAction(){
        //Récupérer l'id d'un compte instagram
        //https://www.instagram.com/lesdessinsdutelephone/?__a=1
        //Récupérer les médias d'un user
        // $endpoint_url = "https://api.instagram.com/v1/users/1463064591/media/recent/?access_token=1463064591.7b97e10.25ef17cf38374b40b7017c4d4ccd57a3";
        //Vérifier si le User a déjà un instagram ID afin de ne pas resoliciter l'API pour cette requête

        $client=new \GuzzleHttp\Client();
        if (empty($this->getUser()->getInstagramId())){
            //Si pas d'id on fait la requête API
            $response=$client->request('GET',"https://www.instagram.com/{$this->getUser()->getInstagramUsername()}/?__a=1");
            $json=$response->getBody();
            $json_tab=json_decode($json,true);
            $id_client_instagram=$json_tab['user']['id'];
            $this->getUser()->setInstagramId($id_client_instagram);
            $em=$this->get('doctrine')->getManager();
            $em->persist($this->getUser());
            $em->flush();
        }
//        $client->setDefaultOption('verify', 'D:\web\wamp64\www\FormationDL\PHP\cacert.pem');
        $endpoint_url = "https://api.instagram.com/v1/users/{$this->getUser()->getInstagramId()}/media/recent/?access_token=1463064591.7b97e10.25ef17cf38374b40b7017c4d4ccd57a3";
        $response=$client->request('GET',$endpoint_url);
        $json=$response->getBody();
        $json_tab=json_decode($json,true);

        $nb_draws=true;
        //On parcourt le tableau de résultat
        foreach ($json_tab['data'] as $img){
            $id_img=$img['id'];
            $tags_img=$img['tags'];
            if (in_array("lesdessinsdutelephone",$tags_img)){
                //On vérifie si l'img n'est pas déjà importée dans notre db
//                echo $img["images"]["standard_resolution"]["url"].'<br>';
//                echo $id_img.'<br>';
                $draw_instagram=$this->get('doctrine')->getRepository('LddtMainBundle:Draw')->findOneBy(['idInstagram'=>$id_img]);
                if (count($draw_instagram)==0){
                    //On crée un dessin dans la table
                    $draw=new Draw($this->getUser());
//                    $draw->setDrawPath($img["images"]["standard_resolution"]["url"]);
                    $pic=new Pic();
                    $pic->setPath($img["images"]["standard_resolution"]["url"]);
                    $draw->setPic($pic);
                    $draw->setIdInstagram($id_img);
                    $cat=$this->get('doctrine')->getRepository('LddtMainBundle:Category')->findOneBy(['id'=>5]);
                    $draw->setCategory($cat);
                    $em=$this->get('doctrine')->getManager();
                    $em->persist($draw);
                    $em->persist($pic);
                    $em->flush();
                }
            }
            else{
                $nb_draws=false;
            }

        }
        if ($nb_draws==false){
            $this->addFlash("info","Aucun dessin Instagram a récupéré");
        }
        else{
            $this->addFlash("success","Les dessins ont été récupérés dans votre compte Instagram, ils sont en attente de modération");
        }

//        return $this->render('LddtMainBundle:Instagram:debug.html.twig',['json'=>$json_tab]);
    return $this->redirect($this->generateUrl('lddt_main_homepage'));
    }
}