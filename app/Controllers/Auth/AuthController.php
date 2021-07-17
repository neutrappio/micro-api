<?php
declare(strict_types=1);

namespace Mapi\Controllers\Auth;

use Exception;
use DateTimeImmutable;

use Lcobucci\JWT\Token;

use Mapi\Models\User;
use Mapi\Core\Controller;
use Mapi\Models\User\Actions;
use Mapi\Libraries\Http\HttpCodes;
use Mapi\Exceptions\PublicException;
use Mapi\Validators\LoginValidator;
use Mapi\Validators\RegisterValidator;

/**
 *  Client Auth Controller
 */
class AuthController extends Controller
{
    /** @var string  */
    const REMEMBER_CLAIM = 'rmb';
    
    /** @var string */
    const AUTH_TOKEN_TYPE = 'bearer';
    
    /** @var string  */
    const TOKEN_EXPIRE_TIME = "+24 hours";
    
    /** @var string */
    const TOKEN_EXPIRE_TIME_REMEMBER = "+15 days";

    /**
     * Login Action
     *
     * @return array
     */
    public function login() :? array
    {
        $validator = new LoginValidator();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        foreach ($errors as $error) {
            throw new PublicException($error->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }

        $user = User::findFirstByEmail($postData['email']);

        if (!$user) {
            // sleep(rand(1, 5)); // slow down if its using brutforce attack
            throw new PublicException('ERROR_INVALID_CREDENTIALS', HttpCodes::HTTP_UNAUTHORIZED);
        }

        if (!$user->validatePassword($postData['password'])) {
            throw new PublicException('ERROR_INVALID_CREDENTIALS', HttpCodes::HTTP_UNAUTHORIZED);
        }

        // remember me
        $remember = isset($postData['remember']);

        // return new JWT Token
        $token = $this->generateJWTSessionToken($user, $remember ? self::TOKEN_EXPIRE_TIME : self::TOKEN_EXPIRE_TIME_REMEMBER, $remember ? [self::REMEMBER_CLAIM => 1] : [])->toString();
        
        return $this->returnSession($user->getProfile(), $token);
    }
    

    /**
     * Session
     */
    public function session() :? array
    {
        if (false === $this->getDi()->has('user')) {
            throw new PublicException('ERROR_AUTHORIZATION_REQUIRED', HttpCodes::HTTP_UNAUTHORIZED);
        }

        $user = $this->getDi()->get('user');

        // remember me
        $remember = $user->getSessionToken()->claims()->get(self::REMEMBER_CLAIM);

        if ($remember) {
            // expiration
            $expiration = $user->getSessionToken()->claims()->get('exp')->getTimeStamp();
        
            if ($expiration > time() && $expiration <= (time() + 60 * 60 * 2)) {
                $user->setSessionToken($this->generateJWTSessionToken($user, self::TOKEN_EXPIRE_TIME_REMEMBER, [self::REMEMBER_CLAIM => 1]));
            }
        }

        return $this->returnSession($user->getProfile(), $user->getSessionToken()->toString());
    }

    /**
     * Login Action
     *
     * @return array
     */
    public function register() :? array
    {
        $validator = new RegisterValidator();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        foreach ($errors as $error) {
            throw new PublicException($error->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }

        $user = new User;
        $user->assign($postData, [
            'firstname',
            'lastname',
            'email',
            'password',
            'country_id',
            'city',
        ]);

        // save user
        $errors = $user->save();
        
        if (!$errors) {
            foreach ($user->getMessages() as $error) {
                throw new PublicException($error->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
            }
        }

        
        // return new JWT Token
        return [
            'message' => 'SUCCESS_ACCOUNT_REGISTERED'
        ];
    }

    /**
     * Forgot password action
     *
     * @return array
     */
    public function forgot() : array
    {
        $email = $this->request->get('email');

        if (!User::isValidEmail($email)) {
            throw new PublicException('ERROR_INVALID_EMAIL', HttpCodes::HTTP_BAD_REQUEST);
        }

        $user = User::findFirstByEmail($email);

        if ($user) {
            $this->db->begin();

            $action = new Actions();
            $action->user_id = $user->id;

            $save = $action->create();

            try {
                if (!$save) {
                    throw new Exception(implode(", ", $action->getMessages()));
                }

                $url = $this->config->application->url;

                $this->mailer->sendMailTemplate($email, 'Reset password', "reset.mail.html", $user->toArray() + [
                    "action"=> "Reset Password",
                    "url"=> $url,
                    "link"=> $url . "reset-password/" . $action->token,
                ]);

                $this->db->commit();
            } catch (Exception $e) {
                $this->db->rollback();

                throw new PublicException("Sending mail has failed! " . $e->getMessage(), HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        
        return ['action'=> 'ok'];
    }


    /**
     * Return session token
     *
     * @param array $user
     * @param string $token
     * @param string $type
     * @return array
     */
    private function returnSession(array $user, string $token, string $type = self::AUTH_TOKEN_TYPE) : array
    {
        return  [
            'user'=> $user,
            'access_token'=> $token,
            'token_type'=> $type
        ];
    }

    /**
     * Create New JWT Session Token
     *
     * @param User $user
     * @return string
     */
    public function generateJWTSessionToken(User $user, string $expire = "+24 hours", array $claims = [], array $headers = []) :? Token
    {
        $appConfig =  $this->config->jwt;
        $config = $this->getDI()->getShared('jwt')["config"];
        $now   = new DateTimeImmutable();

        $generatedToken =  $config->builder()
            // Configures the issuer (iss claim)
            ->issuedBy($appConfig->url)
            // Configures the audience (aud claim)
            ->permittedFor($appConfig->url)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the id (jti claim)
            ->identifiedBy(md5($user->id))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify($expire))
            // Configures a new claim, called "uid"
            ->withClaim('uid', $user->id);

        foreach ($claims as $key => $val) {
            $generatedToken->withClaim($key, $val);
        }

        foreach ($headers as $key => $val) {
            $generatedToken->withHeader($key, $val);
        }
        
        return $generatedToken
            // Builds a new token
            ->getToken($config->signer(), $config->signingKey());
    }
}
