<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\UserBadge;
use Symfony\Component\Security\Core\User\User;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return str_starts_with($request->getPathInfo(), '/api/');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('Authorization');
        $expectedToken = '5bb70c57-9ca1-4bbf-b189-5d01cd1a80e5';  // Uwaga: przechowuj tokeny w sposób bezpieczny

        if (!$token || $token !== $expectedToken) {
            throw new CustomUserMessageAuthenticationException('Invalid or missing API token');
        }
// validator i translacja
        return new SelfValidatingPassport(new UserBadge('api_user', function() {
            return new User('api_user', null, ['ROLE_API']);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Autoryzacja udana, możesz zwrócić null, aby po prostu kontynuować przetwarzanie żądania
        // lub przekierować użytkownika gdzieś indziej, jeśli jest to wymagane
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => 'Authentication Failed: ' . $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}
