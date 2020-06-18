<?php

declare(strict_types=1);

namespace Model;

use Model\User\ReadModel\Queries\ActiveSkautisRoleQuery;
use Model\User\SkautisRole;
use Skautis\Skautis;
use stdClass;
use function array_filter;
use function property_exists;

final class UserService
{
    public const ROLE_KEY_SUPERADMIN = 'superadmin';
    public const ROLE_KEY_DELEGATE   = 'EventCongress';

    private Skautis $skautis;
    private int $congressGroupId;

    public function __construct(int $congressGroupId, Skautis $skautis)
    {
        $this->skautis         = $skautis;
        $this->congressGroupId = $congressGroupId;
    }

    /**
     * varcí ID role aktuálně přihlášeného uživatele
     */
    public function getRoleId() : ?int
    {
        return $this->skautis->getUser()->getRoleId();
    }

    /**
     * @return stdClass[]
     */
    public function getRelatedSkautisRoles() : array
    {
        $res = $this->skautis->user->UserRoleAll(['ID_User' => $this->getUserDetail()->ID]);
        $res = $res instanceof stdClass ? [] : $res;
        $res = array_filter($res, function ($role) {
            return property_exists($role, 'Key') &&
                ($role->Key === 'superadmin'
                    || (property_exists($role, 'ID_Group') && $role->Key === 'EventCongress' && $role->ID_Group === $this->congressGroupId));
        });

        return $res;
    }

    public function getUserDetail() : stdClass
    {
        return $this->skautis->user->UserDetail();
    }

    /**
     * změní přihlášenou roli do skautISu
     */
    public function updateSkautISRole(int $id) : void
    {
        $response = $this->skautis->user->LoginUpdate([
            'ID_UserRole' => $id,
            'ID' => $this->skautis->getUser()->getLoginId(),
        ]);
        if (! $response) {
            return;
        }

        $this->skautis->getUser()->updateLoginData(null, $id, $response->ID_Unit);
    }

    /**
     * informace o aktuálně přihlášené roli
     *
     * @internal  Use query bus with ActiveSkautisRoleQuery
     *
     * @see ActiveSkautisRoleQuery
     */
    public function getActualRole() : ?SkautisRole
    {
        foreach ($this->getRelatedSkautisRoles() as $r) {
            if ($r->ID === $this->getRoleId()) {
                return new SkautisRole($r->Key ?? '', $r->DisplayName, $r->ID_Unit, $r->Unit);
            }
        }

        return null;
    }

    /**
     * kontroluje jestli je přihlášení platné
     */
    public function isLoggedIn() : bool
    {
        return $this->skautis->getUser()->isLoggedIn();
    }

    public function updateLogoutTime() : void
    {
        $this->skautis->getUser()->updateLogoutTime()->getLogoutDate();
    }

    public function isSuperUser() : bool
    {
        return $this->getActualRole()->getKey() === self::ROLE_KEY_SUPERADMIN;
    }

    public function isDelegate() : bool
    {
        return $this->getActualRole()->getKey() === self::ROLE_KEY_DELEGATE;
    }
}
