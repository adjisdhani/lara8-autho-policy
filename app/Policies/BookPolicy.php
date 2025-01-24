<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    /**
     * Determine whether the user can view any books.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['viewer', 'admin']);
    }

    /**
     * Determine whether the user can view a book.
     */
    public function view(User $user, Book $book)
    {
        return in_array($user->role, ['viewer', 'admin']);
    }

    /**
     * Determine whether the user can create books.
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the book.
     */
    public function update(User $user, Book $book)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the book.
     */
    public function delete(User $user, Book $book)
    {
        return $user->role === 'admin';
    }
}