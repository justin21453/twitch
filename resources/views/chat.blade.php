<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    @vite('resources/js/app.js')
    <title>Chat</title>
</head>

<body>
</body>

<style>
    .scroll-left {
        position: absolute;
        left: 100%;
        animation: move-words 40s linear;
        /* text-shadow: 1px 1px 0 black, -1px -1px 0 black; */
        text-shadow: 1px 1px 0 black, 1px -1px 0 black, -1px 1px 0 black, -1px -1px 0 black;
        color: white;
        font-size: 30px;
        font-weight: bold;
    }

    @keyframes move-words {
        0% {
            left: 100%;
        }

        100% {
            left: -100%;
        }
    }
</style>

</html>
<script type="module" src="{{ asset('js/chat.js') }}"></script>
