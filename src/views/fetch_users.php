<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$search = $_GET['search'] ?? '';

$users = Capsule::table('users')
                ->when($search, fn ($query, $search) => $query->where('email', 'like', "%{$search}%"))
                ->limit(10)
                ->get()
                ->toArray();
?>
