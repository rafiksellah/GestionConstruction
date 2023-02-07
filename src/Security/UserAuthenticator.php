<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class UserAuthenticator extends AbstractLoginFormAuthenticator
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
                new RememberMeBadge(),
            ]
            
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        $roles = $token->getRoleNames();
        // dd($roles);
        // $roles = array_map(function($role) {
        //     return $role->getRole();
        // }, $roles);

        if (in_array('ROLE_ADMIN', $roles)) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_admin'));
        } elseif (in_array('ROLE_DECORTIQUEUR', $roles)) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_decortiqueur_plan_deco_index'));
        } elseif (in_array('ROLE_USER', $roles)) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_client'));
        } else {
            $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        return $response;
        }
        //  return new RedirectResponse($this->urlGenerator->generate('app_login'));
    //     throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    // }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}