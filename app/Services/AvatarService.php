<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarService
{
    /**
     * Available avatar styles and their configurations
     */
    private static $avatarStyles = [
        'human' => [
            'woman_1', 'woman_2', 'woman_3', 'woman_4', 'woman_5', 'woman_6', 'woman_7', 'woman_8',
            'woman_9', 'woman_10', 'woman_11', 'woman_12', 'woman_13', 'woman_14', 'woman_15', 'woman_16',
            'man_1', 'man_2', 'man_3', 'man_4', 'man_5', 'man_6', 'man_7', 'man_8',
            'man_9', 'man_10', 'man_11', 'man_12', 'man_13', 'man_14', 'man_15', 'man_16'
        ]
    ];

    /**
     * Get all available avatar identifiers
     */
    public static function getAllAvatars(): array
    {
        $avatars = [];
        foreach (self::$avatarStyles as $category => $items) {
            foreach ($items as $item) {
                $avatars[] = "{$category}_{$item}";
            }
        }
        return $avatars;
    }

    /**
     * Get available (unused) avatars
     */
    public static function getAvailableAvatars(): array
    {
        $allAvatars = self::getAllAvatars();
        $usedAvatars = User::whereNotNull('avatar')->pluck('avatar')->toArray();
        
        return array_diff($allAvatars, $usedAvatars);
    }

    /**
     * Assign a unique avatar to a user
     */
    public static function assignUniqueAvatar(User $user): string
    {
        // If user already has an avatar, return it
        if ($user->avatar) {
            return $user->avatar;
        }

        // Get available avatars
        $availableAvatars = self::getAvailableAvatars();
        
        // If no avatars available, generate a unique one
        if (empty($availableAvatars)) {
            $avatar = self::generateUniqueAvatar();
        } else {
            // Assign a random available avatar
            $avatar = $availableAvatars[array_rand($availableAvatars)];
        }

        // Update user with the avatar
        $user->update(['avatar' => $avatar]);
        
        return $avatar;
    }

    /**
     * Generate a unique avatar when all predefined ones are used
     */
    private static function generateUniqueAvatar(): string
    {
        $timestamp = time();
        $random = Str::random(6);
        return "generated_{$timestamp}_{$random}";
    }

    /**
     * Release a user's avatar back to the available pool
     */
    public static function releaseAvatar(User $user): void
    {
        if ($user->avatar && !str_starts_with($user->avatar, 'generated_')) {
            $user->update(['avatar' => null]);
        }
    }

    /**
     * Change user's avatar to a different unique one
     */
    public static function changeAvatar(User $user): string
    {
        // Release current avatar
        self::releaseAvatar($user);
        
        // Assign new unique avatar
        return self::assignUniqueAvatar($user);
    }

    /**
     * Get avatar URL for display
     */
    public static function getAvatarUrl(?string $avatar): string
    {
        if (!$avatar) {
            return self::getDefaultAvatarUrl();
        }

        if (str_starts_with($avatar, 'generated_')) {
            return self::getGeneratedAvatarUrl($avatar);
        }

        return self::getPredefinedAvatarUrl($avatar);
    }

    /**
     * Get predefined avatar URL
     */
    private static function getPredefinedAvatarUrl(string $avatar): string
    {
        // Use real human avatar images from DiceBear API with human style
        $parts = explode('_', $avatar, 2);
        $seed = $parts[1] ?? $avatar;
        
        return "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($seed) . 
               "&size=128&radius=50&backgroundColor=b6e3f4,c0aede,d1d4f9";
    }

    /**
     * Get generated avatar URL
     */
    private static function getGeneratedAvatarUrl(string $avatar): string
    {
        // Use the unique identifier as seed for generated avatar
        return "https://ui-avatars.com/api/?name={$avatar}&size=128&background=6366f1&color=fff&bold=true&format=svg";
    }

    /**
     * Get default avatar URL
     */
    private static function getDefaultAvatarUrl(): string
    {
        return "https://ui-avatars.com/api/?name=User&size=128&background=9ca3af&color=fff&bold=true&format=svg";
    }

    /**
     * Get background color based on avatar category
     */
    private static function getAvatarColor(string $avatar): string
    {
        $colors = [
            'animals' => '10b981',      // green
            'nature' => '3b82f6',       // blue
            'space' => '8b5cf6',        // purple
            'technology' => 'f59e0b',   // amber
            'food' => 'ef4444',         // red
            'sports' => '06b6d4',       // cyan
            'music' => 'ec4899',        // pink
            'art' => 'f97316'           // orange
        ];

        $category = explode('_', $avatar)[0];
        return $colors[$category] ?? '6b7280'; // gray default
    }

    /**
     * Get avatar display name
     */
    public static function getAvatarDisplayName(string $avatar): string
    {
        if (str_starts_with($avatar, 'generated_')) {
            return 'Unique Avatar';
        }

        $parts = explode('_', $avatar, 2);
        $type = $parts[0] ?? 'human';
        $number = $parts[1] ?? '1';
        
        if ($type === 'woman') {
            return "Woman " . $number;
        } elseif ($type === 'man') {
            return "Man " . $number;
        }
        
        return 'Human Avatar';
    }

    /**
     * Initialize avatar for all users without one
     */
    public static function initializeAllUserAvatars(): int
    {
        $usersWithoutAvatar = User::whereNull('avatar')->get();
        $count = 0;

        foreach ($usersWithoutAvatar as $user) {
            self::assignUniqueAvatar($user);
            $count++;
        }

        return $count;
    }

    /**
     * Get avatar statistics
     */
    public static function getAvatarStats(): array
    {
        $allAvatars = self::getAllAvatars();
        $usedAvatars = User::whereNotNull('avatar')->pluck('avatar')->toArray();
        $availableAvatars = array_diff($allAvatars, $usedAvatars);

        $usedByCategory = [];
        foreach ($usedAvatars as $avatar) {
            if (!str_starts_with($avatar, 'generated_')) {
                $category = explode('_', $avatar)[0];
                $usedByCategory[$category] = ($usedByCategory[$category] ?? 0) + 1;
            }
        }

        return [
            'total_avatars' => count($allAvatars),
            'used_avatars' => count($usedAvatars),
            'available_avatars' => count($availableAvatars),
            'generated_avatars' => count(array_filter($usedAvatars, fn($a) => str_starts_with($a, 'generated_'))),
            'used_by_category' => $usedByCategory
        ];
    }
}
