<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>This is the Dashboard of Gratus</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        body.overflow-hedden {
            overflow: hidden;
        }

        .curtain {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 5000;
            overflow: hidden;
            background-color: #edfafc;
        }

        .curtain.disappear {
            animation-name: disappearing;
            animation-duration: 1s;
            animation-iteration-count: 1;
            animation-timing-function: ease;
        }

        .curtain svg.spin {
            animation-name: spin;
            animation-duration: 3s;
            animation-iteration-count: infinite;
            animation-timing-function: ease;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes disappearing {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>

</head>
<body class="sidebar-mini overflow-hedden">

    <div class="curtain">
        <svg class="spin" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 32 32"><g transform="matrix(.05696 0 0 .05696 .647744 2.43826)" fill="none" fill-rule="evenodd"><circle r="50.167" cy="237.628" cx="269.529" fill="#00d8ff"/><g stroke="#00d8ff" stroke-width="24"><path d="M269.53 135.628c67.356 0 129.928 9.665 177.107 25.907 56.844 19.57 91.794 49.233 91.794 76.093 0 27.99-37.04 59.503-98.083 79.728-46.15 15.29-106.88 23.272-170.818 23.272-65.554 0-127.63-7.492-174.3-23.44-59.046-20.182-94.61-52.103-94.61-79.56 0-26.642 33.37-56.076 89.415-75.616 47.355-16.51 111.472-26.384 179.486-26.384z"/><path d="M180.736 186.922c33.65-58.348 73.28-107.724 110.92-140.48C337.006 6.976 380.163-8.48 403.43 4.937c24.248 13.983 33.042 61.814 20.067 124.796-9.8 47.618-33.234 104.212-65.176 159.6-32.75 56.788-70.25 106.82-107.377 139.272-46.98 41.068-92.4 55.93-116.185 42.213-23.08-13.3-31.906-56.92-20.834-115.233 9.355-49.27 32.832-109.745 66.8-168.664z"/><path d="M180.82 289.482C147.075 231.2 124.1 172.195 114.51 123.227c-11.544-59-3.382-104.11 19.864-117.566 24.224-14.024 70.055 2.244 118.14 44.94 36.356 32.28 73.688 80.837 105.723 136.173 32.844 56.733 57.46 114.21 67.036 162.582 12.117 61.213 2.31 107.984-21.453 121.74-23.057 13.348-65.25-.784-110.24-39.5-38.013-32.71-78.682-83.253-112.76-142.115z"/></g></g></svg>
    </div>

    @yield('content')

    <script>
        function raiseCurtain() {
            setTimeout(function() {
                let curtain = document.querySelector(".curtain");
                let svg = curtain.querySelector("svg");
                svg.classList.remove("spin");
                curtain.classList.add("disappear");
                setTimeout(() => {
                    let parent = curtain.parentNode;
                    parent.removeChild(curtain);
                    document.body.classList.remove("overflow-hedden");
                }, 1000);
            }, 500);
        }
    </script>
    <script defer src="{{ asset('storage/js/api-mobile-documentor.js') }}" onload="raiseCurtain()"></script>
</body>
</html>
