<?php

namespace App\Http\Controllers\Panel;

use App\Exceptions\CannotDeleteUserException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Zarządzanie użytkownikami przez superadmina. Bohaterowie są tu dostępni wyłącznie do podglądu.
 */
class SuperadminUsersController extends Controller
{
    public function __construct(private readonly UserAdminService $userAdminService) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $users = $this->userAdminService->list($search);

        return view('Panel.superadmin.users.index', compact('users', 'search'));
    }

    public function show(User $user): View
    {
        $user = $this->userAdminService->details($user);

        return view('Panel.superadmin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('Panel.superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'is_active' => ['sometimes', 'boolean'],
            'is_superadmin' => ['sometimes', 'boolean'],
        ]);

        // Niezaznaczony checkbox nie jest wysyłany — brak klucza oznacza false.
        $data['is_active'] = $request->boolean('is_active');
        $data['is_superadmin'] = $request->boolean('is_superadmin');

        try {
            $this->userAdminService->update($request->user(), $user, $data);
        } catch (\InvalidArgumentException $exception) {
            return back()->withInput()->withErrors(['user' => $exception->getMessage()]);
        }

        return redirect()->route('panel.superadmin.users.show', $user)->with('status', 'Zapisano zmiany.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        try {
            $this->userAdminService->delete($request->user(), $user);
        } catch (CannotDeleteUserException $exception) {
            return back()->withErrors(['user' => $exception->getMessage()]);
        } catch (\Throwable $exception) {
            Log::error('ERROR DELETING USER: ' . $exception->getMessage(), ['user_id' => $user->id]);

            return back()->withErrors(['user' => 'Wystąpił błąd podczas usuwania użytkownika.']);
        }

        return redirect()->route('panel.superadmin.users.index')->with('status', "Usunięto użytkownika {$user->name}.");
    }
}
