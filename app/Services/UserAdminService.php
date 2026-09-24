<?php

namespace App\Services;

use App\Exceptions\CannotDeleteUserException;
use App\Models\Hero;
use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class UserAdminService
{
    public function __construct(private UsersRepository $usersRepository) {}

    public function list(?string $search): LengthAwarePaginator
    {
        return $this->usersRepository->paginate($search);
    }

    public function details(User $user): User
    {
        return $this->usersRepository->loadDetails($user);
    }

    /**
     * @param  array{name: string, email: string, is_active: bool, is_superadmin: bool}  $data
     *
     * @throws \InvalidArgumentException gdy superadmin próbuje odebrać uprawnienia sobie samemu (blokada konta)
     */
    public function update(User $actor, User $user, array $data): User
    {
        if ($actor->is($user) && ! $data['is_superadmin']) {
            throw new \InvalidArgumentException('Nie możesz odebrać sobie roli superadmina.');
        }

        if ($actor->is($user) && ! $data['is_active']) {
            throw new \InvalidArgumentException('Nie możesz dezaktywować własnego konta.');
        }

        return $this->usersRepository->update($user, $data);
    }

    /**
     * @throws CannotDeleteUserException
     */
    public function delete(User $actor, User $user): void
    {
        if ($actor->is($user)) {
            throw CannotDeleteUserException::self();
        }

        if ($user->is_superadmin) {
            throw CannotDeleteUserException::superadmin();
        }

        if ($this->usersRepository->ownsCampaigns($user)) {
            throw CannotDeleteUserException::ownsCampaigns();
        }

        $this->usersRepository->delete($user);
    }

    public function hero(int $heroId): Hero
    {
        return $this->usersRepository->findHeroWithDetails($heroId) ?? abort(404);
    }
}
