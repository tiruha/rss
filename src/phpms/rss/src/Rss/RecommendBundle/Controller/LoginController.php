<?php

namespace Rss\RecommendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

use Rss\RecommendBundle\Entity\LoginUser;
use Rss\RecommendBundle\Form\Type\LoginUserType;

class LoginController extends Controller
{
    public function loginAction()
    {
        // セッションに情報がある場合取得
        if (!$this->container->get('session')->has('loginUser')) {
            $this->container->get('session')->set('loginUser', new LoginUser());
        }
        //既にログインしていれば、login処理を行わない
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('rss_recommend_home'));
        }
        // formを作成
        $form = $this->createForm(
            new LoginUserType(), 
            $this->container->get('session')->get('loginUser')
        );
        return $this->render('RssRecommendBundle:Login:login.html.twig', array('form' => $form->createView(), 'error' => null));
    }
    
    public function loginPostAction()
    {
        // log設定
        $logger = $this->get('logger');
        $logger->info("login post action");
        // from作成
        $form = $this->createForm(
            new LoginUserType(), 
            $this->container->get('session')->get('loginUser')
        );
        $request = $this->getRequest();
        $form->bind($request);
        //$formからEntityを取り出す
        $user_data = $form->getData();
        // loginエラーを格納
        $error = null;
        // formのバリデーションチェック
        if ($form->isValid()) {
            $logger->info("バリデーション成功");
            try{
                // LoginUserを介して、DBからデータを検索するリポジトリを作成
                $em = $this->getDoctrine()->getEntityManager();
                $repository = $em->getRepository('RssRecommendBundle:LoginUser');
                if($repository->isUsernameUsable($user_data)){
                    return $this->redirect($this->generateUrl('rss_recommend_newuser'));
                }
            } catch (BadCredentialsException $ex) {
                $error = $ex;
            }
        }
        // 最終的にエラーがあれば、login画面を表示
        return $this->render('RssRecommendBundle:Login:login.html.twig', array('form' => $form->createView(), 'error' => $error));
    }

    public function loginFailureAction()
    {
        // セッションに情報がある場合取得
        if (!$this->container->get('session')->has('loginUser')) {
            $this->container->get('session')->set('loginUser', new LoginUser());
        }
        // リクエストの取得
        $request = $this->getRequest();
        $session = $request->getSession();
        // from作成
        $form = $this->createForm(
            new LoginUserType(), 
            $this->container->get('session')->get('loginUser')
        );
        
        // loginエラーを格納
        $error = null;
        // ログインエラーがあれば、ここで取得
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        // Sessionにエラー情報があるか確認
        } elseif ($session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            // Sessionからエラー情報を取得
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            // 一度表示したらSessionからは削除する
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render('RssRecommendBundle:Login:login.html.twig', array('form' => $form->createView(), 'error' => $error));
    }
    
    public function newUserAction()
    {
        $logger = $this->get('logger');
        $logger->info("新規ユーザ登録画面");
        // from作成
        $form = $this->createForm(
            new LoginUserType(),
            $this->container->get('session')->get('loginUser')
        );
        // 新規登録画面を表示
        return $this->render('RssRecommendBundle:Login:newuser.html.twig', 
            array(
                'form' => $form->createView(),
                'error' => null,
            )
        );
    }

    public function newUserPostAction()
    {
        $logger = $this->get('logger');
        $logger->info("新規ユーザ登録アクション");
        // from作成
        $form = $this->createForm(
            new LoginUserType(),
            $this->container->get('session')->get('loginUser')
        );
        $request = $this->getRequest();
        $form->bind($request);
        //$formからEntityを取り出す
        $user_data = $form->getData();
        // エラーを格納
        $error = null;
        // formのバリデーションチェック
        if ($form->isValid()) {
            $logger->info("バリデーション成功");
            try{
                // LoginUserを介して、DBからデータを検索するリポジトリを作成
                $em = $this->getDoctrine()->getEntityManager();
                $repository = $em->getRepository('RssRecommendBundle:LoginUser');
                // TODO: 取得したメールアドレスかTwitterアカウントに対して送信する。送信後リンク先で登録処理を行う。今回は登録処理をそのまま実行する。
                $repository->createNewUser($user_data, $this);
                return $this->render('RssRecommendBundle:Login:login.html.twig', array('form' => $form->createView(), 'error' => $error));
            } catch (BadCredentialsException $ex) {
                $error = $ex;
            }
        }
        // 最終的にエラーがあれば、新規登録画面を表示
        return $this->render('RssRecommendBundle:Login:newuser.html.twig', 
            array(
                'form' => $form->createView(),
                'error' => $error,
            )
        );
    }

}
