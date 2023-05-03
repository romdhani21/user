<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if (in_array('ROLE_USER', $token->getUser()->getRoles(), true)) {
            // If user is an agency
            if ($token->getUser()->isBlocked()) {
                // If user is blocked, redirect to blocked page
                return new RedirectResponse($this->urlGenerator->generate('blocked'));
            } else {
                // If user is not blocked, redirect to agency page
                return new RedirectResponse($this->urlGenerator->generate('userindex'));
            }
        } elseif (in_array('ROLE_CHAUFFEUR', $token->getUser()->getRoles(), true)) {
            // If user is a boutique
            if ($token->getUser()->isBlocked()) {
                // If user is blocked, redirect to blocked page
                return new RedirectResponse($this->urlGenerator->generate('blocked'));
            } else {
                // If user is not blocked, redirect to boutique page
                return new RedirectResponse($this->urlGenerator->generate('boutique'));
            }
        
        } elseif (in_array('ROLE_ADMIN', $token->getUser()->getRoles(), true)) {
            // If user is an admin
            if ($token->getUser()->isBlocked()) {
                // If user is blocked, redirect to blocked page
                return new RedirectResponse($this->urlGenerator->generate('blocked'));
            } else {
                // If user is not blocked, redirect to admin page
                return new RedirectResponse($this->urlGenerator->generate('display'));
            }
        } else {
            // If user has no role or unrecognized role, redirect to login page
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
    }        
    
    
    
    


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

}
