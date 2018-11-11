<?php
namespace Main\Controller;

use E4u\Application\Controller\Security as E4uSecurity,
    E4u\Authentication\Identity,
    E4u\Authentication\Exception,
    E4u\Application\View,
    E4u\Response,
    Main\Model\User;

class SecurityController extends AbstractController implements E4uSecurity
{
    protected $requiredPrivileges = [ ];
    protected $defaultLayout = 'layout/security';

    public function passwordAction()
    {
        return $this->redirectTo('security/login');
    }

    /**
     * @return User|Response\Response
     */
    private function getUserFromParam()
    {
        $id = (int)$this->getParam('id');
        if (empty($id)) {
            return $this->redirectTo('/', 'Nieprawidłowy identyfikator użytkownika.', View::FLASH_ERROR);
        }

        $user = User::find($id);
        if (null === $user) {
            return $this->redirectTo('/', 'Nieprawidłowy identyfikator użytkownika.', View::FLASH_ERROR);
        }

        return $user;
    }

    /**
     * @param  User $user
     * @return $this|Response\Response
     */
    private function verifyToken($user)
    {
        $value = $this->getRequest()->getQuery('token');
        if (empty($value)) {
            return $this->redirectTo('/', 'Nieprawidłowy token użytkownika.', View::FLASH_ERROR);
        }

        $token = User\Token::findOneByUserTypeAndHash($user->id(), 'reset_password', $value);
        if (null === $token) {
            return $this->redirectTo('/', 'Nieprawidłowy token użytkownika.', View::FLASH_ERROR);
        }

        return $this;
    }

    public function resetAction()
    {
        $user = $this->getUserFromParam();
        $this->verifyToken($user);

        $form = new \Main\Form\Security\ResetPassword($this->getRequest());
        $form->getElement('login')->setValue(sprintf('%s (%s)', $user->getLogin(), $user->getEmail()));

        if ($form->isValid()) {
            $user->setPassword($form->getValue('password'))->save();
            return $this->redirectTo('security/login',
                "Hasło zostało zmienione. Zaloguj się używając nowego hasła.",
                View::FLASH_SUCCESS);
        }

        return [
            'form' => $form,
            'title' => 'Nowe hasło',
            'user' => $user,
        ];
    }

    /**
     * @param  User $user
     * @return Response\Redirect
     */
    private function loginSuccess(User $user)
    {
        $message = sprintf(
            'Zalogowano jako <strong>%s</strong> (%s).',
            $user->getPreference('company') ?: $user->getName(), $user->getLogin());
        $this->getView()->addFlash($message, View::FLASH_SUCCESS);

        if (isset($_SESSION['redirect_to'])) {
            $redirect = $_SESSION['redirect_to'];
            unset($_SESSION['redirect_to']);
            return $this->redirectTo($redirect);
        }

        return $this->redirectBackOrTo('/');
    }

    private function loginForm()
    {
        $form = new \Main\Form\Security\Login($this->getRequest());

        if ($form->isValid()) {
            $values = $form->getValues();
            try {

                /** @var User $user */
                $user = $this->getAuthentication()->login($values['login'], $values['password'], $values['remember']);
                return $this->loginSuccess($user);

            }
            catch (Exception\UserNotActiveException $e) {
                $form->addError('Użytkownik jest nieaktywny. <strong>Skontaktuj się z działem obsługi klienta</strong>, aby aktywować konto.', 'password');
            }
            catch (Exception\AuthenticationException $e) {
                $form->addError('Nieprawidłowa nazwa użytkownika lub hasło.', 'password');
            }
        }

        return $form;
    }

    /**
     * @return \Main\Form\Security\ForgotPassword|Response\Response
     */
    private function passwordForm()
    {
        $form = new \Main\Form\Security\ForgotPassword($this->getRequest());
        if ($form->isValid()) {
            $user = User::getRepository()->findOneByLogin($form->getValue('login'));
            if (!empty($user)) {

                $token = User\Token::create([
                    'user' => $user,
                    'type' => 'reset_password',
                    'expires_at' => (new \DateTime())->modify('+7 day'),
                ]);

                // SEND EMAIL TO $user
                \Main\Model\Mailer\Template::sendTemplate('security/ForgotPassword', [
                    'user' => $user->toArray(),
                    'url' => $this->urlTo('security/reset/' . $user->id() . '?token=' .  $token, true),
                ]);

                return $this->redirectTo('security/login', 'Na adres użytkownika
                    została wysłana wiadomość z instrukcją dot. zmiany hasła.
                    Jeżeli e-mail nie dotrze w ciągu 10 minut, skontaktuj się z nami.', View::FLASH_SUCCESS);

            }
            else {
                $form->addError('Nie znaleziono takiego użytkownika.', 'login');
            }
        }

        return $form;
    }

    public function loginAction()
    {
        $loginForm = $this->loginForm();
        $passwordForm = $this->passwordForm();

        return [
            'title' => 'Zaloguj się',
            'passwordForm' => $passwordForm,
            'loginForm' => $loginForm,
        ];
    }

    public function logoutAction()
    {
        $this->getAuthentication()->logout();
        return $this->redirectTo('/',
            "Zostałeś wylogowany.",
            \E4u\Application\View::FLASH_SUCCESS);
    }
}