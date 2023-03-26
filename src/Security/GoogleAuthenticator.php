<?php
namespace App\Security;

use App\Entity\User; // your user entity

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $router;
    private $repository;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router,UserRepository $repository)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);
              
                $email = $googleUser->getEmail();

                // 1) have they logged in with Facebook before? Easy!
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);

                if ($existingUser) {
                    return $existingUser;
                }
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if(!$user){

                    $user = new User();
                    $user->setUsername($googleUser->getName());
                    $user->setEmail($googleUser->getEmail());
                    $user->setFirstName($googleUser->getLastname());
                    $user->setSecondName($googleUser->getName());
                    $user->setPhone(0);
                    $user->setDateJoin(new \DateTime('now'));
                    $user->setBanned(0);
                    $user->setGoogleId($googleUser->getId());
                    $user->setRoles(['ROLE_USER']);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    return $user;
                }else{
                    $user->setGoogleId($googleUser->getId());
                    $this->entityManager->flush();

                    return $user;
                }
          
        
                          
                
                
                
          
                
      
            })
        );

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('client');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}

















// namespace App\Security;
// use Psr\Log\LoggerInterface;
// use App\Entity\User;
// use App\Event\LoginSuccess;
// use App\Repository\UserRepository;
// use App\Service\TargetPathResolver;
// use DateTime;
// use Doctrine\ORM\EntityManagerInterface;
// use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
// use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
// use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
// use Symfony\Component\EventDispatcher\EventDispatcherInterface;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Exception\AuthenticationException;
// use Symfony\Component\Security\Core\User\UserProviderInterface;
// use Symfony\Component\Security\Http\Authenticator\Debug\LoggerAuthenticatorDecorator;

// class GoogleAuthenticator extends SocialAuthenticator 
// {
//     public const REGISTER_WITH_GOOGLE = 'google';

//     private $clientRegistry;
//     private $entityManager;
//     private $eventDispatcher;
//     private $targetPathResolver;
//     private $urlGenerator;
//     private $userRepository;
//     private $logger;
//     public function __construct(
//         ClientRegistry $clientRegistry,
//         EntityManagerInterface $entityManager,
//         EventDispatcherInterface $eventDispatcher,
//         TargetPathResolver $targetPathResolver,
//         UrlGeneratorInterface $urlGenerator,
//         UserRepository $userRepository,
//         LoggerInterface $logger
//     ) {
//         $this->clientRegistry = $clientRegistry;
//         $this->entityManager = $entityManager;
//         $this->eventDispatcher = $eventDispatcher;
//         $this->targetPathResolver = $targetPathResolver;
//         $this->urlGenerator = $urlGenerator;
//         $this->userRepository = $userRepository;
//         $this->logger = $logger;
//     }

//     public function start(
//         Request $request,
//         AuthenticationException $authException = null
//     ): RedirectResponse {
//         return new RedirectResponse(
//             '/connect/google',
//             Response::HTTP_TEMPORARY_REDIRECT
//         );
//     }

//     public function supports(Request $request): bool
//     {
//         return 'google_auth' === $request->attributes->get('_route');
//     }

//     public function getCredentials(Request $request)
//     {
//         return $this->fetchAccessToken($this->getGoogleClient());
//     }

//     public function getUser($credentials, UserProviderInterface $userProvider)
//     {
//         $googleUser = $this->getGoogleClient()->fetchUserFromToken($credentials);
//         $email = $googleUser->getEmail();

//         $existingUser = $this->userRepository->findOneBy(['googleId' => $googleUser->getId()])
//             ?? $this->userRepository->findOneBy(['email' => $email]);

//         $authUser = $existingUser ?? new User();

//         $authUser->setEmail($email);
//          $authUser->setGoogleId($googleUser->getId());
//          $authUser->setAuthType(self::REGISTER_WITH_GOOGLE);

//         if(!$existingUser) {
//             $authUser->setFirstName($googleUser->getName());
//             $authUser->setRoles(['ROLE_USER']);
//             $authUser->setDateJoin(new DateTime());

//             $this->entityManager->persist($authUser);
//         }

//         $this->entityManager->flush();

//         return $authUser;
//     }

//     public function getGoogleClient(): OAuth2ClientInterface
//     {
//         return $this->clientRegistry->getClient('google');
//     }

//     public function supportsRememberMe(): bool
//     {
//         return true;
//     }

//     public function onAuthenticationFailure(
//         Request $request,
//         AuthenticationException $exception
//     ) {
//         return new RedirectResponse($this->urlGenerator->generate('app_login'));
//     }

//     public function onAuthenticationSuccess(
//         Request $request,
//         TokenInterface $token,
//         $providerKey
//     ): ?Response {
//         $event = new LoginSuccess($token->getUser());
//         $this->eventDispatcher->dispatch($event, LoginSuccess::NAME);

//         return new RedirectResponse($this->targetPathResolver->getPath());
//     }
// } #}