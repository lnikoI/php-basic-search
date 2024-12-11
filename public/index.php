<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/capsule.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$search = $_GET['search'] ?? '';

$users = Capsule::table('users')
                ->when(
                        $search,
                    fn ($query, $search) => $query
                        ->where('email', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                )
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
<body class="bg-gray-300">
<div class="m-12">
    <form action="">
        <div class="flex flex-col max-w-48">
            <input class="p-1" type="text" id="search" name="search" value="<?php echo $_GET['search'] ?>">

            <input type="submit" value="Search" class="bg-blue-600 hover:bg-blue-900">
        </div>
    </form>

    <div class="mt-3">
        <table class="table-auto">
            <thead>
            <tr class="bg-gray-500">
                <th>Name</th>
                <th>Email</th>
                <th>Created at</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr class="bg-gray-200">
                    <td class="p-1"><?php echo $user->name; ?></td>
                    <td class="p-1"><?php echo $user->email; ?></td>
                    <td class="p-1"><?php echo $user->created_at; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
