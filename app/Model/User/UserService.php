<?php

declare(strict_types=1);

namespace Model;

use Model\User\ReadModel\Queries\ActiveSkautisRoleQuery;
use Model\User\SkautisRole;
use Skautis\Skautis;
use stdClass;
use function property_exists;

final class UserService
{
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
     * Returns all available roles for current user
     *
     * @return stdClass[]
     */
    public function getAllSkautisRoles(bool $activeOnly = true) : array
    {
        $res = $this->skautis->user->UserRoleAll(['ID_User' => $this->getUserDetail()->ID, 'IsActive' => $activeOnly]);

        return $res instanceof stdClass ? [] : $res;
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
        foreach ($this->getAllSkautisRoles() as $r) {
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
        foreach ($this->getAllSkautisRoles() as $role) {
            if ($role->Key === 'superadmin') {
                return true;
            }
        }

        return false;
    }

    public function isDelegate() : bool
    {
        foreach ($this->getAllSkautisRoles() as $role) {
            if (property_exists($role, 'Key')
                && property_exists($role, 'ID_Group')
                && $role->Key === 'EventCongress'
                && $role->ID_Group === $this->congressGroupId
            ) {
                return true;
            }
        }

        return false;
    }
}
