<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    /** Settings page */
    public function settings()
    {
        return view('admin.settings', ['user' => Auth::user()]);
    }

    /** Update profile name/email */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($request->only('name', 'email'));

        return redirect()->route('admin.settings')->with('success', 'Profil berhasil diperbarui.');
    }

    /** Change password */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->route('admin.settings')
                ->with('error', 'Password lama tidak sesuai.')
                ->withInput();
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.settings')->with('success', 'Password berhasil diubah.');
    }

    /** API: list new-user notifications */
    public function notifications(Request $request)
    {
        $readAt = $request->session()->get('admin_notif_read_at');

        $users = User::where('role', 'parent')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get(['id', 'name', 'email', 'created_at']);

        $cutoff = $readAt
            ? Carbon::parse($readAt)
            : Carbon::now()->subDays(7);

        $unread = $users->filter(fn($u) => $u->created_at > $cutoff)->count();

        return response()->json([
            'unread_count'  => $unread,
            'notifications' => $users->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'time'       => $u->created_at->diffForHumans(),
                'is_new'     => $u->created_at > $cutoff,
            ]),
        ]);
    }

    /** API: mark notifications as read */
    public function markNotificationsRead(Request $request)
    {
        $request->session()->put('admin_notif_read_at', Carbon::now()->toISOString());
        return response()->json(['success' => true]);
    }
}
