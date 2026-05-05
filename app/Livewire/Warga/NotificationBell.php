<?php

namespace App\Livewire\Warga;

use Livewire\Component;

class NotificationBell extends Component
{
    public function getUnreadCountProperty()
    {
        if (!auth()->check()) return 0;
        return auth()->user()->unreadNotifications()->count();
    }

    public function getNotificationsProperty()
    {
        if (!auth()->check()) return [];
        return auth()->user()->notifications()->latest()->limit(10)->get();
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        // Redirect to report detail
        return $this->redirect(route('pengaduan.feed-detail', $notification->data['kode_tracking']), navigate: true);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.warga.notification-bell');
    }
}
