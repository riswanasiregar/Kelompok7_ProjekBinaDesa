<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - {{ $user->name }}</title>
</head>
<body>
    <h1>Edit Profile - {{ $user->name }}</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture">
        <br><br>

        <button type="submit">Update Profile</button>
    </form>

    @if($user->profile_picture)
        <h3>Current Profile Picture:</h3>
        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture" width="200">
        <br><br>
        <form action="{{ route('profile.destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete Profile Picture</button>
        </form>
    @endif

    <br>
    <a href="{{ route('profile.show') }}">Back to Profile</a>
    <br>
    <a href="/">Back to Dashboard</a>
</body>
</html>
