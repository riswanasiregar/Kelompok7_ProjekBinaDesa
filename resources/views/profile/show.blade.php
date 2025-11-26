<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>

    @if($user->profile_picture)
        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture" width="200">
    @else
        <p>No profile picture uploaded.</p>
    @endif

    <br><br>
    <a href="{{ route('profile.edit') }}">Edit Profile</a>
</body>
</html>