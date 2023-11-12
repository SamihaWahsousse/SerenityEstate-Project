<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /* Verify the user credentials from the login form using the request object that return a passport
   that define a list of badges to validate the user authenticated */

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    /* if all the user validations are done the function "onAuthenticationSuccess"
    will redirect the autheticated user to the target path -home page- */

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        $user = $token->getUser();
        
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('admin_panel'));
        }
 
        if (in_array('ROLE_MANAGER', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_agency_management'));
        }
        if (in_array('ROLE_AGENT', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_agency_management'));
        }

         if (in_array('ROLE_CLIENT', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }
 
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    //Generate the url associated to the login route app_login 
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}