<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/capsule.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$search = $_GET['search'] ?? '';

$users =  Capsule::table('users')
    ->when($search, fn ($query, $search) => $query->where('email', 'like', "%{$search}%"))
    ->limit(10)
    ->get()
    ->toArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>
<h1>User List</h1>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?php echo $user->name; ?> - <?php echo $user->email; ?>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
