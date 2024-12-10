<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/capsule.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$search = $_GET['search'] ?? '';

$users = Capsule::table('users')
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
    <link href="./css/output.css" rel="stylesheet">
    <title>User List</title>
</head>
<body class="bg-gray-500">
<div class="m-12">
    <form action="">
        <div class="flex flex-col max-w-48">
            <input class="p-1" type="text" id="search" name="search">

            <input type="submit" value="Search" class="bg-blue-600 hover:bg-blue-900">
        </div>
    </form>

    <div class="mt-3">
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <?php echo $user->name; ?> - <?php echo $user->email; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</body>
</html>
