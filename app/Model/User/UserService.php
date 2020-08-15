<?php

declare(strict_types=1);

namespace Model;

use eGen\MessageBus\Bus\QueryBus;
use Model\User\ReadModel\Queries\ActiveSkautisRoleQuery;
use Model\User\ReadModel\Queries\IsUserOnCommissionMembersListQuery;
use Model\User\ReadModel\Queries\IsUserOnDelegateListQuery;
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
    private QueryBus $queryBus;

    private int $congressGroupId;
    private int $congressEventId;

    public function __construct(
        int $congressGroupId,
        int $congressEventId,
        Skautis $skautis,
        QueryBus $queryBus
    ) {
        $this->skautis         = $skautis;
        $this->queryBus        = $queryBus;
        $this->congressGroupId = $congressGroupId;
        $this->congressEventId = $congressEventId;
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
            if (! property_exists($role, 'Key')) {
                return false;
            }
            $isDelegate = property_exists($role, 'ID_Group') && $role->Key === 'EventCongress' && $role->ID_Group === $this->congressGroupId;

            return $isDelegate || $role->Key === 'superadmin';
        });

        return $res;
    }

    public function getUserDetail() : stdClass
    {
        return $this->skautis->user->UserDetail();
    }

    public function getUserPersonId() : int
    {
        return $this->getUserDetail()->ID_Person;
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
     * Vrací seznam členů komise pro e-volby.
     *
     * @return stdClass[]
     */
    public function getCommissionMembers() : array
    {
        $res = $this->skautis->event->EventCongressEcommissionAll(['ID_EventCongress' => $this->congressEventId]);

        return $res instanceof stdClass ? [] : $res;
    }

    /**
     * kontroluje jestli je přihlášení platné
     */
    public function isLoggedIn(bool $hardCheck) : bool
    {
        return $this->skautis->getUser()->isLoggedIn($hardCheck);
    }

    public function isSuperUser() : bool
    {
        return $this->getActualRole()->getKey() === self::ROLE_KEY_SUPERADMIN;
    }

    public function isDelegate() : bool
    {
        if ($this->getActualRole()->getKey() !== self::ROLE_KEY_DELEGATE) {
            return false;
        }

        return $this->queryBus->handle(new IsUserOnDelegateListQuery($this->getUserPersonId()));
    }

    public function isCommissionMember() : bool
    {
        if ($this->getActualRole()->getKey() !== self::ROLE_KEY_DELEGATE) {
            return false;
        }

        return $this->queryBus->handle(new IsUserOnCommissionMembersListQuery($this->getUserPersonId()));
    }
}
