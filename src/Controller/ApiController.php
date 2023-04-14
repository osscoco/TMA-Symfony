<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Repository\TweetRepository;
use App\Entity\User;
use App\Entity\Tweet;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiController extends AbstractController
{
    private UserRepository $userRepository;
    private TweetRepository $tweetRepository;

    public function __construct(UserRepository $userRepository, TweetRepository $tweetRepository) {
        $this->userRepository = $userRepository;
        $this->tweetRepository = $tweetRepository;
    }

    /* * * *              * * * *
     * * * * AUTH - LOGIN * * * *
     * * * *              * * * */
    #[Route('/api/login_check', name: 'api_login_check', methods: 'POST')]
    #[OA\Tag('AUTH')]
    #[OA\Post(
        summary: 'Retourne le token',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['email', 'password'],
                        properties: [
                            new OA\Property(property: "email", type: "string"),
                            new OA\Property(property: "password", type: "string")
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Jeton retourné avec succès !'
    )]
    #[OA\Response(
        response: 401,
        description: 'Identification incorrect'
    )]
    public function index()
    {

    }

    /* * * *             * * * *
     * * * * USERS - ALL * * * *
     * * * *             * * * */
    #[Route('/api/users', name: 'api_users', methods: 'GET')]
    #[OA\Tag('USERS')]
    #[OA\Get(
        summary: 'Retourne les utilisateurs'
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateurs retournées avec succès !',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getAllUsers']))
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur de serveur'
    )]
    #[Security(name: 'Bearer')]
    public function getAllUsers(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->json(
            $users,
            context: ['groups' => ['getAllUsers']]
        );
    }

    /* * * *             * * * *
     * * * * USERS - NEW * * * *
     * * * *             * * * */
    #[Route('/api/users/new', name: 'api_users_new', methods: 'POST')]
    #[OA\Tag('USERS')]
    #[OA\Post(
        summary: 'Créé un utilisateur',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['firstname', 'lastname', 'email', 'password'],
                        properties: [
                            new OA\Property(property: "firstname", type: "string"),
                            new OA\Property(property: "lastname", type: "string"),
                            new OA\Property(property: "email", type: "string"),
                            new OA\Property(property: "password", type: "string")
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur créé avec succès !'
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur de serveur'
    )]
    #[Security(name: 'Bearer')]
    public function storeOneUser(RequestStack $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getMainRequest()->getContent());

        $user = new User();

        $user->setFirstname($data->firstname);
        $user->setLastname($data->lastname);
        $user->setEmail($data->email);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data->password
        );
        $user->setPassword($hashedPassword);
        $user->setDateCreation(new \DateTime('@'.strtotime('now')));
        $user->setDateModification(new \DateTime('@'.strtotime('now')));

        $this->userRepository->save($user, true);

        return $this->json(
            "Utilisateur créé avec succès"
        );
    }

    /* * * *             * * * *
     * * * * USERS - ONE * * * *
     * * * *             * * * */
    #[Route('/api/users/{id}', name: 'api_user_one', methods: 'GET')]
    #[OA\Tag('USERS')]
    #[OA\Get(
        summary: 'Retourne un utilisateur'
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur retourné avec succès !',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getOneUser']))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Id Inconnu'    
    )]
    #[Security(name: 'Bearer')]
    public function getOneUser(User $user): Response
    {
        $userGet = $this->userRepository->findOneById($user->getId());

        return $this->json(
            $userGet,
            context: ['groups' => ['getOneUser']]
        );
    }

    /* * * *             * * * *
     * * * * USERS - PUT * * * *
     * * * *             * * * */
    #[Route('/api/users/{id}', name: 'api_user_put', methods: 'PUT')]
    #[OA\Tag('USERS')]
    #[OA\Put(
        summary: 'Modifier un utilisateur',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['firstname', 'lastname', 'email', 'password'],
                        properties: [
                            new OA\Property(property: "firstname", type: "string"),
                            new OA\Property(property: "lastname", type: "string"),
                            new OA\Property(property: "email", type: "string"),
                            new OA\Property(property: "password", type: "string")
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur modifié avec succès !'
    )]
    #[OA\Response(
        response: 404,
        description: 'Id Inconnu'
    )]
    #[Security(name: 'Bearer')]
    public function putOneUser(RequestStack $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getMainRequest()->getContent());

        $userPut = $this->userRepository->findOneById($user->getId());
        $userPut->setFirstname($data->firstname);
        $userPut->setLastname($data->lastname);
        $userPut->setEmail($data->email);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data->password
        );
        $user->setPassword($hashedPassword);
        $userPut->setDateModification(new \DateTime('@'.strtotime('now')));

        $this->userRepository->save($userPut, true);

        return $this->json(
            "Utilisateur modifié avec succès"
        );
    }

    /* * * *                * * * *
     * * * * USERS - DELETE * * * *
     * * * *                * * * */
    #[Route('/api/users/{id}', name: 'api_user_delete', methods: 'DELETE')]
    #[OA\Tag('USERS')]
    #[OA\Delete(
        summary: 'Supprime un utilisateur'
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur supprimé avec succès !'
    )]
    #[OA\Response(
        response: 404,
        description: 'Id Inconnu'    
    )]
    #[Security(name: 'Bearer')]
    public function deleteOneUser(User $user): Response
    {
        $userDelete = $this->userRepository->findOneById($user->getId());

        $this->userRepository->remove($userDelete, true);

        return $this->json(
            "Utilisateur supprimé avec succès"
        );
    }

    /* * * *             * * * *
     * * * * TWEETS - ALL * * * *
     * * * *             * * * */
    #[Route('/api/tweets', name: 'api_tweets', methods: 'GET')]
    #[OA\Tag('TWEETS')]
    #[OA\Get(
        summary: 'Retourne les tweets'
    )]
    #[OA\Response(
        response: 200,
        description: 'Tweets retournées avec succès !',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Tweet::class, groups: ['getAllTweets']))
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur de serveur'
    )]
    #[Security(name: 'Bearer')]
    public function getAllTweets(): Response
    {
        $tweets = $this->tweetRepository->findAll();

        return $this->json(
            $tweets,
            context: ['groups' => ['getAllTweets']]
        );
    }

    /* * * *             * * * *
     * * * * TWEETS - NEW * * * *
     * * * *             * * * */
    #[Route('/api/tweets/new', name: 'api_tweets_new', methods: 'POST')]
    #[OA\Tag('TWEETS')]
    #[OA\Post(
        summary: 'Créé un tweet',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['label', 'likes'],
                        properties: [
                            new OA\Property(property: "label", type: "string"),
                            new OA\Property(property: "likes", type: "integer")
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Tweet créé avec succès !'
    )]
    #[OA\Response(
        response: 500,
        description: 'Erreur de serveur'
    )]
    #[Security(name: 'Bearer')]
    public function storeOneTweet(RequestStack $request): Response
    {
        $data = json_decode($request->getMainRequest()->getContent());

        $tweet = new Tweet();

        $tweet->setLabel($data->label);
        $tweet->setLikes($data->likes);
        $tweet->setDateCreation(new \DateTime('@'.strtotime('now')));
        $tweet->setDateModification(new \DateTime('@'.strtotime('now')));

        $this->tweetRepository->save($tweet, true);

        return $this->json(
            "Tweet créé avec succès"
        );
    }

    /* * * *             * * * *
     * * * * TWEETS - ONE * * * *
     * * * *             * * * */
    #[Route('/api/tweets/{id}', name: 'api_tweet_one', methods: 'GET')]
    #[OA\Tag('TWEETS')]
    #[OA\Get(
        summary: 'Retourne un tweet'
    )]
    #[OA\Response(
        response: 200,
        description: 'Tweet retourné avec succès !',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['getOneTweet']))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Id Inconnu'    
    )]
    #[Security(name: 'Bearer')]
    public function getOneTweet(Tweet $tweet): Response
    {
        $tweetGet = $this->tweetRepository->findOneById($tweet->getId());

        return $this->json(
            $tweetGet,
            context: ['groups' => ['getOneTweet']]
        );
    }

    /* * * *             * * * *
     * * * * TWEETS - PUT * * * *
     * * * *             * * * */
    #[Route('/api/tweets/{id}', name: 'api_tweet_put', methods: 'PUT')]
    #[OA\Tag('TWEETS')]
    #[OA\Put(
        summary: 'Modifier un tweet',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['label', 'likes'],
                        properties: [
                            new OA\Property(property: "label", type: "string"),
                            new OA\Property(property: "likes", type: "integer")
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Tweet modifié avec succès !'
    )]
    #[OA\Response(
        response: 404,
        description: 'Id Inconnu'
    )]
    #[Security(name: 'Bearer')]
    public function putOneTweet(RequestStack $request, Tweet $tweet): Response
    {
        $data = json_decode($request->getMainRequest()->getContent());

        $tweetPut = $this->tweetRepository->findOneById($tweet->getId());
        $tweetPut->setLabel($data->label);
        $tweetPut->setLikes($data->likes);
        $tweetPut->setDateModification(new \DateTime('@'.strtotime('now')));

        $this->tweetRepository->save($tweetPut, true);

        return $this->json(
            "Utilisateur modifié avec succès"
        );
    }

    /* * * *                * * * *
     * * * * TWEETS - DELETE * * * *
     * * * *                * * * */
    #[Route('/api/tweets/{id}', name: 'api_tweet_delete', methods: 'DELETE')]
    #[OA\Tag('TWEETS')]
    #[OA\Delete(
        summary: 'Supprime un tweet'
    )]
    #[OA\Response(
        response: 200,
        description: 'Tweet supprimé avec succès !'
    )]
    #[OA\Response(
        response: 404,
        description: 'Id Inconnu'    
    )]
    #[Security(name: 'Bearer')]
    public function deleteOneTweet(Tweet $tweet): Response
    {
        $tweetDelete = $this->tweetRepository->findOneById($tweet->getId());

        $this->tweetRepository->remove($tweetDelete, true);

        return $this->json(
            "Tweet supprimé avec succès"
        );
    }
}