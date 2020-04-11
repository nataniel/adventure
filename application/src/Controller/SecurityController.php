<?php
namespace Main\Controller;

use E4u\Authentication\Social;
use E4u\Application\View,
    Main\Model\User;
use Main\Configuration;

class SecurityController extends AbstractController
{
    protected $requiredPrivileges = [ ];
    protected $defaultLayout = 'layout/security';

    public function indexAction()
    {
        return [
            'title' => 'Zaloguj się...',
        ];
    }

    public function logoutAction()
    {
        $this->getAuthentication()->logout();
        return $this->redirectTo('/',
            "Zostałeś wylogowany.",
            View::FLASH_SUCCESS);
    }

    public function googleAction()
    {
        $helper = new Social\Google(Configuration::googleConfig(), $this->getRequest());
        if ($helper->loginFromRedirect()) {

            $user = $this->getUserFromSocial($helper, User\Profile\Google::class);
            $this->setAvatarIfEmpty($user, $helper->getPicture());

            $user->save();
            return $this->loginAs($user);

        }

        $loginUrl = $helper->getLoginUrl();
        return $this->redirectTo($loginUrl);
    }

    public function microsoftAction()
    {
        $helper = new Social\Microsoft(Configuration::microsoftConfig(), $this->getRequest());
        if ($helper->loginFromRedirect()) {

            $user = $this->getUserFromSocial($helper, User\Profile\Microsoft::class);
            $this->setAvatarIfEmpty($user, $helper->getPicture());

            $user->save();
            return $this->loginAs($user);

        }

        $loginUrl = $helper->getLoginUrl();
        return $this->redirectTo($loginUrl);
    }

    public function facebookAction()
    {
        $helper = new Social\Facebook(Configuration::facebookConfig(), $this->getRequest());
        if ($helper->loginFromRedirect()) {

            $user = $this->getUserFromSocial($helper, User\Profile\Facebook::class);
            $this->setAvatarIfEmpty($user, $helper->getPicture());

            $user->save();
            return $this->loginAs($user);

        }

        $loginUrl = $helper->getLoginUrl();
        return $this->redirectTo($loginUrl);
    }

    public function twitterAction()
    {
        $helper = new Social\Twitter(Configuration::twitterConfig(), $this->getRequest());
        if ($helper->loginFromRedirect()) {

            $user = $this->getUserFromSocial($helper, User\Profile\Twitter::class);
            $this->setAvatarIfEmpty($user, $helper->getPicture());

            $user->save();
            return $this->loginAs($user);

        }

        $loginUrl = $helper->getLoginUrl();
        return $this->redirectTo($loginUrl);
    }

    public function steamAction()
    {
        $helper = new Social\Steam(Configuration::steamConfig(), $this->getRequest());
        if ($helper->loginFromRedirect()) {

            $user = $this->getUserFromSocial($helper, User\Profile\Steam::class);
            $this->setAvatarIfEmpty($user, $helper->getPicture());

            $user->save();
            return $this->loginAs($user);

        }

        $loginUrl = $helper->getLoginUrl();
        return $this->redirectTo($loginUrl);
    }

    private function loginAs(User $user)
    {
        if (!$user->isActive()) {
            $this->redirectTo('/',
                sprintf('Użytkownik <strong>%s</strong> jest nieaktywny. <strong>Skontaktuj się z działem obsługi klienta</strong>, aby aktywować konto.', $user->getLogin()),
                View::FLASH_ERROR);
        }

        $this->getAuthentication()->loginAs($user);
        return $this->redirectBackOrTo('/', [
            'Zalogowano jako <strong>%s</strong> (%s).',
            $user->getName(),
            $user->getLogin(),
        ], View::FLASH_SUCCESS);
    }

    /**
     * @param User $user
     * @param string $picture
     */
    private function setAvatarIfEmpty(User $user, $picture)
    {
//        if (empty($user->getAvatar()) && !empty($picture)) {
//            $user->setAvatar($picture);
//        }
    }

    /**
     * @param  Social\Helper $social
     * @param  string $profileClass
     * @return User
     */
    private function getUserFromSocial(Social\Helper $social, $profileClass)
    {
        // Profile already exists - login as connected user
        $profile = User\Profile::getRepository()->findOneByTypeAndProfileId($profileClass, $social->getId());
        if (!empty($profile)) {
            return $profile->getUser();
        }

        $profile = new $profileClass([
            'profile_id' => $social->getId(),
        ]);

        // Profile does not exists, but maybe user with primary email exists
        $user = User::getRepository()->findOneByLogin($social->getEmail());
        if (!empty($user)) {
            $user->addToProfiles($profile);
            return $user;
        }

        // No profile, no user - create new user with no password
        return new User([
            'login' => $social->getEmail(),
            'email' => $social->getEmail(),
            'name' => trim($social->getFirstName() . ' ' . $social->getLastName()),
            # 'avatar' => $social->getPicture(),
            'locale' => $social->getLocale(),
            'profiles' => [ $profile ],
            'active' => true,
        ]);
    }
}