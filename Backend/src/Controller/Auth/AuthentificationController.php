<?php

namespace App\Controller\Auth;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthentificationController extends AbstractController
{
    #[Route('/api/login_check',methods:['POST'])]
    public function index(Request $request,UserPasswordHasherInterface $encoder,UserRepository $userRepository,JWTTokenManagerInterface $JWTManager,EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        if(!$data){
            $data=$request->request->all();
        }
        $user = $userRepository->findOneBy(['username'=>$data['username']]);
        if(!$user){
            return new JsonResponse(['message' => "Nom d'utilisateur ou mot de passe incorrecte"], Response::HTTP_UNAUTHORIZED);    
        }
        // if($user->isIsActive()==false){
        //     return new JsonResponse(['message' => "Votre compte à été désactiver, veuillez contacter le support pour plus d'information"], Response::HTTP_UNAUTHORIZED);    
        // }
        if (!$encoder->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => "Nom d'utilisateur ou mot de passe incorrecte"], Response::HTTP_UNAUTHORIZED);    
        }
        return new JsonResponse(['token' => $JWTManager->create($user)]);    
    }
    
}
